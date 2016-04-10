<?php

namespace Blocky\Path;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class PathService extends BaseService implements \ArrayAccess
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
		
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function to($parent, $file)
	{
		return $this->values[$parent]."/".$file;
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