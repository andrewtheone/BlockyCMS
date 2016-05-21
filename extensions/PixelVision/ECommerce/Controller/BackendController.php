<?php

namespace PixelVision\ECommerce\Controller;

use Blocky\Controller\SimpleController;
use Blocky\Content\Content;
use Blocky\Content\Exception\ContentSaveException;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BackendController extends SimpleController
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function beforeRoute()
	{
		$this->middleware("Blocky\Backend\Middleware\Admin::onlyLoggedIn");
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function properties()
	{
		$q = $this->request->getAttribute('q');
		$keyField = $this->request->getAttribute('type');

		$items = [];


		$items[] = [
			'text' => $q['term'],
			'id' => $q['term'],
			'slug' => $q['term']
		];

		die(json_encode([
			'items' => $items,
			'totalcount' => count($items)
		]));
	}


} // END class BackendController