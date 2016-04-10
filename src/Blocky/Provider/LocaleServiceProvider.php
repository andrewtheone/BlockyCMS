<?php

namespace Blocky\Provider;

use Blocky\Locale\LocaleService;

/**
 * Storage service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class LocaleServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['locale'] = function(&$c) {
			$service = new LocaleService($c);
			$service->boot();
			return $service;
		};
	
	}
} // END class SessionServiceProvider