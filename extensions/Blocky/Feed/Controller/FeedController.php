<?php

namespace Blocky\Feed\Controller;

use Blocky\Controller\SimpleController;
use Blocky\Content\Content;
use Blocky\Content\Exception\ContentSaveException;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FeedController extends SimpleController
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function view()
	{
		$feedName = $this->route->getAttribute('name');
		$type = $this->route->getAttribute('type');

		$feeds = YamlWrapper::parse($this->app['path']->to("config", "feeds.yml"));

		if(!array_key_exists($feedName, $feeds))
			die("No way...");

		$feed = $feeds[$feedName];
		if(!array_key_exists($type, $feed['types']))
			die("No way");

		$feeder = new $feed['feeder']($this->app);

		$items = $feeder->getItems($feedName, $feed['contenttype']);
		
		if(array_key_exists('header', $feed['types'][$type])) {
			header("Content-type: ".$feed['types'][$type]['header']);
		}

		if(array_key_exists('render', $feed['types'][$type])) {
			$render = explode("::", $feed['types'][$type]['render']);

			call_user_func_array([$render[0], $render[1]], [$items, $feed['types'][$type]]);
			return;
		}

		$this->render($feed['types'][$type]['template'], [
			'items' => $items,
			'options' => $feed['types'][$type]
		]);
	}

} // END class BackendController