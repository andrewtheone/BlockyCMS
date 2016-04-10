<?php

namespace Blocky\Provider;

use Blocky\Path\PathService;

/**
 * Path service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class PathServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['path'] = function(&$c) {
			$service = new PathService($c);
			$service->boot();
			return $service;
		};
	
	}
} // END class PathServiceProvider