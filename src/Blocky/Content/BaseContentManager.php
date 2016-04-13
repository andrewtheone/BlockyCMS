<?php

namespace Blocky\Content;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BaseContentManager
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(&$content)
	{
		$this->content = $content;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function validate()
	{
		$fields = $this->content->getContentType()->getFields();

		foreach($fields as $inputName => $options) {

			$value = $this->content->getValue($inputName);

			if(array_key_exists('validators', $options)) {
				$validators = $options['validators'];
				
				if(array_key_exists('regexp', $validators))
					$validators = [$validators];

				foreach($validators as $validator) {
					if($validator['regexp'] == 'empty') {
						if(strlen($value) == 0) 
							throw new Exception\ContentSaveException($validator['message']);
					} else 
					if(preg_match($validator['regexp'], $value) !== 1) {
						throw new Exception\ContentSaveException($validator['message']);
					}
				}
			}
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function beforeFromArray(&$data)
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function formatValue($inputName, $value)
	{
		return $value;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function afterFromArray(&$data)
	{
		if(array_key_exists('locale', $data)) {
			$this->content->bean->locale = $data['locale'];
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function beforeSave()
	{
		$this->validate();

		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function afterSave()
	{
		return true;
	}
} // END class BaseContentManager