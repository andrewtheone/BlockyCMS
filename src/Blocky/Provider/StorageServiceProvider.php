<?php

namespace Blocky\Provider;

use Blocky\Storage\StorageService;

/**
 * Storage service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class StorageServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['storage'] = function(&$c) {
			$service = new StorageService($c);
			$service->boot();
			return $service;
		};
	
	}
} // END class StorageServiceProvider