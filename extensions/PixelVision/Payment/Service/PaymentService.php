<?php

namespace PixelVision\Payment\Service;

use Blocky\BaseService;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class PaymentService extends BaseService
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->config = YamlWrapper::parse( $this->app['path']->to('config', 'payment.yml') );
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function findTransaction($transaction_nr)
	{
		$contents = $this->app['content']->getContents("transactions", "where transaction = ?", [$transaction_nr]);
		
		if(count($contents) == 0)
			return null;

		$content = $contents[0];

		$details = new TransactionDetails($this->app, $content);
		return $details;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function storeTransaction(&$details)
	{
		$this->app['content']->storeContent($details->content);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function createTransaction($provider, $cart = [], $callback = null)
	{
		$ct = $this->app['content']->createContent("transactions");
		
		$data = [
			'cart' => $cart,
			'callback' => $callback
		];

		$ct->fromArray([
			'provider' => $provider,
			'status' => 0,
			'data' => json_encode($data)
		]);

		$details = new TransactionDetails($this->app, $ct);

		$this->storeTransaction($details);

		return $details;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function &getProviderByName($provider)
	{
		$details = $this->config['providers'][$provider];

		$class = new $details['handler']();
		$class->boot($this->app, $details);
		
		return $class;
	}
} // END class PaymentService extends BaseService