<?php

namespace Blocky;

use Pimple\Container;
use Blocky\Config\YamlWrapper;

/**
 * Blockys main core
 *
 * @package Blocky
 * @author Daniel Simon
 **/
class Application extends Container
{

	/**
	 * This is the jump start of the system, it registers all crucial services' providers.
	 * Further more, it sets 2 event listeners, one for BootstrapFinished -which will be emited after __construct has finished
	 * And the other one is for if the core is ready to take off.
	 *
	 * @return void
	 * @author Daniel Simon
	 **/
	public function __construct($values = [])
	{
		$values['site'] = 'frontend';

		parent::__construct($values);

		$this->register(new Provider\EventServiceProvider(), []);
		$this->register(new Provider\SessionServiceProvider(), []);
		$this->register(new Provider\PathServiceProvider(), []);
		$this->register(new Provider\ConfigServiceProvider(), []);
		$this->register(new Provider\ContentServiceProvider(), []);
		$this->register(new Provider\StorageServiceProvider(), []);
		$this->register(new Provider\ControllerServiceProvider(), []);
		$this->register(new Provider\RouterServiceProvider(), []);
		$this->register(new Provider\ViewServiceProvider(), []);
		$this->register(new Provider\MailServiceProvider(), []);
		$this->register(new Provider\ExtensionServiceProvider(), []);
		$this->register(new Provider\LocaleServiceProvider(), []);

		$this['event']->on('Blocky::BootsrapFinished', [$this, 'onBootstrapFinished']);
		$this['event']->on('Blocky::ApplicationInitialized', [$this, 'onApplicationInitialized']);

		//@deprecated
		//$this['event']->on('Bootstrap::CoreLoaded', [$this, 'onCoreLoaded']);
	}

	/**
	 * This method, loads all extension specified in config.yml's extensions key, after this, it emmits an event 'onRequest'.
	 * Blocky\Router\RouterService has a listener for onRequest
	 *
	 * @return void
	 * @author 
	 **/
	public function onApplicationInitialized()
	{
		$extensions = $this['config']['extensions'];
		$classes = [];
		foreach($extensions as $ext) {
			$e = new $ext();
			$this['extension']->install( $e );
			$classes[] = $e;
		}

		foreach($classes as $e) {
			$this['extension']->register( $e );
		}
		unset($extensions);
		unset($classes);

		putenv('LANGUAGE='.$this['locale']['locale']);
		putenv('LANG='.$this['locale']['locale']);
		putenv('LC_ALL='.$this['locale']['locale']);

        $locale = array_merge([], array(
            $this['locale']['locale'] . '.UTF-8',
            $this['locale']['locale'] . '.utf8',
            $this['locale']['locale'],
            $this['config']['fallback_locale'] . '.UTF-8',
            $this['config']['fallback_locale'] . '.utf8',
            $this['config']['fallback_locale'],
            substr($this['config']['fallback_locale'], 0, 2)
        ));
        setlocale(LC_ALL, array_unique($locale));

		// Specify location of translation tables
		bindtextdomain("blocky", $this['path']['translations']);

		// Choose domain
		textdomain("blocky");

		$themeConfig = YamlWrapper::parse($this['path']['theme']."/config.yml");

		if(array_key_exists('assets', $themeConfig)) {
			if(array_key_exists('js', $themeConfig['assets'])) {
				foreach($themeConfig['assets']['js'] as $js) {
					$this['view']->addAsset('js', $js, 100);
				}
			}
			if(array_key_exists('css', $themeConfig['assets'])) {
				foreach($themeConfig['assets']['css'] as $css) {
					$this['view']->addAsset('style', $css, 100);
				}
			}
		}
		$this['event']->trigger("Blocky::onRequest");
	}

	/**
	 * This method fires up the config service and router service by calling it, and registering default routes, provided in routes.yml
	 *
	 * @return void
	 * @author 
	 **/
	public function onBootstrapFinished()
	{
		$routes = $this['config']['routes'];
		foreach($routes as $name => $data) {
			$this['router']->registerRoute($name, $data['path'], $data);
		}
		$this['event']->trigger("Blocky::RoutesRegistered");

		unset($routes);

		$this['event']->trigger("Blocky::ApplicationInitialized");
	}

	/**
	 * This function is no longer used!
	 * @deprecated 
	 * @return void
	 * @author 
	 **/
	public function onCoreLoaded()
	{
		//@deprectaed
		$routes = $this['config']['routes'];
		foreach($routes as $name => $data) {
			$this['router']->registerRoute($name, $data['path'], $data);
		}

		$extensions = $this['config']['extensions'];
		foreach($extensions as $ext) {
			$this['extension']->register( new $ext() );
		}
		
		unset($extensions);
		unset($routes);

		$this['event']->trigger("Bootstrap::Finished");
	}
} // END class Application