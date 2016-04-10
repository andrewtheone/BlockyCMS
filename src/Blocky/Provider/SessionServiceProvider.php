<?php

namespace Blocky\Provider;

use Blocky\Storage\SessionService;

/**
 * Storage service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class SessionServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['session'] = function(&$c) {
			$service = new SessionService($c);
			$service->boot();
			return $service;
		};
	
	}
} // END class SessionServiceProvider