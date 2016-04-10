<?php

namespace Blocky\Provider;

use Blocky\View\ViewService;

/**
 * View service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class ViewServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['view'] = function(&$c) {
			$service = new ViewService($c);
			$service->boot();
			return $service;
		};

		//$this->app['event']->on('Bootstrap::Finished', [$this->app['view'], 'onCoreLoaded'], 400);
	}
} // END class ViewServiceProvider