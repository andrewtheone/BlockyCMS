<?php

namespace Blocky\Content;


/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
interface SimpleFieldInterface
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate();

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function processInput(Content $content, $input, $options);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function extractValue(Content $content, $value, $options);

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName();
	
} // END class SimpleField