<?php

namespace Blocky\CLI;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SimpleCommand
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $app;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $cli;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function attach(&$app, &$cli)
	{
		$this->app = $app;
		$this->cli = $cli;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "command";
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function exec($params)
	{
	}
} // END class SimpleCommand