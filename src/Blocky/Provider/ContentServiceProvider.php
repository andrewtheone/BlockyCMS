<?php

namespace Blocky\Provider;

use Blocky\Content\ContentService;

/**
 * Content service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class ContentServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['content'] = function(&$c) {
			$service = new ContentService($c);
			$service->boot();
			return $service;
		};
	}
} // END class ContentServiceProvider