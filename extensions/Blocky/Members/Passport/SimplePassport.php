<?php

namespace Blocky\Members\Passport;

use Blocky\Event\EventData;

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
			$profile = $adapter->getUserProfile();

			$beans = $this->app['storage']->findBean("passports", "provider = ? and provider_id = ?", [$this->provider, $profile->identifier]);
			if(count($beans) == 0) {
				$member = $this->app['members']->getMemberByKey($this->options['local_id_key'], $profile->{$this->options['foreign_id_key']});
				if(!$member) {
					$mapperClass = new $this->options['mapper']($this->app);

					$member = $this->app['content']->createContent( $this->app['members']->config['contenttype'] );

					$mapperClass->map($member, $profile, $this->options['mapper_options']);

					$protoData = [
						$this->app['members']->config['login']['password_field'] => time()
					];
					$member->fromArray($protoData);

					$this->app['content']->storeContent($member);

					$eventData = new EventData();
					$eventData->member = $member;
					$eventData->provider = $this->provider;
					$eventData->options = $this->options;
					$eventData->profile = $profile;

					$this->app['event']->trigger("Passport::MemberCreated", $eventData);
				}

				$bean = $this->app['storage']->createBean("passports");
				$bean->provider = $this->provider;
				$bean->provider_id = $profile->identifier;
				$bean->member_id = $member->getID();

				$this->app['storage']->storeBean($bean);

				$eventData = new EventData();
				$eventData->member = $member;
				$eventData->provider = $this->provider;
				$eventData->options = $this->options;
				$eventData->profile = $profile;

				$this->app['event']->trigger("Passport::MemberConnected", $eventData);
			} else {
				$bean = array_pop($beans);
				$member = $this->app['content']->getContent($this->app['members']->config['contenttype'], $bean->member_id);
				$eventData = new EventData();

				$eventData->member = $member;
				$eventData->provider = $this->provider;
				$eventData->options = $this->options;
				$eventData->profile = $profile;

				$this->app['event']->trigger("Passport::MemberLoggingIn", $eventData);
			}

			return $member;
		}
	 
		// something went wrong?
		catch( Exception $e )
		{
			die("Something went wrong :P");
		}
	}
} // END class SimplePassport