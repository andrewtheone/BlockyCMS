<?php

namespace PixelVision\ECommerce\Content;

use Blocky\Content\BaseContentManager as BCM;
use RedBeanPHP\R as R;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ProductManager extends BCM
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function afterSave()
	{
		$beans = R::find("comproductcategories", "where product_id = ?", [$this->content->getID()]);
		foreach($beans as $b) {
			R::trash($b);
		}

		$cats = $this->content->getValue('categories');
		foreach($cats as $c) {
			$bean = R::dispense("comproductcategories");
			$bean->category_slug = $c->getValue('slug');
			$bean->product_id = $this->content->getID();
			R::store($bean);
		}
		return true;
	}
} // END class CategoryManager