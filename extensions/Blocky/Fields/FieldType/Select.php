<?php

namespace Blocky\Fields\FieldType;

use Blocky\Content\SimpleField;
use Blocky\Content\SimpleFieldInterface;
use Blocky\Content\Content;
use RedBeanPHP\R as R;
/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Select extends SimpleField implements SimpleFieldInterface
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate() {
		return "@fields/select.twig";
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function processInput(Content $content, $input, $options) {
		if(array_key_exists('foreign', $options)) {

			$app = &$this->app;
			$this->app['event']->on('Blocky::contentSaved', function($data) use(&$content, $input, $options, $app) {

				if($content == $data['content']) {
					$foreignContentType = $options['foreign'];

					$ct = $app['content']->getContentType($foreignContentType);

					$beans = R::find('relations', '`from` = ? and from_id = ? and `to` = ?', [
						$content->getContentType()->getSlug(),
						$data['content']->getID(),
						$ct->getSlug()
					]);

					foreach($beans as $bean) {
						R::trash($bean);
					}

					if(!is_array($input)) {
						$input = [$input];
					}

					foreach($input as $id) {
						$bean = R::dispense('relations');
						$bean->from = $content->getContentType()->getSlug();
						$bean->from_id = $data['content']->getID();
						$bean->to = $ct->getSlug();
						$bean->to_id = $id;
						R::store($bean);
					}
				}
			});

			return null;
		}

		return $input;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function extractValue(Content $content, $value, $options) {
		if(array_key_exists('foreign', $options)) {
			$foreignContentType = $options['foreign'];

			$ct = $this->app['content']->getContentType($foreignContentType);

			$beans = R::find('relations', "`from` = :from and from_id = :from_id and `to` = :to", [
				'from' =>$content->getContentType()->getSlug(),
				'from_id' =>$content->getID(),
				'to' =>$ct->getSlug()
			]);

			$results = [];
			$ids = [];
			foreach($beans as $bean) {
				$ids[] = $bean->to_id;
			}

			$d = $this->app['content']->getContents($ct->getSlug(), 'where id in ('.R::genSlots( $ids ).')', $ids);
			return $d;
		}

		return $value;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName() {
		return "select";
	}

} // END class Text