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
	public function getCheckoutOptionsList()
	{
		return [[
			'id' => 1,
			'title' => 'teszt 1'
		]];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getCart()
	{
		/*
			it retrieves ecommerce.cart->getItems and ecommerce.cart.getVoucher
			it retrieves global vouchers

			it adds voucher discounts to the proper items as 'extra_accessories'
			'extra accessories key is not saved to the cart, because coupons and vouchers can be timed, so those should always be recalculated

		*/
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTotal()
	{
		/*
			it retrieves ecommerce.getCart() and iterates through it, returns SUM(item.quantity*(item.price+SUM(item.accessories.price)+SUM(item.extra_accesories.price))
		*/
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getActiveVouchers()
	{
		/* todo: return cart voucher, and active global vouchers */
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