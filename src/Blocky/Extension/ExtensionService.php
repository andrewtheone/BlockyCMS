<?php

namespace Blocky\Extension;

use Blocky\Config\YamlWrapper;
use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ExtensionService extends BaseService
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $extensions = [];

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function install(&$class)
	{
		$class->register($this->app);
		if(!$class->isInstalled()) {
			
			$exts = $this->app['config']['installed'];
			$exts[] = $class->getName();
			$this->app['config']['installed'] = $exts;

			$class->install();

			YamlWrapper::emit($this->app['path']['root']."/app/cache/installed.yml", $exts);
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function register($class)
	{
		$this->extensions[$class->getName()] = $class;

		//register extension routes
		$routes = [];
		if(($class instanceof FrontendRouteProvider) && ($this->app['site'] == 'frontend')) {
			$routes = $class->getFrontendRoutes();
		}

		if(($class instanceof BackendRouteProvider) && ($this->app['site'] == 'backend')) {
			$routes = $class->getBackendRoutes();
		}

		foreach($routes as $route) {
			//registerRoute($name, $path, $data)
			$this->app['router']->registerRoute($route['name'], $route['path'], $route);
		}

		unset($routes);

		//register extension contenttypes
		if($class instanceof ContentTypeProvider) {
			$cts = $class->getContentTypes();
			foreach($cts as $slug => $desc) {
				$this->app['content']->addContentType($slug, $desc);
			}
			unset($cts);
		}

		//register extension field types
		if($class instanceof FieldTypeProvider) {
			$fields = $class->getFieldTypes();
			foreach($fields as $field) {
				$this->app['content']->addFieldType($field);
			}
			unset($fields);
		}
		
		//register extension services
		if($class instanceof ServiceProvider) {
			$services = $class->getServices();
			foreach($services as $name => $serviceProvider) {
				$this->app[$name] = call_user_func_array($serviceProvider, [$this->app]);
			}
			unset($services);
		}


		//register extension twig filters
		if($class instanceof TwigFilterProvider) {
			$filters = $class->getTwigFilters();
			foreach($filters as $name => $callback) {
				$filter = new \Twig_SimpleFilter($name, $callback, ['needs_context' => true, 'needs_environment' => true]);
				$this->app['view']->twig->addFilter($filter);
			}
			unset($filter);
			unset($filters);
		}

		//register extension twig functions
		if($class instanceof TwigFunctionProvider) {
			$functions = $class->getTwigFunctions();
			foreach($functions as $name => $callback) {
				$function = new \Twig_SimpleFunction($name, $callback, ['needs_context' => true, 'needs_environment' => true]);
				$this->app['view']->twig->addFunction($function);
			}
			unset($functions);
			unset($function);
		}


		//register extension contenttypes
		if($class instanceof TwigSnippetProvider) {
			$snippets = $class->getSnippets();
			foreach($snippets as $type => $snippet) {
				$this->app['view']->addSnippet($type, $snippet);
			}
			unset($snippets);
		}

		if($class instanceof BackendMenuItemProvider) {
			$menus = $class->getBackendMenuItems();
			$_m = $this->app['config']['backend_menu'];
			foreach($menus as $m) {
				$_m[] = $m;
			}
			$this->app['config']['backend_menu'] = $_m;
		}


		$namespaces = $class->getViewNamespaces();

		foreach($namespaces as $k => $v) {
			$this->app['view']->loader->addPath($v, $k);
		}
	}
} // END class Service