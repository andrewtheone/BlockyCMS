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
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public static $app;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	static function parse($path)
	{
		return self::$app['cache']->get($path, function() use(&$path) {
			$content = @yaml_parse_file($path);
			if(!$content) {

				if((strpos($path, "contenttypes.yml") !== false) || (strpos($path, "routes.yml") !== false) ) {
			die("Config fájl üresen jött vissza.. ". $path);
		}

			}
			return ($content)?$content:[];
			//return @yaml_parse_file($path);
		}, 60);

		/*$content = @yaml_parse_file($path);

		return ($content)?$content:[];*/
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