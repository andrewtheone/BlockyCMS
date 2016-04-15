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

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getErrors($formName)
	{
		$protoErrors = $this->app['session']->getFlashMessages("forms.errors.".$formName);
		$errors = [];
		foreach($protoErrors as $r) {
			if(is_array($r)) {
				$errors[$r['field']] = $r['message'];
			} else {
				$errors['__form'] = $r['message'];
			}
		}
		return $errors;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getPreviousState($formName)
	{
		$states = $this->app['session']->getFlashMessages('forms.data.'.$formName);

		if(count($states) > 0) {
			$contentType = $this->app['content']->getContentType($this->forms[$formName]['contenttype']);
			return $contentType->createContent(null, $states[0]);
		}
		return [];
	}
} // END class Service