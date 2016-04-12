<?php

namespace Blocky\Setup;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\BackendRouteProvider;
use Blocky\Extension\ContentTypeProvider;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SetupExtension extends SimpleExtension implements BackendRouteProvider, ContentTypeProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		parent::boot();

		$self = $this;
		$this->app['event']->on("Setup::Success", function() use(&$self) {

			$config = YamlWrapper::parse($self->app['path']['root']."/app/config/config.yml");
			$extensions = [];
			
			foreach($config['extensions'] as $ex) {
				if($ex != "Blocky\Setup\SetupExtension")
					$extensions[] = $ex;
			}

			$config['extensions'] = $extensions;
			YamlWrapper::emit($self->app['path']['root']."/app/config/config.yml", $config);

			$self->app['path']->redirect("/admin");
		});
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function install()
	{
		$this->extendConfig("forms.yml", "forms.yml");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContentTypes()
	{
		return [
			"setupadmin" => [
				"name" => "Setupadmin",
				"singular_name" => "Setupadmin",
				"show_menu" => false,
				"fields" => [
					"username" => [
						"label" => "Felhasználónév",
						"type" => "text"
					],
					"password" => [
						"label" => "Jelszó",
						"type" => "password"
					]
				]
			]
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getBackendRoutes()
	{
		return [
			[
				'name' => 'backend_setup',
				'path' => '/admin/setup',
				'handler' => ['_controller' => 'Blocky\Setup\Controller\SetupController', '_action' => 'setup']
			]
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getViewNamespaces()
	{
		return [
			'setup' => __DIR__."/views"
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "Blocky::SetupExtension";
	}
} // END class SetupExtension