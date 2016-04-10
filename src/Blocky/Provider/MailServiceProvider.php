<?php

namespace Blocky\Provider;

use Blocky\Mail\MailService;

/**
 * Router service
 *
 * @package Blocky\Provider
 * @author Daniel Simon
 **/
class MailServiceProvider extends BaseServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{

		$this->app['mail'] = function(&$c) {
			$service = new MailService($c);
			$service->boot();
			return $service;
		};
	}
} // END class RouterServiceProvider