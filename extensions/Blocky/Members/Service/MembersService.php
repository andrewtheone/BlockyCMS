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

		$member = $this->app['session']->get('member', null);
		if($member) {
			$content = $this->app['content']->getContents($this->config['contenttype'], $this->config['login']['username_field']." = ?", [ $member[$this->config['login']['username_field']] ]);

			if(count($content) > 0) {
				$this->member = $content[0];
			}
		}
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
	public function getMemberContent()
	{
		return $this->member;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getMemberByKey($key, $val)
	{
		$contentType = $this->app['content']->getContentType($this->config['contenttype']);
		$content = $this->app['content']->getContents($contentType->getSlug(), $key." = ?", [$val]);

		if(count($content) < 1)
			return null;

		return $content[0];
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

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function logout()
	{
		$this->app['event']->trigger('Members::LoggingOut');
		$this->app['session']['member'] = null;
		$this->app['event']->trigger('Members::LoggedOut');
	}
} // END class Service