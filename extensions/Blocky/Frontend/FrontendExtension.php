<?php

namespace Blocky\Frontend;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\FrontendRouteProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FrontendExtension extends SimpleExtension implements FrontendRouteProvider
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
	}
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFrontendRoutes()
	{
		return [
			[
				'name' => 'frontend_recordview',
				'path' => '/{contenttype}/{slug}',
				'handler' => ['_controller' => 'Blocky\Frontend\Controller\FrontendController', '_action' => 'recordView'],
				'tokens' => ['contenttype' => '[a-z]*', 'slug' => '[a-z\-]*']
			]
		];
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
	public function getName()
	{
		return "Blocky::FrontendExtension";
	}
} // END class FrontendExtension