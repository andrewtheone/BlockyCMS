<?php

namespace Blocky\Content;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ContentList implements \ArrayAccess, \Iterator, \Countable
{

    private $values = array();

    public $contentTypeSlug;
    public $where;
    public $args;

    public $recordSize = null;

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function __construct($contentTypeSlug, $list, $where = '', $args = [])
    {
        $this->values = $list;
        $this->where = $where;
        $this->args = $args;
        $this->contentTypeSlug = $contentTypeSlug;
        $this->recordSize = count($list);
    }

    public function count()
    {
        return $this->recordSize;
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
        unset($this->values[$id]);
    }

    public function rewind()
    {
        reset($this->values);
    }
  
    public function current()
    {
        $var = current($this->values);
        return $var;
    }
  
    public function key() 
    {
        $var = key($this->values);
        return $var;
    }
  
    public function next() 
    {
        $var = next($this->values);
        return $var;
    }
  
    public function valid()
    {
        $key = key($this->values);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getWhereArgs()
    {
        return $this->args;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getContentTypeSlug()
    {
        return $this->contentTypeSlug;
    }

} // END class Service