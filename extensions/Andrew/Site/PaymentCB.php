<?php

namespace Andrew\Site;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class PaymentCB
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function cb($app, $details, $event)
	{
		/*file_put_contents("payment_cb_".date("Y-m-d_H-i-s").".txt", print_r([
			'event' => $event,
			'details' => $details->content->toArray()
		], 1));*/

		//rather not pollute everything
		//callback do whatever you want
	}
} // END class PaymentCB