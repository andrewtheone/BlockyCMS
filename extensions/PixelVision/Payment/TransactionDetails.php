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
		return $this->content->getID();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTransactionNr()
	{
		return $this->content->getValue('transid');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getCart()
	{
		$data = json_decode($this->content->getValue('data'), 1);

		return $data['cart'];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTotal()
	{
		$cart = $this->getCart();
		$total = 0;

		array_map(function($element) use(&$total) {
			$total += $element['quantity']*$element['price'];
		}, $cart);

		return $total;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getCustomData($key = null)
	{
		$data = json_decode($this->content->getValue('data'), 1);

		if($key) {
			return (array_key_exists($key, $data['custom'])?$data['custom'][$key]: null);
		}

		return $data['custom'];
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
	public function start($fromPaymentController = false)
	{
		if(!$fromPaymentController) {
			$this->app['path']->redirect("/payment/start/".$this->getTransactionNr());
		}

		$handler = $this->getProviderHandler();
		$this->callCallback(["onStart"]);
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

		return $this->callCallback(["onCancel"]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function back()
	{
		return $this->callCallback(["onBack"]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setStatus($status)
	{
		$this->content->fromArray([
			'status' => $status
		]);

		$this->app['payment']->storeTransaction($this);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setRefNumber($number)
	{
		$this->content->fromArray([
			'refnumber' => $number
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
		$data = json_decode($this->content->getValue('data'), 1);
		$callback = $data['callback'];

		switch($this->content->getValue("status")) {
			case 0:
				$viewType = "pending";
			break;

			case 1:
				$viewType = "success";
			break;

			case 2:
				$viewType = "unsuccess";
			break;

			default:
				$viewType = "cancelled";
			break;
		}

		if(!$callback || (strlen($callback) < 3)) {

			$resp = new ResponseView("@payment/response/".$viewType.".twig", [
				'details' => $this
			]);
			return $resp;
		}

		array_unshift($params, $this);

		if(strpos($callback, ".") !== false) {
			$callbackParts = explode(".", $callback);
			$method = array_pop($callbackParts);
			$service = implode(".", $callbackParts);

			$resp = call_user_func_array([$this->app[$service], $method], $params);

			if( !($resp instanceof ResponseView) ) {
				$resp = new ResponseView("@payment/response/".$viewType.".twig", [
					'details' => $this
				]);
			}
			return $resp;
		}

		$callbackParts = explode("::", $callback);
		array_unshift($params, $this->app);
		$resp = call_user_func_array($callbackParts, $params);
		if( !($resp instanceof ResponseView) ) {
			$resp = new ResponseView("@payment/response/".$viewType.".twig", [
				'details' => $this
			]);
		}
		return $resp;
	}
} // END class BasePaymentProvider