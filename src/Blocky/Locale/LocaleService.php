<?php

namespace Blocky\Locale;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class LocaleService extends BaseService implements \ArrayAccess
{
    private $values = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->values['locale'] = $this->app['session']->get('locale', $this->app['config']['default_locale']);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function set($locale)
	{
		$this->values['locale'] = $locale;
		$this->app['session']['locale'] = $locale;
	}

    public function offsetSet($id, $value)
    {
    	$this->values[$id] = $value;
    }

    public function offsetGet($id)
    {
    	return (array_key_exists($id, $this->values)?$this->values[$id]:null);
    }


    public function offsetExists($id)
    {
        return array_key_exists($id, $this->values);
    }

    public function offsetUnset($id)
    {
    }
} // END class Service