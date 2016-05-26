<?php

namespace PixelVision\Payment\Controller;

use Blocky\Controller\SimpleController;
use Blocky\Content\Content;
use Blocky\Content\Exception\ContentSaveException;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class PaymentController extends SimpleController
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function start()
	{
		$transactionnr = $this->route->getAttribute('transaction');

		$transaction = $this->app['payment']->findTransaction($transactionnr);
		$response = $transaction->start(true);

		if($response->isRedirect()) {
			$this->redirect($response->getRedirect());
			return;
		}

		$this->render($response->getTemplate(), $response->getData());
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function cancel()
	{
		$transactionnr = $this->route->getAttribute('transaction');
		$transaction = $this->app['payment']->findTransaction($transactionnr);
		$response = $transaction->cancel();

		if($response->isRedirect()) {
			$this->redirect($response->getRedirect());
			return;
		}

		$this->render($response->getTemplate(), $response->getData());
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function back()
	{
		$transactionnr = $this->route->getAttribute('transaction');
		$transaction = $this->app['payment']->findTransaction($transactionnr);
		$response = $transaction->back();

		if($response->isRedirect()) {
			$this->redirect($response->getRedirect());
			return;
		}

		$this->render($response->getTemplate(), $response->getData());
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function ipn()
	{
		$providerName = $this->route->getAttribute('provider');

		$provider = $this->app['payment']->getProviderByName($providerName);
		$response = $provider->onResponse($this->request);

		if($response->isRedirect()) {
			$this->redirect($response->getRedirect());
			return;
		}

		$this->render($response->getTemplate(), $response->getData());
	}

} // END class BackendController