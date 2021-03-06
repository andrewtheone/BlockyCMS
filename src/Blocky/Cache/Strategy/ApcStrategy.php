<?php

namespace Blocky\Cache\Strategy;

use Blocky\Cache\StrategyInterface;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ApcStrategy implements StrategyInterface
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $apcNamespace;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($namespace = 'blocky:app:')
	{
		$this->apcNamespace = $namespace;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function get($key, $provider, $ttl)
	{
		if(!$this->exists($key)) {
			apc_store($this->apcNamespace.$key, call_user_func_array($provider, []), $ttl);
		}

		return apc_fetch($this->apcNamespace.$key);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function exists($key)
	{
		return apc_exists($this->apcNamespace.$key);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function fetch($key)
	{
		if($this->exists($key))
			return apc_fetch($this->apcNamespace.$key);

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
		return "apc";
	}
} // END class ApcStrategy	