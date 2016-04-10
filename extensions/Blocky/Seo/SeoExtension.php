<?php

namespace Blocky\Seo;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\FrontendRouteProvider;
use Blocky\Extension\BackendRouteProvider;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\ContentTypeProvider;
use Blocky\Extension\BackendMenuItemProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SeoExtension extends SimpleExtension implements FieldTypeProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFieldTypes()
	{
		return [
			new FieldType\Meta()
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getViewNamespaces()
	{
		return [
			'seo' => __DIR__."/views"
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "Blocky::SeoExtension";
	}
} // END class TestExtension