<?php

namespace Blocky\Extension;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SimpleExtension
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
	public function register(&$app)
	{
		$this->app = $app;
		$this->boot();
	}

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
	public function install()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function addAsset($type, $path, $priority = 200)
	{
		$extension = str_replace(['::', 'Extension'], ['/', ''], $this->getName());
		$this->app['view']->addAsset($type, "@extensions/".str_replace("@this", $extension, $path), $priority);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getConfig($config)
	{
		if(!file_exists($this->app['path']['config']."/".$config)) {
			$extension = str_replace(['::', 'Extension'], ['/', ''], $this->getName());
			copy($this->app['path']['extensions']."/".$extension."/config/".$config, $this->app['path']['config']."/".$config);
		} else {
			if(!$this->isInstalled()) {
				$_content = yaml_parse_file($this->app['path']['config']."/".$config);
				$_bare = yaml_parse_file($this->app['path']['extensions']."/".$extension."/config/".$config);
				$_content = array_merge([], $_content, $_bar);
				file_put_contents($this->app['path']['config']."/".$config, yaml_emit($_content));
			}
		}

		return yaml_parse_file($this->app['path']['config']."/".$config);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function extendConfig($config, $extension = [])
	{
		$_config = [];

		if(file_exists($this->app['path']['config']."/".$config)) {
			$_config = yaml_parse_file($this->app['path']['config']."/".$config);
		}

		$_config = array_merge([], $_config, $extension);
		$_config = yaml_emit($_config);
		file_put_contents($this->app['path']['config']."/".$config, $_config);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function isInstalled()
	{
		return in_array($this->getName(), $this->app['config']['installed']);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getViewNamespaces()
	{
		return [];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "SimpleExtension";
	}
} // END class 