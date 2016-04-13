<?php

namespace Blocky\Logger\FieldType;

use Blocky\Content\SimpleField;
use Blocky\Content\SimpleFieldInterface;
use Blocky\Content\Content;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class LogHistory extends SimpleField implements SimpleFieldInterface
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate() {
		return "@fields/text.twig";
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function processInput(Content $content, $input, $options) {
		return json_encode($input, 1);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function extractValue(Content $content, $value, $options) {
		return json_decode($value, 1);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName() {
		return "loghistory";
	}

} // END class Text