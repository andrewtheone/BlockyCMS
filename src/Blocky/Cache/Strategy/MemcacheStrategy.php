<?php

namespace Blocky\Cache\Strategy;

use Blocky\Cache\StrategyInterface;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class MemcacheStrategy implements StrategyInterface
{



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
		return "memcache";
	}
} // END class ApcStrategy	