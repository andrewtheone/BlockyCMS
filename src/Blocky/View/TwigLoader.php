<?php

namespace Blocky\View;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class TwigLoader extends \Twig_Loader_Filesystem
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
	public function setApp(&$app)
	{
		$this->app = $app;;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function findTemplate($name)
	{
		$isForced = false;

		if($name[0] == '!') {
			$isForced = true;
			$name = substr($name, 1);
		}

        $name = $this->normalizeName($name);

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $this->validateName($name);

        list($namespace, $shortname) = $this->parseName($name);

        if (!isset($this->paths[$namespace])) {
            throw new \Twig_Error_Loader(sprintf('There are no registered paths for namespace "%s".', $namespace));
        }
        
        $deviceDirectories = [];


        if($this->app['config']->isTablet() && !$isForced) {
        	$deviceDirectories[] = '/tablet';
        	$deviceDirectories[] = '/mobile';
        } else 
        if($this->app['config']->isMobile() && !$isForced) {
        	$deviceDirectories[] = '/mobile';
        	$deviceDirectories[] = '/tablet';
        }

        $deviceDirectories[] = '';

        foreach($deviceDirectories as $dDir) {
	        foreach ($this->paths[$namespace] as $path) {
	            if (is_file($path.$dDir.'/'.$shortname)) {
	                return $this->cache[$name] = $path.$dDir.'/'.$shortname;
	            }
	        }
        }


        throw new \Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths[$namespace])));
	}
} // END class TwigLoader extends \Twig_Loader_Filesystem