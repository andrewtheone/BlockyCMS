<?php

namespace Blocky\Forms\Controller;

use Blocky\Controller\SimpleController;
use Blocky\Content\Content;
use Blocky\Content\Exception\ContentSaveException;
use Blocky\Forms\BaseFormManager;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FormController extends SimpleController
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function process()
	{
		$formName = $this->request->getAttribute('form');
		$refer = $this->request->getAttribute('refer');
		$values = $this->request->getAttribute('form_data');

		$form = $this->app['forms']->getForm($formName);
		
		$content = null;
		$contentType = $this->app['content']->getContentType($form['contenttype']);

		$managerClass = array_key_exists('manager', $form)?$form['manager']:"Blocky\Forms\BaseFormManager";
		$manager = new $managerClass($this->app, $formName, $refer, $form, $values, $contentType, $content);

		try {
			$manager->onProcess(BaseFormManager::PROCESS_INITIAL);

			if(array_key_exists('store', $form) && ($form['store'])) {
				$bean = $this->app['storage']->createBean($contentType->getSlug());
				
				if( ($content = $manager->getContent()) == null) { 
					$content = new Content($contentType, $bean);
					$content->fromArray($values);
				}

				if($contentType->hasLocales()) {
					$content->bean->locale = $this->app['locale']['locale'];
				}
				$content->bean->site = "both";

					$manager->setContent($content);
					$manager->onProcess(BaseFormManager::PROCESS_BEFORE_STORE);
					$this->app['content']->storeContent($content);
					$manager->setContent($content);
					$manager->onProcess(BaseFormManager::PROCESS_AFTER_STORE);

			}


			if(array_key_exists('email', $form)) {
				$template = array_key_exists('template', $form['email'])?$form['email']['template']:'@forms/email.twig';

				$data = [
					'values' => $values,
					'formName' => $formName,
					'form' => $form,
					'email' => [
						'subject' => $form['email']['subject'],
						'to' => $form['email']['to'],
						'template' => $template
					],
					'content' => $content,
					'contentType' => $contentType,
				];

				$res = call_user_func([$manager, 'onProcess'], BaseFormManager::PROCESS_BEFORE_EMAIL, $data);
				if($res && is_array($res)) {
					$data = $res;
				}
				$this->app['mail']->send($data['email']['to'], $data['email']['subject'], $data['email']['template'], $data);
				$manager->onProcess(BaseFormManager::PROCESS_AFTER_EMAIL);
			}
		} catch(ContentSaveException $ex) {
			$manager->onProcess(BaseFormManager::PROCESS_VALIDATION_ERROR, $ex);


			$this->app['session']->setFlashMessage('error', $ex->getMessage());
			$this->app['path']->redirect($refer);
			exit();
			return;
		}

		$this->app['path']->redirect($refer);
		exit();
		return;
	}

} // END class BackendController