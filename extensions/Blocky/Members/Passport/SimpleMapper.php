<?php

namespace Blocky\Members\Passport;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SimpleMapper
{

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
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function map(&$member, $profile, $options)
	{
		$protoArray = [];
		foreach($options as $foreign => $local) {
			$protoArray[$local] = $profile->$foreign;
		}

		$member->fromArray($protoArray);
	}
} // END class SimplePassport