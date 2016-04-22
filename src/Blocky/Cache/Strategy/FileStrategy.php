<?php

namespace Blocky\Cache\Strategy;

use Blocky\Cache\StrategyInterface;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FileStrategy implements StrategyInterface
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $cacheFile;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $cacheData;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($cacheFile)
	{
		$this->cacheFile = $cacheFile;
		$this->cacheData = (file_exists($cacheFile)?unserialize(file_get_contents($cacheFile)):[]);
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
			$this->cacheData[$key] = [
				'addedAt' => time(),
				'ttl' => $ttl,
				'payload' => call_user_func_array($provider, [])
			];

			file_put_contents($this->cacheFile, serialize($this->cacheData));
		}

		return $this->cacheData[$key]['payload'];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function exists($key)
	{
		if(!array_key_exists($key, $this->cacheData))
			return false;

		if( ($this->cacheData[$key]['addedAt']+$this->cacheData[$key]['ttl']) >= time()) {
			return false;
		}

		return true;
	}

} // END class ApcStrategy	