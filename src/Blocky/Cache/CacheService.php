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
    public $strategies;

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
    public function addStrategy($strategy)
    {
        $this->strategies[$strategy->getName()] = $strategy;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function get($key, $provider, $ttl, $strategy = 'default')
    {
        $strategy = $this->getStrategy($strategy);
        return $this->strategies[$strategy]->get($key, $provider, $ttl);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function fetch($key, $strategy = 'default')
    {
        $strategy = $this->getStrategy($strategy);
        return $this->strategies[$strategy]->fetch($key);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function exists($key, $strategy = 'default')
    {
        $strategy = $this->getStrategy($strategy);
        return $this->strategies[$strategy]->exists($key);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getStrategy($strategy)
    {
        if($strategy == 'default' && (!array_key_exists($strategy, $this->strategies))) {
            $keys = array_keys($this->strategies);
            return $keys[0];
        }

        return $strategy;
    }
} // END class Service