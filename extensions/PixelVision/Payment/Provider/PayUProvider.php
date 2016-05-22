<?php

namespace PixelVision\Payment\Provider;

use PixelVision\Payment\BasePaymentProvider;

require_once __DIR__."/../libs/payu/PayUPayment.class.php";

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class PayUProvider extends BasePaymentProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "payu";
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
		if($request->getAttribute('REFNOEXT', false)) {
			$transactionDetails = $this->app['payment']->findTransaction($request->getAttribute('REFNOEXT'));
	        
	        $payu_config = [
	            'MERCHANT' => $this->options['options']['MERCHANT'], //merchant account ID (HUF)
	            'SECRET_KEY' => $this->options['options']['SECRET_KEY'],  //secret key for account ID (HUF)    
	            'EUR_MERCHANT' => "", //merchant account ID (EUR)
	            'EUR_SECRET_KEY' => "", //secret key for account ID (EUR)
	            'USD_MERCHANT' => "", //merchant account ID (USD)
	            'USD_SECRET_KEY' => "", //secret key for account ID (USD)
	            'METHOD' => "CCVISAMC",                                             //payment method     empty -> select payment method on PayU payment page OR [ CCVISAMC, WIRE ]
	            'ORDER_DATE' => date("Y-m-d H:i:s"),                                //date of transaction
	            'LOGGER' => false,                                                   //transaction log
	            'LOG_PATH' => 'log',                                                //path of log file
	            'ORDER_TIMEOUT' => 300,
	            'LANGUAGE' => 'HU',
	            'GET_DATA' => $_GET,
	            'POST_DATA' => $_POST,
	            'SERVER_DATA' => $_SERVER,
	        ];

	        $ipn = new \PayUIpn($payu_config);
	        if($ipn->validateReceived()){
	        
	            //echo <EPAYMENT> (must have)
	            echo $ipn->confirmReceived();
	        } else {
	        	echo "Not valid payu response";
	        }

			die("Servicing ".$this->getName()." payment ipn, transaction-id:".$request->getAttribute('REFNOEXT'));
		}
	}
} // END class BorgunProvider 