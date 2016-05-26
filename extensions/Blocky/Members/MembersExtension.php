<?php

namespace Blocky\Members;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\ServiceProvider;
use Blocky\Extension\BackendRouteProvider;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\ContentTypeProvider;
use Blocky\Extension\BackendMenuItemProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class MembersExtension extends SimpleExtension  implements ServiceProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function install()
	{
		$this->extendConfig("contenttypes.yml", "contenttypes.yml");
		$this->extendConfig("forms.yml", "forms.yml");
		$this->extendConfig("members.yml", "members.yml");
		$this->extendConfig("routes.yml", "routes.yml");
		$this->extendConfig("passports.yml", "passports.yml");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$self = $this;
		$this->app['event']->on("Members::onSignup", function($data) use($self){
			if( !$self->app['members']->config['emails']['signup']['subject'] && strlen( $self->app['members']->config['emails']['signup']['subject']) == 0 ) 
				return;
			$self->app['mail']->send($data['member']->getValue('email'), $self->app['members']->config['emails']['signup']['subject'], $self->app['members']->config['emails']['signup']['template'], [
				'member' => $data['member']
			]);
		});
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getServices()
	{
		return [
			'members' => function($app) {
				$members = new Service\MembersService($app);
				$members->boot();
				return $members;
			},
			'passport' => function($app) {
				$passport = new Service\PassportService($app);
				$passport->boot();
				return $passport;
			}
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getViewNamespaces()
	{
		return [
			'members' => __DIR__."/views"
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "Blocky::MembersExtension";
	}
} // END class TestExtension