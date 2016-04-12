<?php

namespace Blocky\Forms;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\FrontendRouteProvider;
use Blocky\Extension\BackendRouteProvider;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\ContentTypeProvider;
use Blocky\Extension\ServiceProvider;
use Blocky\Extension\TwigFunctionProvider;
use Blocky\Extension\BackendMenuItemProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FormsExtension extends SimpleExtension implements FrontendRouteProvider, ServiceProvider, TwigFunctionProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		parent::boot();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getServices()
	{
		$config = $this->getConfig('forms.yml');

		return [
			'forms' => function($app) use($config) {
				$service = new Service\FormService($app);
				$service->setForms($config);
				$service->boot();
				return $service;
			}
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
			'forms_ajaxForm' => function($a, $b, $formName) {

			},
			'forms_form' => function($a, $b, $formName) use($self) {
				$formOptions = $self->app['forms']->getForm($formName);
				
				$formContentType = null;
				if(array_key_exists('contenttype', $formOptions))
					$formContentType = $self->app['content']->getContentType($formOptions['contenttype']);

				$formLayout = "@forms/_layout.twig";
				if(array_key_exists('layout', $formOptions))
					$formLayout = $formOptions['layout'];

				$args = [
					'formOptions' => $formOptions,
					'formContentType' => $formContentType,
					'formName' => $formName,
					'app' => $self->app
				];

				$self->addAsset('js', '@this/assets/js/ajaxform.jquery.js');

				return new \Twig_Markup($self->app['view']->twig->render($formLayout, $args), "utf-8");
			},
			'forms_captchaImage' => function($a, $b) {

			} 
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFrontendRoutes()
	{
		return [
			[
				'name' => 'forms_process',
				'path' => '/forms/process',
				'handler' => ['_controller' => 'Blocky\Forms\Controller\FormController', '_action' => 'process']
			]
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
			'forms' => __DIR__."/views"
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
		return "Blocky::FormsExtension";
	}
} // END class TestExtension