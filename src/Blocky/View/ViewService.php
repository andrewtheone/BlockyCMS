<?php

namespace Blocky\View;

use Blocky\BaseService;
use Blocky\Extension\TwigFilterProvider;
use Blocky\Extension\TwigFunctionProvider;
use MatthiasMullie\Minify;
/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ViewService extends BaseService
{
	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	public $assets = ['js' => [], 'style' => []];

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	public $snippets;

	/**
	 * undocumented class variable
	 *
	 * @var Twig_Loader_Fileystem
	 **/
	public $loader;

	/**
	 * undocumented class variable
	 *
	 * @var Twig_Environment
	 **/
	public $twig;

	/**
	 * Adds an asset, if the path has @theme or @extensions it, it will replace them
	 *
	 * @return void
	 * @author 
	 **/
	public function addAsset($type, $path, $priority)
	{
		if((!$this->app['config']['assets']['minify']) || ($this->app['site'] == 'backend')) {
			$path = str_replace("@theme", $this->app['config']['host']."/themes/".$this->app['config']['theme'], $path);
			$path = str_replace("@extensions", $this->app['config']['host']."/extensions", $path);
		}

		if(!array_key_exists($priority, $this->assets[$type])) 
			$this->assets[$type][$priority] = [];

		$this->assets[$type][$priority][] = $path;

		$this->snippets = [];
	}

	/**
	 * Returns assets associated with passed $key, which can be 'js' or 'style'
	 *
	 * @return void
	 * @author 
	 **/
	public function getAssets($key)
	{
		$assets = $this->assets[$key];
		
		$ext = $key == 'js'?'js':'css';

		ksort($assets);

		$list = [];
		foreach($assets as $a) {
			$list = array_merge($list, $a);
		}

		if($this->app['config']['assets']['minify'] && ($this->app['site'] == 'frontend')) {
			$hash = crc32(implode(",", $list));

			if(!file_exists($this->app['path']['files']."/".$hash.".".$ext)) {
				if($ext == "js") {
					$minifier = new Minify\JS();
				} else {
					$minifier = new Minify\CSS();
				}

				foreach($list as $path) {
					$filePath = str_replace("@theme", $this->app['path']['root']."/themes/".$this->app['config']['theme'], $path);
					$filePath = str_replace("@extensions", $this->app['path']['root']."/extensions", $filePath);
					
					$content = "";
					if(file_exists($filePath.".packing")) {
						$content = file_get_contents($filePath.".packing");
					} else {
						$content = file_get_contents($filePath);
					}
					

					$content = str_replace("@theme", $this->app['config']['host']."/themes/".$this->app['config']['theme'], $content);
					$content = str_replace("@extensions", $this->app['config']['host']."/extensions", $content);

					$minifier->add($content);
				}

				$minifier->minify($this->app['path']['files']."/".$hash.".".$ext);
			}

			return [$this->app['path']['files_url']."/".$hash.".".$ext];
			
		}

		return $list;
	}

	/**
	 * Adds a snippet under a certian key. a Snippet can be a function or a string.
	 *
	 * @return void
	 * @author 
	 **/
	public function addSnippet($type, $snippet)
	{
		if(!array_key_exists($type, $this->snippets))
			$this->snippets[$type] = [];

		$this->snippets[$type][] = $snippet;
	}

	/**
	 * Fetch snippets for a certian key. If any of the snippets is callable, it will be called, and the outcome
	 * of the function will be used a a snippet. $args paramter is passed to all callable snippets.
	 *
	 * @return void
	 * @author 
	 **/
	public function getSnippets($type, $args = [])
	{
		$out = '<!-- SNIPPET: '.$type.' START -->';
		$snippets = [];
		if(array_key_exists($type, $this->snippets))
			$snippets = $this->snippets[$type];

		foreach($snippets as $s) {
			$out .= "\n";
			if(is_callable($s)) {
				$out .= $s($this->app, $args = []);
			} else {
				$out .= $s;
			}
		}

		$out .= "\n<!-- SNIPPET: ".$type.' END -->';
		return new \Twig_Markup($out, 'utf-8');
	}

	/**
	 * @inherit
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->loader = new \Twig_Loader_Filesystem([$this->app['path']['views']]);
		$this->loader->addPath($this->app['path']['theme'], 'theme');
		$this->twig = new \Twig_Environment($this->loader);
		$this->twig->addExtension(new \Twig_Extensions_Extension_I18n());
		$snippetFn = new \Twig_SimpleFunction("snippet", [$this, 'getSnippets']);
		$this->twig->addFunction($snippetFn);
	}

} // END class Service