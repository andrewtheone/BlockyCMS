<?php

namespace Blocky\Backend\Middleware;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Admin
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function onlyLoggedIn(&$app, &$request, &$router)
	{
		if($app['router']->route->handler->getName() != 'backend_login') {
			if($app['admin']->isLoggedIn()) {
				return;
			}
			$app['path']->redirect("/admin/login");
			exit();
		}
	}

} // END public class Auth