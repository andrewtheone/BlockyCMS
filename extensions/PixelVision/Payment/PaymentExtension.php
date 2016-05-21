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
class PaymentExtension extends SimpleExtension implements ServiceProvider, FieldTypeProvider
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
		$this->extendConfig("payment.yml", "payment.yml");
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
	public function getFieldTypes()
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
			'payment' => __DIR__."/views"
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
		return "PixelVision::PaymentExtension";
	}
} // END class ECommerceExtension