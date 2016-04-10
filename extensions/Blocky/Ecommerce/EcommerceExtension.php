<?php

namespace Blocky\Ecommerce;

use Blocky\Extension\SimpleExtension;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class EcommerceExtension extends SimpleExtension
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function install()
	{
		$this->extendConfig('forms.yml', [
			'checkout_form' => [
				'contenttype' => 'cart_contet'
			]
		]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "Blocky::EcommerceExtension";
	}
} // END class EcommerceExtension