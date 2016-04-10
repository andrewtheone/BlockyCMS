<?php

namespace Blocky\Backend\Controller;

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

		if($this->route->getName() != 'backend_login') {
			if($this->app['admin']->isLoggedIn()) {
				return;
			}
			header("Location: /admin/login");
			exit();
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function login()
	{
		if($this->app['admin']->isLoggedIn()) {
			header("Location: /admin");
			exit();
			return;
		}
		if($this->request->getMethod() == "POST") {
			$isLoggedIn = $this->app['admin']->login($this->request->getAttribute('username'), $this->request->getAttribute('password'));
			
			if($isLoggedIn) {
				$this->app['event']->trigger('Blocky::Halting');
				header("Location: /admin");
				exit();
				return;
			}
		}
		$this->render("@backend/login.twig", []);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function dashboard()
	{
		$this->render("@backend/dashboard.twig", []);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function contentEditById()
	{
		if($this->request->getMethod() == "POST") {
			try {
				$content = $this->app['content']->getContent($this->route->getAttribute('contenttype'), $this->route->getAttribute('id'));
				$content->fromArray($this->request->getAttribute('content'));
				
				$content->bean->site = $this->app['session']['_site'];

				$this->app['content']->storeContent($content);
			} catch(ContentSaveException $ex) {
				$this->app['session']->setFlashMessage('error', $ex->getMessage());
			}

			header("Location: /admin/content/edit/".$content->getContentType()->getSlug()."/".$content->getValue($content->getContentType()->getSlugName()));
			exit();
		}

		$content = $this->app['content']->getContent($this->route->getAttribute('contenttype'), $this->route->getAttribute('id'));

		$this->render("@backend/editcontent.twig", ['content' => $content]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function contentEditBySlug()
	{
		$slug = $this->route->getAttribute('slug');
		$contentType = $this->app['content']->getContentType($this->route->getAttribute('contenttype'));
		$slugName = $contentType->getSlugName();

		if( ($locale = $this->request->getAttribute('locale')) == null) {
			$locale = $this->app['config']['default_locale'];
		}

		$content = null;
		if($contentType->hasLocales()) {
			
			$currentLocaleBean = null;

			if($locale != $this->app['config']['default_locale']) {

				//check if we have a current locale copy of our content
				$currentLocaleBean = $this->app['storage']->findBean($contentType->getSlug(), "where ".$slugName." = ? and locale = ?", [$slug, $locale]);

				if(count($currentLocaleBean) == 0) {
					$currentLocaleBean = $this->app['storage']->createBean($contentType->getSlug());
					//we dont have, so lets fill it with default_locale values' copy
					$defaultLocaleBean = $this->app['storage']->findBean($contentType->getSlug(), "where ".$slugName." = ? and locale = ?", [$slug, $this->app['config']['default_locale']]);
					
					if(count($defaultLocaleBean) == 0) {
						$defaultLocaleBean = $this->app['storage']->findBean($contentType->getSlug(), "where ".$slugName." = ?", [$slug]);
						$defaultLocaleBean = array_shift($defaultLocaleBean);
						$defaultLocaleBean->locale = $this->app['config']['default_locale'];
						$this->app['storage']->storeBean($defaultLocaleBean);
					} else {
						$defaultLocaleBean = array_shift($defaultLocaleBean);
					}

					foreach($contentType->getFields() as $key => $options) {
						if($key != "id") {
							$currentLocaleBean->{$key} = $defaultLocaleBean->{$key};
						}
					}
					$currentLocaleBean->locale = $locale;
					$this->app['storage']->storeBean($currentLocaleBean);
				}
			} 

			if($currentLocaleBean) {
				//reuse variable.. save 3 queries...
				$content = new Content($contentType, (is_array($currentLocaleBean)?array_shift($currentLocaleBean):$currentLocaleBean));
			} else {
				$contents = $this->app['content']->getContents($contentType->getSlug(), "where ".$slugName." = ? and locale = ?", [$slug, $locale]);
				if((count($contents) == 1) && (!$contents[0])) {
					//transition to noLocales to Locales... first edit :P
					$defaultLocaleBean = $this->app['storage']->findBean($contentType->getSlug(), "where ".$slugName." = ?", [$slug]);
					$defaultLocaleBean = array_shift($defaultLocaleBean);
					$defaultLocaleBean->locale = $this->app['config']['default_locale'];
					$this->app['storage']->storeBean($defaultLocaleBean);

					$content = new Content($contentType, $defaultLocaleBean);
				} else {
					$content = $contents[0];
				}
			}
		} else {
			$contents = $this->app['content']->getContents($contentType->getSlug(), "where ".$slugName." = ?", [$slug]);
			$content = $contents[0];
		}

		if($this->request->getMethod() == "POST") {
			try {
				$postData = $this->request->getAttribute('content');
				$content->fromArray($postData);

				if(array_key_exists('site', $postData)) {
					$content->bean->site = $postData['site'];
				}

				$this->app['content']->storeContent($content);
			} catch(ContentSaveException $ex) {
				$this->app['session']->setFlashMessage('error', $ex->getMessage());
			}

			header("Location: /admin/content/edit/".$content->getContentType()->getSlug()."/".$content->getValue($content->getContentType()->getSlugName())."?locale=".$locale);
			exit();
		}

		$this->render("@backend/editcontent.twig", ['content' => $content]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function contentList()
	{
		$contentType = $this->app['content']->getContentType( $this->route->getAttribute('contenttype') );

		if( ($term = $this->request->getAttribute('term', null)) != null) {
			$results = [];

			$searchableFields = $contentType->getFieldsByType('text');
			$where = [];
			$whereArgs = [];

			foreach($searchableFields as $field) {
				$where[] = $field." like ?";
				$whereArgs[] = "%".$term."%";
			}

			$contents = $this->app['content']->getContents($contentType->getSlug(), "WHERE (".implode(" OR ", $where).") GROUP BY slug", $whereArgs);

		} else {
			$contents = $this->app['content']->getContents( $this->route->getAttribute('contenttype'), "GROUP BY slug" );
		}
		$this->render("@backend/listcontent.twig", ['contentType' => $contentType, 'contents' => $contents]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function upload()
	{
		//echo $this->request->getAttribute('type', 'file');
			$path = $this->app['path']['files']."/".date("y-m");
			$file_name = time();
			$tmp_name = $_FILES["file"]["tmp_name"];
			$exp = explode(".", $_FILES['file']['name']);
			$ext = $exp[count($exp)-1];

			@mkdir($path);
			move_uploaded_file($tmp_name, $path."/".$file_name.".".$ext);

			die(json_encode([
				'path' => "/".date("y-m")."/".$file_name.".".$ext
			]));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function search()
	{
		$results = [];
		$contentTypes = [];
		$term = $this->request->getAttribute('term');

		foreach($this->app['content']->getContentTypes() as $ct) {
			if($ct->getOption('searchable', true)) {
				$contentTypes[] = $ct;
			}
		}

		foreach($contentTypes as $ct) {
			$searchableFields = $ct->getFieldsByType('text');
			$where = [];
			$whereArgs = [];

			foreach($searchableFields as $field) {
				$where[] = $field." like ?";
				$whereArgs[] = "%".$term."%";
			}

			$contents = $this->app['content']->getContents($ct->getSlug(), "where ".implode(" OR ", $where), $whereArgs);
			foreach($contents as $c) {
				$results[] = $c;
			}
		}

		$results = array_slice($results, 0, 15);

		$this->render("@backend/search_results.twig", ['results' => $results]);
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function ckeditorWidgets()
	{
		$data = [
			['command' => 'postGallery', 'name' => 'Gallery', 'label' => 'Gallery', 'dialog' => 'selectContent']
		];

		die(json_encode($data));
	}
} // END class BackendController