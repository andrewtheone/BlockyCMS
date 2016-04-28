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
	 * If a certain key does not exists or its ttl is out, then calls dataProvider, otherwise returns stored content
	 *
	 * @return void
	 * @author 
	 **/
	public function get($key, $dateProvider, $ttl);

	/**
	 * Returns true if a certion key exists otherwise false
	 *
	 * @return void
	 * @author 
	 **/
	public function exists($key);

	/**
	 * Returns the content stored under a certion key, if does not exits then returns false
	 *
	 * @return void
	 * @author 
	 **/
	public function fetch($key);
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName();
} // END interface StrategyInterface