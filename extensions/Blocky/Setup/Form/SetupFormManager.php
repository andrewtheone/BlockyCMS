<?php

namespace Blocky\Setup\Form;

use Blocky\Forms\BaseFormManager;
use Blocky\Content\Content;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SetupFormManager extends BaseFormManager
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
			$adminuserContenType = $this->app['content']->getContentType("adminuser");
			$adminuserBean = $this->app['storage']->createBean($adminuserContenType->getSlug());

			$adminuser = new Content($adminuserContenType, $adminuserBean);

			$this->formData['permissions'] = ['admin'];
			$adminuser->fromArray($this->formData);

			$this->app['content']->storeContent($adminuser);

			$this->app['event']->trigger("Setup::Success");
		}
	}
} // END class SetupFormManager