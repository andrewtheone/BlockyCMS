<?php

namespace PixelVision\Payment;


/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ResponseView
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $template;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $data;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $redirect;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($template, $data = [], $redirect = null)
	{
		$this->template = $template;
		$this->data = $data;
		$this->redirect = $redirect;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getData()
	{
		return $this->data;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getRedirect()
	{
		return $this->redirect;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function isRedirect()
	{
		return ($this->redirect != null);
	}
} // END class BasePaymentProvider