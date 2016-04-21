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

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function passport()
	{
		if($this->request->getAttribute("hauth.start", false) || $this->request->getAttribute("hauth.done", false)) {
			\Hybrid_Endpoint::process();
		} else {
			$providerName = $this->request->getAttribute('provider');
			$provider = $this->app['passport']->getPassport($providerName);
			$provider->onAuthenticate();
		}
	}
} // END class MemberController