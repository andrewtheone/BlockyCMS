<?php

namespace Blocky\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Base service provider
 *
 * @package Blocky\Provider
 * @author 
 **/
class BaseServiceProvider implements ServiceProviderInterface
{

	/**
	 * App reference
	 *
	 * @var Blocky\Application
	 **/
	var $app;

	/**
	 * Connect current service to pimple container
	 *
	 * @return void
	 * @author Daniel Simon
	 **/
	final public function register(Container $app)
	{
		$this->app = $app;

		$this->boot();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
	}
	
} // END class BaseServiceProvider