<?php

namespace Blocky\Controller;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ControllerService extends BaseService
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function onRouteFound($data)
	{

		$controller = new $data['routeData']['handler']['_controller']($this->app['router']->request, $data['routeData'], $this->app);
		$controller->beforeRoute();
		$controller->{$data['routeData']['handler']['_action']}();
	}
} // END class ControllerService