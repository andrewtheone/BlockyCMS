<?php

namespace Blocky;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BaseService
{

	/**
	 * Reference to Blocky\Application
	 *
	 * @var Blocky\Application
	 **/
	var $app;

	/**
	 * Sets reference to application
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(&$app)
	{
		$this->app = $app;
	}

	/**
	 * This method is called when the services is called for the first time!
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
	}
} // END class BaseService