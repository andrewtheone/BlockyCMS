<?php

namespace Blocky\Members\Form;

use Blocky\Forms\BaseFormManager;
use Blocky\Content\Content;
use Blocky\Event\EventData;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SignupFormManager extends BaseFormManager
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function onProcess($step, $etc = null)
	{
		if($step == self::PROCESS_VALIDE) {
			$args = new EventData();
			$args['member'] = $this->content;
			$this->app['event']->trigger("Members::onSignup", $args);
		}
		return parent::onProcess($step, $etc);
	}
} // END class LoginFormManager