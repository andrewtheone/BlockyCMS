<?php

namespace Andrew\Site\Controller;

use Blocky\Controller\SimpleController;
use Blocky\Content\Content;
use Blocky\Content\Exception\ContentSaveException;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FrontendController extends SimpleController
{



	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function pay()
	{

		$details = $this->app['payment']->createTransaction("borgun", [
			[
				'quantity' => 1,
				'price' => 100,
				'title' => 'Test product'
			]
		], "\Andrew\Site\PaymentCB::cb", [
			'buyername' => 'Teszt Elek',
			'buyeremail' => 'teszt@elek.hu'
		]);

		$details->start();
	}


} // END class BackendController