<?php

namespace Blocky\Cache\Twig;

use Asm89\Twig\CacheExtension\CacheProviderInterface;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class CacheProvider implements CacheProviderInterface
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
	public $strategy;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($app, $strategy = 'default')
	{
		$this->app = $app;
		$this->strategy = $strategy;
	}

    /**
     * {@inheritDoc}
     */
    public function fetch($key)
    {
    	list($strategy, $key) = $this->extract($key);

        return $this->app['cache']->fetch($key, $strategy);
    }

    /**
     * {@inheritDoc}
     */
    public function save($key, $value, $lifetime = 0)
    {
    	list($strategy, $key) = $this->extract($key);

        return $this->app['cache']->get($key, function() use($value) {
        	return $value;
        }, $lifetime, $strategy);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function extract($key)
    {
    	$key = str_replace("__LCS__", "", $key);

    	if(strpos($key, "|") !== FALSE) {
    		return explode("|", $key);
    	}

    	return [$this->strategy, $key];
    }
} // END class CacheProvider