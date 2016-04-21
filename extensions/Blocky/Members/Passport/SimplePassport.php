<?php

namespace Blocky\Members\Passport;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SimplePassport
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $app;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $provider;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $options;


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(&$app, $provider, $options = [])
	{
		$this->app = $app;
		$this->provider = $provider;
		$this->options = $options;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFrontendLink()
	{
		return $this->app['path']->link('members_passport')."?provider=".$this->provider;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function onAuthenticate()
	{
		try
		{
			$config = [
				/*'base_path' => $this->getFrontendLink(),*/
				'providers' => [
					$this->provider => $this->options
				]
			];
	 
			// initialize Hybrid_Auth class with the config file
			$hybridauth = new \Hybrid_Auth( $config );
	 
			// try to authenticate with the selected provider
			$adapter = $hybridauth->authenticate( $this->provider );
	 
			// then grab the user profile
			$user_profile = $adapter->getUserProfile();
			
			print_r($user_profile);
			die("success login!");
		}
	 
		// something went wrong?
		catch( Exception $e )
		{
			die("Something went wrong :P");
		}
	}
} // END class SimplePassport