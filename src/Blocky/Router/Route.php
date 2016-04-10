<?php

namespace Blocky\Router;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Route implements \ArrayAccess
{

    private $values = array();

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function __construct($initial)
    {
    	$this->values = $initial;
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

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getName()
    {
        return $this->values['name'];
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getAttribute($key, $default = '')
    {
        if(!array_key_exists('attributes', $this->values))
            return null;
        return (array_key_exists($key, $this->values['attributes'])?$this->values['attributes'][$key]:$default);
    }
}