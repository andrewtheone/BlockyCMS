<?php

namespace Blocky\Extension;

use Blocky\Config\YamlWrapper;

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
	public function extendConfig($config, $data = [])
	{
		if(!is_array($data)) {
			$extension = str_replace(['::', 'Extension'], ['/', ''], $this->getName());
			$srcConfig = $this->app['path']['extensions']."/".$extension."/config/".$data;
			$data = YamlWrapper::parse($srcConfig);
		}

		$destConfig = [];
		if(file_exists($this->app['path']['config']."/".$config)) {
			$destConfig = YamlWrapper::parse($this->app['path']['config']."/".$config);
		}
		/*if($this->getName() == "Blocky::SetupExtension") {
			if($config == "routes.yml") {
				print_r($destConfig);
			}
		}*/
		$destConfig = array_merge([], $destConfig, $data);

		/*if($this->getName() == "Blocky::SetupExtension") {
			if($config == "routes.yml") {
				print_r($destConfig);
				print_r($data);
				die();
			}
		}*/

		YamlWrapper::emit($this->app['path']['config']."/".$config, $destConfig);
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
				$_content = YamlWrapper::parse($this->app['path']['config']."/".$config);
				$_bare = YamlWrapper::parse($this->app['path']['extensions']."/".$extension."/config/".$config);
				$_content = array_merge([], $_content, $_bar);
				YamlWrapper::emit($this->app['path']['config']."/".$config, $_content);
			}
		}

		return YamlWrapper::parse($this->app['path']['config']."/".$config);
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