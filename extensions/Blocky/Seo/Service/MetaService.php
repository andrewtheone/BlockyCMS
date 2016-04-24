<?php

namespace Blocky\Seo\Service;

use Blocky\BaseService;
use Blocky\Event\EventData;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class MetaService extends BaseService
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $fields;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$config = $this->app['config'];
		$title = $config['site']['title'];

		$route = $this->app['router']->route;

		if($route->handler && $route->handler['site']) {
			$title = $route->handler['site']['title'];
		}

		$this->fields = [
			'title' => $title,
			'description' => $config['site']['description'],
			'keywords' => $config['site']['keywords'],
			'og:title' => $title,
			'og:description' => $config['site']['description'],
			'og:image' => $config['site']['og_image'],
			'og:url' => $this->app['config']['host'].$this->app['router']->request->getUri()->getFullPath(),
			'DC.publisher' => $title,
			'DC.title' => $title,
			'DC.description' => $config['site']['description'],
			'author' => $config['site']['author'],
			'viewport' => 'width=device-width, initial-scale=1'

		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function set($key, $value)
	{
		$this->fields[$key] = $value;

		if(array_key_exists("og:".$key, $this->fields)) {
			$this->fields["og:".$key] = $value;
		}

		if(array_key_exists("DC.".$key, $this->fields)) {
			$this->fields["DC.".$key] = $value;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContent()
	{
		$lines = "";
		$title = $this->app['config']['site']['title'];

		foreach($this->fields as $k => $v) {
			if(($k == "title") || ($k == "og:title") || ($k == "DC.title")) {
				if($v != $title)
					$v = $v." - ".$title;

				$lines .= "<title>".$v."</title>";
			}
			if(is_array($v)) {
				foreach($v as $l) {
					$lines .= '<meta property="'.$k.'" name="'.$k.'" content="'.$l.'">';
				}
			} else {
				$lines .= '<meta property="'.$k.'"  name="'.$k.'" content="'.$v.'">';
			}
		}

		return $lines;
	}
} // END class Service