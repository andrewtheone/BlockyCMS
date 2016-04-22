<?php

namespace Blocky\Cache;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class CacheService extends BaseService
{

    /**
     * undocumented class variable
     *
     * @var string
     **/
    public $strategy;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
        parent::boot();
	}

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function get($key, $provider, $ttl)
    {
        return $this->strategy->get($key, $provider, $ttl);
    }

} // END class Service