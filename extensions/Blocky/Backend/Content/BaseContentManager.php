<?php

namespace Blocky\Backend\Content;

use Blocky\Content\BaseContentManager as BCM;
/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BaseContentManager extends BCM
{


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


} // END class BaseContentManager