<?php

namespace Blocky\Logger;

use Blocky\Extension\ContentTypeProvider;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\SimpleExtension;
use Blocky\Content\Content;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class LoggerExtension extends SimpleExtension implements FieldTypeProvider
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $drafts = [];

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function install()
	{
		$this->extendConfig("contenttypes.yml", "contenttypes.yml");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->drafts = [];

		$self = $this;
		$this->app['event']->on("Blocky::contentBeforeSave", function($data) use(&$self) {
			if($self->app['site'] != "backend")
				return;
			$eventContent = $data['content'];
			$contentType = $eventContent->getContentType();

			if($contentType->getSlug() == "adminlog")
				return;
			// at this point in the system, the passed content is merged from the ones posted from the backend, retrieve the original one :)
			
			if($eventContent->getID() == 0) {
				// fresh content, it must have been at creation stage!
				$self->drafts[$contentType->getSlug()."_new"] = [
					'before' => [],
					'after' => $eventContent->toArray(),
					'new' => true
				];
			} else {
				$slug_field = $contentType->getFieldByType('slug');
				$originalContent = $contentType->getContents("where ".$slug_field." = ?", [$eventContent->getValue($slug_field)]);
				$originalContent = $originalContent[0];

				$self->drafts[$contentType->getSlug()."_".$eventContent->getID()] = [
					'before' => $originalContent->toArray(),
					'after' => $eventContent->toArray(),
					'new' => false
				];
			}
		});

		$this->app['event']->on("Blocky::contentSaved", function($data) use(&$self) {
			if($self->app['site'] != "backend")
				return;
			$eventContent = $data['content'];
			$contentType = $eventContent->getContentType();

			if($contentType->getSlug() == "adminlog")
				return;

			$key = $contentType->getSlug()."_new";
			if(!array_key_exists($key, $self->drafts)) {
				$key = $contentType->getSlug()."_".$eventContent->getID();
			}

			$draft = $self->drafts[$key];

			$logContentType = $self->app['content']->getContentType('adminlog');
			$bean = $self->app['storage']->createBean($logContentType->getSlug());
			$content = new Content($logContentType, $bean);
			$content->fromArray([
				'admin' => $self->app['admin']->user->getID(),
				'title' => $draft['new']?"Létrehozás":"Módosítás",
				'contenttype' => $contentType->getSlug(),
				'content_slug' => $eventContent->getValue($contentType->getFieldByType('slug')),
				'content_before' => $draft['before'],
				'content_after' => $draft['after'],
				'locale' => $contentType->hasLocales()?$eventContent->getValue('locale'):'',
				'search_query' => ($draft['new']?"Létrehozás":"Módosítás")." ".$contentType->getName()." ".$contentType->getSingularName()." ".$eventContent->getValue($contentType->getFieldByType('test'))
			]);

			$self->app['content']->storeContent($content);
		});
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
			new FieldType\LogHistory()
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
			'logger' => __DIR__."/views"
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
		return "Blocky::LoggerExtension";
	}
} // END class LoggerExtension