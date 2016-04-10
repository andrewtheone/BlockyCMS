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
class Random extends SimpleField implements SimpleFieldInterface
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate() {
		return null;
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function processInput(Content $content, $input, $options) {
		if( ($s = $content->getValue($options['inputName'])) ) return $s;

		return md5(base64_encode(time()*rand()));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function extractValue(Content $content, $value, $options) {
		return $value;
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName() {
		return "random";
	}

} // END class Slug