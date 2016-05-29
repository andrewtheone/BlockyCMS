<?php

namespace PixelVision\ECommerce;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\ServiceProvider;
use Blocky\Extension\FieldTypeProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ECommerceExtension extends SimpleExtension implements ServiceProvider, FieldTypeProvider
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

		if($this->app['site'] == "backend") {
			$this->addAsset('js', '@this/assets/js/backend.js', 700);
		}

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
	public function getServices()
	{
		return [
			'ecommerce' => function($app) {
				$e = new Service\ECommerceService($app);
				$e->boot();

				return $e;
			},
			'ecommerce.cart' => function($app) {
				$e = new Service\ECommerceCartService($app);
				$e->boot();

				return $e;
			},
			'ecommerce.checkout' => function($app) {
				$e = new Service\ECommerceCheckoutService($app);
				$e->boot();

				return $e;
			},
			'ecommerce.voucher' => function($app) {
				$e = new Service\ECommerceVoucherService($app);
				$e->boot();

				return $e;
			}
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFieldTypes()
	{
		return [
			new FieldType\Properties()
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