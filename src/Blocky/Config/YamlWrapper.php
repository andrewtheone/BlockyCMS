<?php

namespace Blocky\Config;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class YamlWrapper
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	static function parse($path)
	{
		return yaml_parse_file($path);
	}

	/**
	 * undocumented function
	 *
	 * @param string path
	 * @param array $content
	 * @return void
	 * @author 
	 **/
	static function emit($path, $content)
	{
		file_put_contents($path, yaml_emit($content));
	}
} // END class YamlWrapper