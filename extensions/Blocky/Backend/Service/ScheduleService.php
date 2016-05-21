<?php

namespace Blocky\Backend\Service;

use Blocky\BaseService;
use Blocky\Event\EventData;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ScheduleService extends BaseService
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFor($content)
	{
		$contenttype = $content->getContentType()->getSlug();
		$id = $content->getID();

		$beans = $this->app['storage']->findBean("contentschedules", "contenttype = ? and contentid = ? order by id desc", [$contenttype, $id]);

		return $beans;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getPriorTo($date)
	{

		$beans = $this->app['storage']->findBean("contentschedules", "prior_at < ? and applied = 0", [$date]);

		return $beans;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function add($content, $data = [], $at = '0000-00-00 00:00:00')
	{
		$oldBean = $content->bean;
		$newBean = $content->getContentType()->createContent(null, $data)->bean;
		$fields = $content->getContentType()->getFields();

		$modifications = [];

		foreach($fields as $field => $opts) {
			if( array_key_exists($field, $data) ) {
				if($newBean->{$field} !== $oldBean->{$field}) {
					$modifications[$field] = $data[$field];
				}
			}
		}

		$bean = $this->app['storage']->createBean("contentschedules");
		$bean->contenttype = $content->getContentType()->getSlug();
		$bean->contentid = $content->getID();
		$bean->data = json_encode($modifications);
		$bean->prior_at = $at;
		$bean->applied = 0;

		$this->app['storage']->storeBean($bean);
		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function apply($scheduleData)
	{

		$content = $this->app['content']->getContent($scheduleData->contenttype, $scheduleData->contentid);
		$_data = json_decode($scheduleData->data, 1);

		$content->fromArray($_data);

		$this->app['content']->storeContent($content);

		$beans = $this->app['storage']->findBean("contentschedules", "id = ?", [$scheduleData->id]);
		$bean = array_pop($beans);
		$bean->applied = 1;
		$this->app['storage']->storeBean($bean);
		return true;
	}


} // END class Service