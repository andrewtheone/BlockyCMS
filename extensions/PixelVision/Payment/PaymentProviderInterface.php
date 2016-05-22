<?php

namespace PixelVision\Payment;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
interface PaymentProviderInterface
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot(&$app, $options = []);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName();

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function start($details);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function onResponse($response);
} // END interface PaymentProviderInterface