<?php

namespace Blocky\Fields\FieldType;

use Blocky\Content\SimpleField;
use Blocky\Content\SimpleFieldInterface;
use Blocky\Content\Content;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Timestamp extends SimpleField implements SimpleFieldInterface
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate() {
		return "@fields/timestamp.twig";
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function processInput(Content $content, $input, $options) {
		
		if(!$input || (strlen($input) < 3)) {
			if(array_key_exists('default', $options)) {
				switch($options['default']) {
					case 'now':
						return strtotime('now');
					break;
				}
			}
		}

		return strtotime($input);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function extractValue(Content $content, $value, $options) {
		return date("Y-m-d H:i:s", $value);
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName() {
		return "timestamp";
	}

} // END class Slug