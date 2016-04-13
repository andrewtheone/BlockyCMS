<?php

namespace Blocky\Logger\Content;

use Blocky\Backend\Content\BaseContentManager as BCM;
/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class AdminLogManager extends BCM
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function formatValue($inputName, $value)
	{
		switch($inputName) {
			case "content":
				$contentType = $this->content->getValue('contenttype');
				$slug = $this->content->getValue('content_slug');

				$hack_app = $this->content->getContentType()->app;
				$contents = $hack_app['content']->getContents($contentType, "where slug = ?", [$slug]);
				$content = $contents[0];
				return $content->getValue($content->getContentType()->getFieldByType('text'));
			break;
			default:
				return $value;
			break;
		}
	}

} // END class BaseContentManager