<?php

namespace Blocky\Seo;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\ServiceProvider;
use Blocky\Extension\FrontendRouteProvider;
use Blocky\Extension\BackendRouteProvider;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\ContentTypeProvider;
use Blocky\Extension\BackendMenuItemProvider;
use Blocky\Extension\TwigFunctionProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SeoExtension extends SimpleExtension implements FieldTypeProvider, ServiceProvider, TwigFunctionProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getServices()
	{
		$this->app->register(new Provider\MetaServiceProvider());
		return [
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTwigFunctions()
	{
		$self = $this;
		return [
			'metatags' => function($a, $b) use($self)  {
				return new \Twig_Markup($self->app['meta']->getContent(), "utf-8");
			}
		];
	}

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