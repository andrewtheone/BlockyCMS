<?php

namespace Blocky\Seo\Provider;

use Blocky\Seo\Service\MetaService;
use Blocky\Provider\BaseServiceProvider;

/**
 * Config service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class MetaServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['meta'] = function(&$c) {
			$service = new MetaService($c);
			$service->boot();
			return $service;
		};
	
	}
} // END class ConfigServiceProvider