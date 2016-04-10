<?php

namespace Blocky\Router;

use Blocky\BaseService;
use Aura\Router\RouterContainer;
use Blocky\Event\EventData;

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
	 * @inherit
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->router = new RouterContainer();
		$this->map = $this->router->getMap();
		$this->request = new Request();

		$path = $this->request->getUri()->getPath();
		if( $this->app['config']['subfolder'] && (($length = strlen($this->app['config']['subfolder'])) > 0) ) {
			$path = substr($path, $length);
		}
		if( strpos($path, "/admin") === 0 ) {
			$this->app['site'] = 'backend';
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
		$route = $this->router->getMatcher()->match($this->request);
		$routeData = null;
		if($route) {
			$routeData = $route->handler;
		} else {
			$routeData = $this->map->getRoute('not_found')->handler;
		}

		$eventData = new EventData();
		$eventData['routeData'] = $routeData;
		if($route) {
			$eventData['routeData']['attributes'] = $route->attributes;
		}
		$this->app['event']->trigger('Blocky::onRouteFound', $eventData);
	}

} // END class Service