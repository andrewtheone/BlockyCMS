<?php

namespace Blocky\Extension;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
interface ServiceProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getServices();

	/*

	public function getServices() {
		return [
			'some_service' => function(&$app) {
				$serviceInstance = new SomeServiceClass();
				$serviceInstance->setApplication($app);
				return $serviceInstance;

				// after service has initiated $app['some_service'] is a valid service
			}
		]
	}

	*/
} // END interface BackendRouteProvider