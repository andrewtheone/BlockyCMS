<?php

namespace Blocky\Backend;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\BackendRouteProvider;
use Blocky\Extension\ServiceProvider;
use Blocky\Extension\TwigFunctionProvider;
use Blocky\Extension\BackendMenuItemProvider;
use Blocky\Extension\ContentTypeProvider;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\CommandProvider;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BackendExtension extends SimpleExtension implements ServiceProvider, FieldTypeProvider, BackendRouteProvider, TwigFunctionProvider, BackendMenuItemProvider, ContentTypeProvider, CommandProvider
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		if($this->app['site'] == "backend") {
			$this->addAsset('js', '@this/assets/js/jquery-1.10.2.min.js');
			$this->addAsset('js', '@this/assets/js/jquery.ui.min.js');
			$this->addAsset('js', '@this/assets/js/bootstrap.min.js');
			$this->addAsset('js', '@this/assets/js/classie.js');
			$this->addAsset('js', '@this/assets/js/Chart.js');
			$this->addAsset('js', '@this/assets/js/jquery.flexisel.js');
			$this->addAsset('js', '@this/assets/js/jquery.flot.min.js');
			$this->addAsset('js', '@this/assets/js/jquery.nicescroll.js');
			$this->addAsset('js', '@this/assets/js/skycons.js');
			$this->addAsset('js', '@this/assets/js/uisearch.js');
			$this->addAsset('js', '@this/assets/js/wow.min.js');
			$this->addAsset('js', '@this/assets/js/scripts.js', 1000);

			$this->addAsset('style', '@this/assets/css/bootstrap.min.css');
			$this->addAsset('style', '@this/assets/css/font-awesome.css');
			$this->addAsset('style', '@this/assets/css/graph.css');
			$this->addAsset('style', '@this/assets/css/icon-font.min.css');
			$this->addAsset('style', '@this/assets/css/animate.css');
			$this->addAsset('style', '@this/assets/css/style.css');
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getBackendMenuItems()
	{
		return [
			[
				'name' => 'Admin felhasználók',
				'icon' => 'lnr-users',
				'children' => [
					[
						'name' => 'Lista',
						'href' => '/admin/content/list/adminuser'
					],
					[
						'name' => 'Új felhasználó',
						'href' => '/admin/content/edit/adminuser'
					]
				]
			]
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getServices()
	{
		return [
			'admin' => function($app) {
				$service = new Service\AdminService($app);
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
	public function getContentTypes()
	{
		return YamlWrapper::parse(__DIR__."/config/contenttypes.yml");
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
			new FieldType\Permission()
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getBackendRoutes()
	{
		return [
			[
				'name' => 'backend_login',
				'path' => '/admin/login',
				'handler' => ['_controller' => 'Blocky\Backend\Controller\BackendController', '_action' => 'login']
			],
			[
				'name' => 'backend_homepage',
				'path' => '/admin',
				'handler' => ['_controller' => 'Blocky\Backend\Controller\BackendController', '_action' => 'dashboard']
			],
			[
				'name' => 'backend_omnisearch',
				'path' => '/admin/search',
				'handler' => ['_controller' => 'Blocky\Backend\Controller\BackendController', '_action' => 'search']
			],
			[
				'name' => 'backend_editcontent_by_id',
				'path' => '/admin/content/edit/{contenttype}{/id}',
				'handler' => ['_controller' => 'Blocky\Backend\Controller\BackendController', '_action' => 'contentEditById'],
				'defaults' => ['id' => 0],
				'tokens' => ['id' => '[0-9]*']
			],
			[
				'name' => 'backend_editcontent_by_slug',
				'path' => '/admin/content/edit/{contenttype}{/slug}',
				'handler' => ['_controller' => 'Blocky\Backend\Controller\BackendController', '_action' => 'contentEditBySlug'],
				'defaults' => ['slug' => ''],
				'tokens' => ['slug' => '[a-z0-9\_\-]*']
			],
			[
				'name' => 'backend_listcontent',
				'path' => '/admin/content/list/{contenttype}',
				'handler' => ['_controller' => 'Blocky\Backend\Controller\BackendController', '_action' => 'contentList']
			],
			[
				'name' => 'backend_upload',
				'path' => '/admin/upload',
				'handler' => ['_controller' => 'Blocky\Backend\Controller\BackendController', '_action' => 'upload']
			],
			[
				'name' => 'backend_ckeditor',
				'path' => '/admin/getCKEditorWidgets',
				'handler' => ['_controller' => 'Blocky\Backend\Controller\BackendController', '_action' => 'ckeditorWidgets']
			]
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
		return [
			'getMemoryUsage' => function() {
		        $mem_usage = memory_get_usage(false); 
		        
		        if ($mem_usage < 1024) 
		            return $mem_usage." bytes"; 
		        elseif ($mem_usage < 1048576) 
		            return round($mem_usage/1024,2)." kilobytes"; 
		        else 
		            return round($mem_usage/1048576,2)." megabytes"; 
			},
			'getContents' => [$this, 'twigGetContents'],
			'pager' => [$this, 'twigPager'],
			'round' => function($a, $b, $c) {
				return round($c);
			},
			'ceil' => function($a, $b, $c) {
				return ceil($c);
			},
			'floor' => function($a, $b, $c) {
				return floor($c);
			}
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function twigPager($a, $b, $list, $template = null, $limit = null, $pageAttr = null)
	{
		$attr = $this->app['config']['pager']['page_attribute'];
		if($pageAttr)
			$attr = $pageAttr;
		
		if(!$limit) {
			$limit = $this->app['config']['pager']['limit'];
		}

		$pagerTmpl = $this->app['config']['view']['pager'];
		if($template) {
			$pagerTmpl = $template;
		}

		$page = $this->app['router']->request->getAttribute($attr , 1);

		$where = $list->getWhere();
		$args = $list->getWhereArgs();

		$_where = explode("LIMIT", $where);
		$where = $_where[0];
		
		if(count($_where) == 1) $_where[1] = "";

		$args = array_slice($args, 0, count($args)-substr_count($_where[1], '?'));

		$count = $this->app['storage']->countBeans($list->getContentTypeSlug(), $where, $args);

		$path = $this->app['router']->request->getServerParams();
		$path = $path['REQUEST_URI'];
		$path = explode("".$attr."=", $path);
		$path = $path[0];
		if((strpos($path, "?") !== false)) {
			if(($path[strlen($path)-1] != "&"))
				$path .= "&";
		}  else {
			$path .= "?";
		}
		return new \Twig_Markup($a->render($pagerTmpl, ['app' => $this->app, 'path' => $path, 'count' => $count, 'limit' => $limit, 'page' => $page, 'attr' => $attr]), 'utf-8');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function twigGetContents($a, $b, $contenttype, $where = '', $args = [])
	{
		$g = $this->app['content']->getContents($contenttype, $where, $args);

		return $g;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getCommands()
	{
		return [
			new Command\BrewLanguageCommand()
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
			'backend' => __DIR__."/views"
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
		return "Blocky::BackendExtension";
	}
} // END class FrontendExtension