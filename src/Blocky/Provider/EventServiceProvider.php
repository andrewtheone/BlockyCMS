<?php

namespace Blocky\Provider;

use Blocky\Event\EventService;

/**
 * Event service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class EventServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['event'] = function(&$c) {
			$service = new EventService($c);
			$service->boot();
			return $service;
		};
	
	}
} // END class EventServiceProvider