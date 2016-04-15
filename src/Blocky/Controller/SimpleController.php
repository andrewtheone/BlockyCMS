<?php

namespace Blocky\Controller;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SimpleController
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $request;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $route;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $app;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(&$request, &$route, &$app)
	{
		$this->request = $request;
		$this->route = $route;
		$this->app = $app;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function beforeRoute()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function render($template, $arr = [])
	{
		$arr['app'] = &$this->app;
		
		echo $this->app['view']->twig->render($template, $arr);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function json($data)
	{
		die(json_encode($data));
	}
} // END class SimpleController