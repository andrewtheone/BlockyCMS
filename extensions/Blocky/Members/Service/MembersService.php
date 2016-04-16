<?php

namespace Blocky\Members\Service;

use Blocky\BaseService;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class MembersService extends BaseService
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $member;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $config;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->config = YamlWrapper::parse( $this->app['path']->to('config', 'members.yml') );
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function isLoggedIn()
	{
		return ($this->member != null);
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function login($username, $password)
	{
		$contentType = $this->app['content']->getContentType($this->config['contenttype']);
		$content = $this->app['content']->getContents($contentType->getSlug(), $this->config['login']['username_field']." = ?", [$username]);
		if(count($content) < 1) {
			return false;
		}

		$content = $content[0];
		$passwordOptions = $contentType->getField('password');
		if(md5($content->getValue($passwordOptions['uses']).$password) == $content->password) {
			$this->app['session']['member'] = $content->toArray();
			return true;
		}

		return false;
	}
} // END class Service