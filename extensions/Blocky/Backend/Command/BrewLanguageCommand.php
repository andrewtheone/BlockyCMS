<?php

namespace Blocky\Backend\Command;

use Blocky\CLI\SimpleCommand;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BrewLanguageCommand extends SimpleCommand
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function exec($params)
	{
		$dirs = $this->getExtensionLocalisationDirs();
		// first we use each localisations dir's proto.yml to fill any other languge.yml keys 
		foreach($dirs as $dir) {
			if(file_exists($dir."/proto.yml")) {
				$proto = YamlWrapper::parse($dir."/proto.yml");

				$otherLanguagePaths = glob($dir."/*.yml");
				foreach($otherLanguagePaths as $path) {
					$file = str_replace($dir."/", "", $path);

					if($file != "proto.yml") {

						$langugeConfig = YamlWrapper::parse($dir."/".$file);

						foreach($proto as $k => $v) {
							if(!array_key_exists($k, $langugeConfig)) {
								$langugeConfig[$k] = $v;
							}
						}

						YamlWrapper::emit($dir."/".$file, $langugeConfig);
					}
				}
			}
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getExtensionLocalisationDirs()
	{
		$paths = [];

		$objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->app['path']['extensions']), \RecursiveIteratorIterator::SELF_FIRST);
		foreach($objects as $name => $object){
			$name = str_replace(["/", "\\"], "/", $name);
		    $parts = explode("/", $name);

		    if($parts[count($parts)-1] == "localisations")
		    	$paths[] = $name;
		}

		return $paths;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "BrewLanguage";
	}
} // END class BrewLangaugeCommand