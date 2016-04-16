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
class UpdateFormManager extends BaseFormManager
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
			$this->content = $this->app['members']->getMemberContent();
			$this->content->fromArray($this->formData);
			return;
		}

		return parent::onProcess($step, $etc);
	}
} // END class LoginFormManager