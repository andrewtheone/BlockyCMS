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
class Repeater extends SimpleField implements SimpleFieldInterface
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate() {
		return "@fields/repeater.twig";
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function processInput(Content $content, $input, $options) {

		$data = [];

		foreach($input as $itr => $fields) {
			$v = [];
			foreach($options['fields'] as $key => $field) {
				$v[$key] = $this->app['content']->getField($field['type'])->processInput($content, $fields[$key], $field);
			}
			$data[] = $v;
		}

		return json_encode($data, 1);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function extractValue(Content $content, $value, $options) {
		if(strlen($value) > 1)
			$f = json_decode($value, 1);
		else
			$f = [];
		foreach($f as &$row) {
			foreach($options['fields'] as $name => $_options) {
				$val = '';
				if(array_key_exists($name, $row))
					$val = $row[$name];
				$row[ $name ] = $this->app['content']->getField($_options['type'])->extractValue($content, $val, $_options);
			}
		}

		return $f;
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName() {
		return "repeater";
	}

} // END class Text