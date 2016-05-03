<?php

namespace PixelVision\ECommerce\Service;

use Blocky\BaseService;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ECommerceService extends BaseService
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->config = YamlWrapper::parse( $this->app['path']->to('config', 'ecommerce.yml') );
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getCheckoutOptions()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getCart()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getGlobalVouchers()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getCouponVouchers()
	{
	}
} // END class Service