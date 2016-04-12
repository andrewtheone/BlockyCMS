<?php

namespace Blocky\Backend\Service;

use Blocky\BaseService;
use Blocky\Event\EventData;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class AdminService extends BaseService
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $user;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $content;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $permissions;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->user = $this->app['session']['admin'];
		$this->permissions = [];
		if($this->user) {
			$user = $this->app['content']->getContents("adminuser", "where username = ?", [$this->user['username']]);
			$this->user = $user[0];
			$this->permissions = $this->user->permissions;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function get()
	{
		/*if(!$this->content) {
			if($this->user) {
				$c = $this->app['content']->getContents("adminuser", "where username = ?", [$this->user['username']]);
				$this->content = $c[0];
			}
		}

		return $this->content;*/
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function isLoggedIn()
	{
		return ($this->user != null);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function hasPermission($permission)
	{
		if(!$this->isLoggedIn())
			return false;

		return in_array($permission, $this->permissions) || in_array('admin', $this->permissions);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getPermissions()
	{
		if(!$this->isLoggedIn())
			return [];

		return $this->permissions;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function login($username, $password)
	{
		$contentType = $this->app['content']->getContentType('adminuser');
		$content = $this->app['content']->getContents($contentType->getSlug(), "username = ?", [$username]);
		if(count($content) < 1) {
			return false;
		}

		$content = $content[0];
		$passwordOptions = $contentType->getField('password');
		if(md5($content->getValue($passwordOptions['uses']).$password) == $content->password) {
			$this->app['session']['admin'] = $content->toArray();
			return true;
		}

		return false;
	}
} // END class Service