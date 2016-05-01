<?php

namespace Blocky\Content;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Content
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $contentType;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $bean;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $manager;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(&$contentType, &$bean)
	{
		$this->contentType = $contentType;
		$this->bean = $bean;

		$contentManager = $contentType->getOption('manager', $this->getContentType()->app['config']['default_content_manger']);
		$this->manager = new $contentManager($this);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getManager()
	{
		return $this->manager;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getValue($inputName)
	{
		$val = isset($this->bean->{$inputName})?$this->bean->{$inputName}:'';
		
		$fieldType = $this->getContentType()->getFieldType($inputName);

		if(!$fieldType) {
			return $this->getManager()->formatValue($inputName, $val);
		}

		return $this->getManager()->formatValue($inputName, $fieldType->extractValue($this, $val, $this->getContentType()->getField($inputName)));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function fromArray($data)
	{
		$this->getManager()->beforeFromArray($data);
		if(is_array($data)) {
			$fields = $this->getContentType()->getFields();
			foreach($fields as $inputName => $options) {
				$options['inputName'] = $inputName;
				if(array_key_exists($inputName, $data)) {
					$val = $this->getContentType()->getFieldType($inputName)->processInput($this, $data[$inputName], $options);
					if($val != null) {
						$this->bean->{$inputName} = $val;
					}
				} else {
					if(!isset($this->bean->{$inputName}))
						$this->bean->{$inputName} = $this->getContentType()->getFieldType($inputName)->processInput($this, null, $options);
				}
			}
		}
		$this->getManager()->afterFromArray($data);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __get($key)
	{
		return $this->getValue($key);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
    public function __isset($key)
    {
    	return isset($this->bean->{$key});
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function toArray()
    {
    	$result = [];
		$fields = $this->getContentType()->getFields();
		foreach($fields as $inputName => $options) {
			$result[$inputName] = $this->getValue($inputName);
		}

		return $result;
    }

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function &getBean()
	{
		return $this->bean;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getID()
	{
		return $this->bean->id;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getSlug()
	{
		return $this->getValue('slug');
	}
} // END class SimpleField