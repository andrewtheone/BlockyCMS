<?php

namespace Blocky\Content\Exception;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ContentSaveException extends \Exception
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $field;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($message, $field = null)
	{
		$this->field = $field;
		parent::__construct($message);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getField()
	{
		return $this->field;
	}
} // END class ContentSaveException