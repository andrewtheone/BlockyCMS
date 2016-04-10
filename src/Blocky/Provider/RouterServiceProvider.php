<?php

namespace Blocky\Provider;

use Blocky\Router\RouterService;

/**
 * Router service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class RouterServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['router'] = function(&$c) {
			$service = new RouterService($c);
			$service->boot();
			return $service;
		};
	}
} // END class RouterServiceProvider