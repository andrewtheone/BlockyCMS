<?php

namespace Blocky\Feed\Feeder;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SimpleFeeder
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $app;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getItems($feed, $contenttype)
	{
		$contentList = $this->app['content']->getContents($contenttype, "LIMIT 0, 9999", []);
		$items = [];
		foreach($contentList as $i) {
			$items[] = $i->toArray();
		}

		return $items;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function renderXml($items, $options)
	{
		$xml = new \SimpleXMLElement('<'.$options['root'].'/>');
		self::data2XML($items, $xml, $options['item']);

		echo $xml->asXML();
		die();
	}


	public static function data2XML(array $data, \SimpleXMLElement $xml, $child = "items")
	{

        foreach($data as $key => $val) {
            if(is_array($val)) {

                if(is_numeric($key)) {
                	$key = $child;
                    $node  = $xml->addChild($child);
                    $nodes = $node->getName($child);
                } else {

                    $node  = $xml->addChild($key);
                    $nodes = $node->getName($key);
                }

                $node->addChild($nodes, self::data2XML($val, $node));
            } else {
            	if($val) {
            		if(is_string($val)) {
            			$val = "<![CDATA[".htmlentities($val)."]]>";
            		}
            		if(is_numeric($key)) {
            			$xml->addChild($child, $val);
            		} else {
                		$xml->addChild($key, $val);
            		}
            	}
            }
        }

	}

} // END class SimpleFeeder