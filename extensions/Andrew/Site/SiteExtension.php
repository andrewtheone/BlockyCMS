<?php

namespace Andrew\Site;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\ServiceProvider;
use Blocky\Extension\FieldTypeProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SiteExtension extends SimpleExtension implements ServiceProvider
{


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function install()
	{
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
		parent::boot();
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
			'site' => __DIR__."/views"
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
		return "Andrew::SiteExtension";
	}
} // END class ECommerceExtension