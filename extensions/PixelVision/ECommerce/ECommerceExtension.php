<?php

namespace Blocky\ECommerce;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\ServiceProvider;


/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ECommerceExtension extends SimpleExtension implements ServiceProvider
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
		$this->extendConfig("ecommerce.yml", "ecommerce.yml");
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

		$this->app['event']->on("Newsletter::Subscribed", function() {
			// if session's cart is not associated with an email, then associate it
			// for further reminders
		});

		$this->app['event']->on("Members::LoggedIn", function() {
			// same as before
		});
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
			'ecommerce' => __DIR__."/views"
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
		return "PixelVision::ECommerceExtension";
	}
} // END class ECommerceExtension