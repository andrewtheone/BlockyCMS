<?php

namespace Blocky\Members\Controller;

use Blocky\Controller\SimpleController;
use Blocky\Event\EventData;

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
			$member = $provider->onAuthenticate();

			$this->app['session']['member'] = $member->toArray();

			$eventData = new EventData();
			$eventData->member = $member;
			$eventData->provider = $provider;

			$this->app['event']->trigger("Passport::MemberLoggedIn", $eventData);

			$this->app['path']->redirect("/");
		}
	}
} // END class MemberController