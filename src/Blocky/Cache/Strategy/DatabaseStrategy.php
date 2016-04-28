<?php

namespace Blocky\Cache\Strategy;

use Blocky\Cache\StrategyInterface;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class DatabaseStrategy implements StrategyInterface
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($databaseOptions = [])
	{
		/*
			todo: if database options present, use them to connect to db, and fetch data through that connection, otherviews, this strategy should obtaion a pointer to $app
		*/
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function get($key, $provider, $ttl)
	{
		return call_user_func_array($provider, []);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function exists($key)
	{
		return false;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function fetch($key)
	{
		return false;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "database";
	}
} // END class ApcStrategy	