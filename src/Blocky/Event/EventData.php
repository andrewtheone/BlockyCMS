<?php

namespace Blocky\Event;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class EventData implements \ArrayAccess
{

    private $values = array();


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

} // END class EventData