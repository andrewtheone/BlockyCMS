<?php

namespace Blocky\Feed;

use Blocky\Extension\SimpleExtension;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FeedExtension	extends SimpleExtension
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function install()
	{
		$this->extendConfig("feeds.yml", "feeds.yml");
		$this->extendConfig("routes.yml", "routes.yml");
	}

	public function getViewNamespaces()
	{
		return [
			'feed' => __DIR__."/views"
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
		return "Blocky::FeedExtension";
	}
} // END class FeedExtension