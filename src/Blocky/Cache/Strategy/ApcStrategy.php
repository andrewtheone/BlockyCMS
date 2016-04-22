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
		if(!apc_exists($this->apcNamespace.$key)) {
			apc_store($this->apcNamespace.$key, call_user_func_array($provider, []), $ttl);
		}

		return apc_fetch($this->apcNamespace.$key);
	}

} // END class ApcStrategy	