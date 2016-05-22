<?php

namespace PixelVision\Payment;


/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class TransactionDetails
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
	public $content;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(&$app, &$content)
	{
		$this->app = $app;
		$this->content = $content;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTransactionID()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTransactionNr()
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
	public function getTotal()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getProvider()
	{
		return $this->content->getValue('provider');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getProviderHandler()
	{
		$class = $this->app['payment']->getProviderByName($this->getProvider());
		return $class;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function start()
	{
		$handler = $this->getProviderHandler();
		return $handler->start($this);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function cancel()
	{
		$this->content->fromArray([
			'status' => 3
		]);

		$this->app['payment']->storeTransaction($this);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function callCallback($params = [])
	{
	}
} // END class BasePaymentProvider