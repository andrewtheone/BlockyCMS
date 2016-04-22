<?php

namespace Blocky\Cache;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
interface StrategyInterface
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function get($key, $dateProvider, $ttl);
	
} // END interface StrategyInterface