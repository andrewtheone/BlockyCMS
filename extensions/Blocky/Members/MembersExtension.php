<?php

namespace Blocky\Members;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\ServiceProvider;
use Blocky\Extension\BackendRouteProvider;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\ContentTypeProvider;
use Blocky\Extension\BackendMenuItemProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class MembersExtension extends SimpleExtension  implements ServiceProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function install()
	{
		$this->extendConfig("contenttypes.yml", "contenttypes.yml");
		$this->extendConfig("forms.yml", "forms.yml");
		$this->extendConfig("members.yml", "members.yml");
		$this->extendConfig("routes.yml", "routes.yml");
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
	public function getServices()
	{
		return [
			'members' => function($app) {
				$members = new Service\MembersService($app);
				$members->boot();
				return $members;
			}
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
			'members' => __DIR__."/views"
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
		return "Blocky::MembersExtension";
	}
} // END class TestExtension