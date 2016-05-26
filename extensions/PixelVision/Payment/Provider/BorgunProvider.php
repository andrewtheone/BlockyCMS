<?php

namespace PixelVision\Payment\Provider;

use PixelVision\Payment\BasePaymentProvider;
use PixelVision\Payment\ResponseView;

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

		$total = 0;
		$cart = $details->getCart();

		$fields = [
			"merchantid" => $this->options['options']['merchantid'],
			"paymentgatewayid" => $this->options['options']['paymentgatewayid'],
			"checkhash" => "",
			"orderid" => $details->getTransactionNr(),
			"currency" => "HUF",
			"language" => "HU",
			"buyername" => $details->getCustomData('buyername'),
			"buyeremail" => $details->getCustomData('buyeremail'),
			"returnurlsuccess" => $this->app['path']->link("/payment/back")."/".$details->getTransactionNr(),
			"returnurlsuccessserver" => $this->app['path']->link("/payment/ipn/borgun"),
			"returnurlcancel" => $this->app['path']->link("/payment/cancel")."/".$details->getTransactionNr(),
			"returnurlerror" => $this->app['path']->link("/payment/cancel")."/".$details->getTransactionNr(),
			"amount" => 0,
			"pagetype" => "0",
			"skipreceiptpage" => "0",
			"merchantemail" => $this->options['options']['merchantemail']
		];

		$i = 0;
		foreach($cart as $item) {
			if($item['price'] > 0) {
				$fields['itemunitamount_'.$i] = $item['price'];
				$fields['itemamount_'.$i] = ($item['price']*$item['quantity']);
				$fields['itemcount_'.$i] = $item['quantity'];
				$fields['itemdescription_'.$i] = $item['title'];
				$i++;   

				$total += $item['price']*$item['quantity'];
			}
		}

		$fields['amount'] = $total;

		$message = utf8_encode($fields['merchantid'].'|'.$fields['returnurlsuccess']."|".$fields["returnurlsuccessserver"]."|".$fields['orderid']."|".$fields['amount']."|HUF");
		$fields['checkhash'] = hash_hmac('sha256', $message, $this->options['options']['secretkey']);

		if($this->options['options']['test'] == 1) {
			$display = '<form id="form1" action="https://test.borgun.is/SecurePay/default.aspx" method="post">';
		} else {
			$display = '<form id="form1" action="https://securepay.borgun.is/SecurePay/default.aspx" method="post">';
		}
		
		foreach($fields as $k => $v) {
			$display .= "<input type='hidden' name='".$k."' value='".$v."'>";
		}
		$display .= "<input name='PostButton' class='btn button' type='submit' value='Tovább a fizetési oldalra!'>";
		$display .= "</form>";
			
		$borgun_view = array_key_exists('view', $this->options['options'])?$this->options['options']['view']:'@payment/provider/borgun.twig';

		return new ResponseView($borgun_view, [
			'form' => $display
		]);
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

			$isSuccess = false;
			$transactionDetails->setStatus(2);
			$transactionDetails->setRefNumber($_POST['authorizationcode']);

			if($_POST['status'] == "OK") {
				if($_POST['step'] != "Payment") return;

				$total = 0;
				$cart = $transactionDetails->getCart();

				foreach($cart as $item) {
					if($item['price'] > 0) {
						$total += $item['price']*$item['quantity'];
					}
				}

				$message = utf8_encode($_POST['orderid'].'|'.$total.'|HUF');
				$hash = hash_hmac('sha256', $message, $this->options['options']['secretkey']);
				if($hash == $_POST['orderhash']) {
					$transactionDetails->setStatus(1);
					$isSuccess = true;
				}
				
			}
			
			if($isSuccess) {
				$transactionDetails->callCallback(["onSuccess"]);
			} else {
				$transactionDetails->callCallback(["onUnsuccess"]);
			}

			die("");
		}
	}
} // END class BorgunProvider 