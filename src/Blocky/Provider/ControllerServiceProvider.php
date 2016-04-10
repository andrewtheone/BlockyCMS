<?php

namespace Blocky\Provider;

use Blocky\Controller\ControllerService;

/**
 * Controller service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class ControllerServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['controller'] = function(&$c) {
			$service = new ControllerService($c);
			$service->boot();
			return $service;
		};

		$this->app['event']->on('Blocky::onRouteFound', [$this->app['controller'], 'onRouteFound']);
	}
} // END class ControllerServiceProvider