<?php

namespace Blocky\Provider;

use Blocky\Config\ConfigService;

/**
 * Config service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class ConfigServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['config'] = function(&$c) {
			$service = new ConfigService($c);
			$service->boot();
			return $service;
		};
	
	}
} // END class ConfigServiceProvider