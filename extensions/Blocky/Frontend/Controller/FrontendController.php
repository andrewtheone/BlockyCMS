<?php

namespace Blocky\Frontend\Controller;

use Blocky\Controller\SimpleController;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FrontendController extends SimpleController
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function recordView()
	{

		$contentType = $this->route->getAttribute('contenttype');
		$slug = $this->route->getAttribute('slug');

		$contentType = $this->app['content']->getContentType($contentType);

		$mds = $contentType->getOption('middlewares', []);
		foreach($mds as $m) {
			$this->middleware($m);
		}

		$content = $this->app['content']->getContents($contentType->getSlug(), 'slug = ?', [$slug]);
		$content = $content[0];
		$this->render( $contentType->record_view , [
			'content' => $content
		]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function templateView()
	{
		//$this->app['mail']->send("andrewxxtheone@gmail.com", "ahham", "@theme/homepage.twig", []);
		$this->render( $this->route['template'] );
	}
} // END class FrontendController