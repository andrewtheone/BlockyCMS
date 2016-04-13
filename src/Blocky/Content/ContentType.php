<?php

namespace Blocky\Content;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ContentType
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $app;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $slug;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $name;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $singularName;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $fields;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $options;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $acl;


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($app)
	{
		$this->app = $app;

		$this->options = [
			'backend_content_list' => '@backend/listcontent.twig',
			'backend_list_header' => '@backend/_list_header.twig',
			'backend_list_footer' => '@backend/_list_footer.twig',
			'backend_list_item' => '@backend/_list_item.twig',
			'record_view' => $this->app['config']['view']['record_view'],
			'list_view' => $this->app['config']['view']['record_list'],
			'localizable' => false,
			'show_menu' => true,
			'syncable' => true,
			'custom_query' => []
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContents($where = '', $whereArgs = [])
	{
		return $this->app['content']->getContents($this->getSlug(), $where, $whereArgs);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setSlug($slug)
	{
		$this->slug = $slug;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return $this->name;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setSingularName($singularName)
	{
		$this->singularName = $singularName;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getSingularName()
	{
		return $this->singularName;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function addField($inputName, $data)
	{
		$this->fields[$inputName] = $data;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getField($inputName)
	{
		return (array_key_exists($inputName, $this->fields)?$this->fields[$inputName]:null);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getGroup($name)
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getGroups()
	{
		$grouped = [];

		$group = "Adatok";
		$group_slug = "adatok";
		foreach($this->fields as $inputName => $data) {

			if( array_key_exists('group', $data) ) {
				$group = $data['group'];
				$group_slug = $this->slugify($data['group']);
			}

			if(!array_key_exists($group_slug, $grouped)) {
				$grouped[$group_slug] = ['name' => $group, 'fields' => []];
			}

			$grouped[$group_slug]['fields'][$inputName] = $data;
		}

		return $grouped;
	}

	public function slugify($text) {
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text))
	  {
	    return 'n-a';
	  }

	  return $text;
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFieldType($inputName)
	{
		if(!array_key_exists($inputName, $this->fields))
			return null;
		return $this->app['content']->getField( $this->fields[$inputName]['type'] );
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setOptions($options)
	{
		
		if(array_key_exists('acl', $options)) {
			$acl = $options['acl'];
			unset($options['acl']);

			$this->setPermissions($acl);
		} else {
			$this->setPermissions();
		}

		foreach($options as $k => $v) {
			$this->options[$k] = $v;
		}
		//$this->options = array_merge($this->options,  $options);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getOption($key, $default = null)
	{
		return array_key_exists($key, $this->options)?$this->options[$key]:$default;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function hasLocales()
	{
		return array_key_exists('localizable', $this->options)?$this->options['localizable']:false;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setPermissions($acl = null)
	{
		if($acl == null) {
			$acl = [$this->getSlug()."_view", $this->getSlug()."_edit"];
		}

		$this->acl = $acl;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getReadPermission()
	{
		return $this->acl[0];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getWritePermission()
	{
		return $this->acl[1];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getPermissions()
	{
		return $this->acl;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __get($key)
	{
		return $this->getOption($key);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __isset($key)
	{
		return array_key_exists($key, $this->options);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getSlugName()
	{
		return $this->getFieldByType('slug');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFieldsByType($type)
	{
		$fields = [];
		foreach($this->fields as $key => $options) {
			if($options['type'] == $type)
				$fields[] = $key;
		}

		return $fields;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFieldByType($type)
	{
		foreach($this->fields as $key => $options) {
			if($options['type'] == $type)
				return $key;
		}
	}
} // END class SimpleField