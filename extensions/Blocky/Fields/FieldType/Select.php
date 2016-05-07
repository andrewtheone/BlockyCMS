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
					$keyField = (array_key_exists('keyKey', $options)?$options['keyKey']:'id');
					$valueField = (array_key_exists('valueKey', $options)?$options['valueKey']:'title');
					$foreignContentType = $options['foreign'];

					$relationName = $content->getContentType()->getSlug()."_".$foreignContentType;
					if(array_key_exists('relation_name', $options)) {
						$relationName = $options['relation_name'];
					}

					$keyValue = $data['content']->getID();
					if($keyField != 'id') {
						$keyValue = $data['content']->getValue($keyField);
					}

					$ct = $app['content']->getContentType($foreignContentType);

					$beans = R::find('relations', '`from` = ? and from_id = ? and `to` = ? and `relation` = ?', [
						$content->getContentType()->getSlug(),
						$keyValue,
						$ct->getSlug(),
						$relationName
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
						$bean->from_id = $keyValue;
						$bean->to = $ct->getSlug();
						$bean->to_id = $id;
						$bean->relation = $relationName;
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

			$keyField = (array_key_exists('keyKey', $options)?$options['keyKey']:'id');
			$valueField = (array_key_exists('valueKey', $options)?$options['valueKey']:'title');

			$keyValue = $content->getID();
			if($keyField != 'id') {
				$keyValue = $content->getValue($keyField);
			}

			$foreignContentType = $options['foreign'];

			$relationName = $content->getContentType()->getSlug()."_".$foreignContentType;
			if(array_key_exists('relation_name', $options)) {
				$relationName = $options['relation_name'];
			}

			$ct = $this->app['content']->getContentType($foreignContentType);

			$beans = R::find('relations', "(`from` = :from and from_id = :from_id and `to` = :to and `relation` = :relation)"/* or (`from` = :fromB and to_id = :to_id and `to` = :toB)"*/, [
				'from' =>$content->getContentType()->getSlug(),
				'from_id' => $keyValue,
				'to' => $ct->getSlug(),
				'relation' => $relationName/*,
				'fromB' => $ct->getSlug(),
				'to_id' => $keyValue,
				'toB' => $content->getContentType()->getSlug()*/
			]);

			$results = [];
			$ids = [];
			foreach($beans as $bean) {
				$ids[] = $bean->to_id;
			}

			$d = $this->app['content']->getContents($ct->getSlug(), 'where '.$keyField.' in ('.R::genSlots( $ids ).')', $ids);
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