<?php

namespace Blocky\Members\Middleware;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Auth
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function onlyLoggedIn(&$app, &$request, &$router)
	{
		if(!$app['members']->isLoggedIn()) {
			$app['path']->redirect( $app['members']->config['redirects']['not_authorized'] );
		}
	}

} // END public class Auth