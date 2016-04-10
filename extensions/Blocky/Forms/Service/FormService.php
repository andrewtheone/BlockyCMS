<?php

namespace Blocky\Forms\Service;

use Blocky\BaseService;
use Blocky\Event\EventData;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FormService extends BaseService
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $forms;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setForms($config)
	{
		$this->forms = $config;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getForm($name)
	{
		return $this->forms[$name];
	}
} // END class Service