<?php

namespace Blocky\Provider;

use Blocky\Extension\ExtensionService;

/**
 * Extension service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class ExtensionServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['extension'] = function(&$c) {
			$service = new ExtensionService($c);
			$service->boot();
			return $service;
		};
	
	}
} // END class ExtensionServiceProvider