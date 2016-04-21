<?php

namespace Blocky\Members\Service;

use Blocky\BaseService;
use Blocky\Members\Passport\SimplePassport;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class PassportService extends BaseService
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $config;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $_passports;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->config = YamlWrapper::parse( $this->app['path']->to('config', 'passports.yml') );
		$this->_passports = [];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getPassport($type)
	{
		if(!array_key_exists($type, $this->_passports)) {
			$this->_passports[$type] = new SimplePassport($this->app, $type, $this->config[$type]);
		}

		return $this->_passports[$type];
	}

} // END class PassportService