<?php

namespace Blocky\Members\Form;

use Blocky\Forms\BaseFormManager;
use Blocky\Content\Content;
use Blocky\Content\Exception\ContentSaveException;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class LoginFormManager extends BaseFormManager
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function onProcess($step, $etc = null)
	{
		if($step == self::PROCESS_INITIAL) {
			$loginResult = $this->app['members']->login( $this->formData[ $this->app['members']->config['login']['username_field'] ], $this->formData[ $this->app['members']->config['login']['password_field'] ]);

			if(!$loginResult) {
				throw new ContentSaveException("Hibás felhasználónév vagy jelszó!", $this->app['members']->config['login']['username_field']);
				return false;
			}

			return true;
		}

		if($step == self::PROCESS_GET_RESPONSE_JSON) {
			if($this->isValid) {
				return ['success' => 1, 'errors' => [], 'redirect' => '/', 'messages' => ['Sikeres belépés']];
			}
			// returning false, uses the contentsaveexception as error message
			return false;
		}

		if($step == self::PROCESS_GET_REDIRECT_ROUTE) {
			if($this->isValid) {
				return "/";
			}
			return "__none__";
		}

		return parent::onProcess($step, $etc);
	}
} // END class LoginFormManager