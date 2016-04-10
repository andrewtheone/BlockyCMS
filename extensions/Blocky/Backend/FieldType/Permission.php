<?php

namespace Blocky\Backend\FieldType;

use Blocky\Content\SimpleField;
use Blocky\Content\SimpleFieldInterface;
use Blocky\Content\Content;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Permission extends SimpleField implements SimpleFieldInterface
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate() {
		return "@backend/fields/permission.twig";
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function processInput(Content $content, $input, $options) {
		if(!$input) return "[]";
		return json_encode($input);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function extractValue(Content $content, $value, $options) {
		if(($value == null) || (strlen($value) == 0))
			return [];
		return json_decode($value, 1);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName() {
		return "permission";
	}

} // END class Text