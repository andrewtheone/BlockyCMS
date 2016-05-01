<?php

namespace PixelVision\ECommerce\Content;

use Blocky\Content\BaseContentManager as BCM;
/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class CategoryManager extends BCM
{


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function beforeFromArray(&$data)
	{
		if( ( array_key_exists('parent', $data) && is_numeric($data['parent']) ) ){
			$parent = $data['parent'];
			$parentContent = $this->content->getContentType()->getContents("where id = ?", [$parent]);
			$fqnParts = explode(" / ", $parentContent[0]->getValue('fqn'));
			$fqnParts[] = $data['title'];
			$data['fqn'] = implode(" / ", $fqnParts);
		} else {
			$data['fqn'] = $data['title'];
		}
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
} // END class CategoryManager