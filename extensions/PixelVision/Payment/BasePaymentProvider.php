<?php

namespace PixelVision\Payment;


/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BasePaymentProvider implements PaymentProviderInterface
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $app;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $options;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot(&$app, $options = []) {
		$this->app = $app;
		$this->options = $options;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName() {
		return null;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setupTransaction() {}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function onResponse($response) {}
} // END class BasePaymentProvider