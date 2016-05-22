<?php

namespace PixelVision\Payment\Provider;

use PixelVision\Payment\BasePaymentProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BorgunProvider extends BasePaymentProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "borgun";
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function start($details)
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function onResponse($request)
	{
		if($request->getAttribute('orderid', false)) {
			$transactionDetails = $this->app['payment']->findTransaction($request->getAttribute('orderid'));

			die("Servicing ".$this->getName()." payment ipn, transaction-id:".$request->getAttribute('orderid'));
		}
	}
} // END class BorgunProvider 