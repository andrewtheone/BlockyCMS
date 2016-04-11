<?php

namespace Blocky\Content;

use Blocky\Config\YamlWrapper;
use Blocky\BaseService;
use Blocky\Event\EventData;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ContentService extends BaseService
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $fieldTypes = [];

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $contentTypes = [];

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$cts = YamlWrapper::parse($this->app['path']->to('config', 'contenttypes.yml'));

		foreach($cts as $key => $v) {
			$this->addContentType($key, $v);
		}

		unset($cts);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContent($contentSlug, $id = 0)
	{
		$ct = $this->getContentType($contentSlug);
		if($id == 0) {
			$bean = $this->app['storage']->createBean($ct->getSlug());

			return new Content($ct, $bean);
		}

		$bean = $this->app['storage']->findBean($ct->getSlug(), 'id = :id', [':id' => $id]);
		return new Content($ct, array_pop($bean));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContents($contentSlug, $where = '', $args = [])
	{

		$ct = $this->getContentType($contentSlug);
		$results = [];

		$needClosing = true;
		if($ct->getOption('syncable', true) && (stripos($where, "site in") === false)) {
			if(stripos($where, "where") === FALSE) {
				$where = "where site in ('both', '".$this->app['session']['_site']."') ".$where;
				$needClosing = false;
			} else {
				$where = "where site in ('both', '".$this->app['session']['_site']."') AND (".str_replace(["where", "WHERE"], ["", ""], $where);
			}
		} else {
			$needClosing = false;
		}
		if(strpos($where, " LIMIT ") === FALSE) {
			$limit = $this->app['router']->request->getAttribute( $this->app['config']['pager']['limit_attribute'], $this->app['config']['pager']['limit']);
			$page = $this->app['router']->request->getAttribute( $this->app['config']['pager']['page_attribute'], 1);
			$page--;
			$where .= " LIMIT ".($page)*($limit).", ".$limit;
		}

		if($ct->getOption('syncable', true) && $needClosing) {
			if(stripos($where, "GROUP BY") !== FALSE) {
				$where = str_replace("GROUP BY", ") GROUP BY", $where);
			} else {
				$where = str_replace("LIMIT", ") LIMIT", $where);
			}
		}

		$beans = $this->app['storage']->findBean($ct->getSlug(), $where, $args);

		foreach($beans as $b) {
			$results[] = new Content($ct, $b);
		}

		return new ContentList($contentSlug, $results, $where, $args);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function storeContent(Content $ct)
	{
		$ct->getManager()->beforeSave();

		$this->app['storage']->storeBean($ct->getBean());

		$ct->getManager()->afterSave();

		$eventData = new EventData();
		$eventData['content'] = $ct;
		$this->app['event']->trigger('Blocky::contentSaved', $eventData);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getField($type)
	{
		return $this->fieldTypes[$type];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getPermissions()
	{
		$permissions = ['admin'];

		foreach($this->contentTypes as $ct)
			foreach($ct->getPermissions() as $p)
				if(!in_array($p, $permissions))
					$permissions[] = $p;

		foreach($this->app['config']['backend_menu'] as $menu) {
			if(array_key_exists('permission', $menu))
				$permissions[] = $menu['permission'];
		}
		return $permissions;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function addContentType($slug, $data)
	{
		$ct = new ContentType($this->app);
		$ct->setName($data['name']);
		$ct->setSingularName($data['singular_name']);
		$ct->setSlug($slug);

		foreach($data['fields'] as $inputName => $options) {
			$ct->addField($inputName, $options);
		}

		unset($data['name']);
		unset($data['singular_name']);
		unset($data['fields']);
		$ct->setOptions($data);

		$this->contentTypes[$slug] = $ct;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContentTypes()
	{
		return $this->contentTypes;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContentType($slug)
	{
		return $this->contentTypes[$slug];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function addFieldType(SimpleFieldInterface $field)
	{
		$this->fieldTypes[$field->getName()] = $field;
		$this->fieldTypes[$field->getName()]->register($this->app);
	}
} // END class Service