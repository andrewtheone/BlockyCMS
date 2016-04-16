<?php

namespace Blocky\Forms;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BaseFormManager 
{

	/**
	 * If after this point, $this->content is not null, then $this->content will be used and fromArray will be skipped!
	 **/
	const PROCESS_INITIAL = 0x01;
	
	/**
	 * At this point, $this->content has a pseudo content, which can be modified, all data has been setted through fromArray
	 **/
	const PROCESS_BEFORE_STORE = 0x02;

	/**
	 * At this point, the content is fully validated, and got stored in the database.
	 **/
	const PROCESS_AFTER_STORE = 0x03;

	/**
	 * Before the email is sent, all data associated with the email can be found in $this->emailData
	 **/
	const PROCESS_BEFORE_EMAIL = 0x04;

	/**
	 * After email has sent
	 **/
	const PROCESS_AFTER_EMAIL = 0x05;

	/**
	 * Validation error uppon saving
	 **/
	const PROCESS_VALIDATION_ERROR = 0x06;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	const PROCESS_VALIDE = 0x07;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	const PROCESS_GET_RESPONSE_JSON = 0x08;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	const PROCESS_GET_REDIRECT_ROUTE = 0x09;

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
	public $formName;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $formOptions;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $formData;

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
	public $contentType;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $refer;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $isValid;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(&$app, $formName, $refer, $formOptions, $formData, &$contentType, $content)
	{
		$this->app = $app;
		$this->formName = $formName;
		$this->refer = $refer;
		$this->formOptions = $formOptions;
		$this->formData = $formData;
		$this->contentType = $contentType;
		$this->content = $content;
		$this->isValid = true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function onProcess($process, $etc = null)
	{
		if($process == self::PROCESS_VALIDATION_ERROR) {
			$this->isValid = false;
		}
		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setContent(&$content)
	{
		$this->content = $content;
	}

} // END class 