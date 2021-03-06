<?php

namespace Blocky\Router;

use Blocky\BaseService;
use Aura\Router\RouterContainer;
use Blocky\Event\EventData;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class RouterService extends BaseService
{

	/**
	 * undocumented class variable
	 *
	 * @var Aura\Router\RouterContainer
	 **/
	public $router;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $map;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $request;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $route;

	/**
	 * @inherit
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->router = new RouterContainer();
		$this->map = $this->router->getMap();
		$this->request = new Request($this->app["config"]);

		$path = $this->request->getUri()->getPath();
		if( strpos($path, "/admin") === 0 ) {
			$this->app['site'] = 'backend';
		} else {
			$themeConfig = YamlWrapper::parse($this->app['path']['theme']."/config.yml");

			if(array_key_exists('assets', $themeConfig)) {
				if(array_key_exists('js', $themeConfig['assets'])) {
					foreach($themeConfig['assets']['js'] as $js) {
						$priority = 100;
						$path = $js;
						if(strpos($path, "|") !== false) {
							$parts = explode("|", $path);
							$priority = $parts[0];
							$path = $parts[1];
						}
						$this->app['view']->addAsset('js', $path, $priority);
					}
				}
				if(array_key_exists('css', $themeConfig['assets'])) {
					foreach($themeConfig['assets']['css'] as $css) {
						$priority = 100;
						$path = $css;
						if(strpos($path, "|") !== false) {
							$parts = explode("|", $path);
							$priority = $parts[0];
							$path = $parts[1];
						}
						$this->app['view']->addAsset('style', $path, $priority);
					}
				}
			}
		}

		$this->app['event']->on('Blocky::onRequest', [$this, 'onRequest']);
		
		//@deprected
		//$this->app['event']->on('Bootstrap::Finished', [$this, 'onBootstrapFinished']);
	}

	/**
	 * Registers a route
	 *
	 * @return void
	 * @author 
	 **/
	public function registerRoute($name, $path, $data)
	{
		$routeData = new Route($data);
		$defaults = (array_key_exists('defaults', $data)?$data['defaults']:[]);
		$tokens = (array_key_exists('tokens', $data)?$data['tokens']:[]);
		$this->map->route($name, $path, $routeData)->allows('GET')->allows('POST')->tokens($tokens)->defaults($defaults);
	}

	/**
	 * This is an event listener. It is fired after extensions are loaded.
	 *
	 * @return 	
	 * @author 	
	 **/
	public function onRequest()
	{
		$this->route = $this->router->getMatcher()->match($this->request);
		$routeData = null;
		if($this->route) {
			$routeData = $this->route->handler;
		} else {
			$routeData = $this->map->getRoute('not_found')->handler;
		}

		$eventData = new EventData();
		$eventData['routeData'] = $routeData;
		if($this->route) {
			$eventData['routeData']['attributes'] = $this->route->attributes;
		}
		$this->app['event']->trigger('Blocky::onRouteFound', $eventData);
	}

} // END class Service