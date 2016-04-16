<?php

namespace Blocky\Members\Controller;

use Blocky\Controller\SimpleController;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class MemberController extends SimpleController
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function logout()
	{
		$this->app['members']->logout();
		$this->app['path']->redirect('/');
	}

} // END class MemberController