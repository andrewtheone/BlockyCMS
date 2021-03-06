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
		$type = $this->request->getAttribute('type');

		$form = $this->app['forms']->getForm($formName);
		
		$content = null;
		$contentType = $this->app['content']->getContentType($form['contenttype']);

		$managerClass = array_key_exists('manager', $form)?$form['manager']:"Blocky\Forms\BaseFormManager";
		$manager = new $managerClass($this->app, $formName, $refer, $form, $values, $contentType, $content);

		try {
			$manager->onProcess(BaseFormManager::PROCESS_INITIAL);

			if(array_key_exists('store', $form) && ($form['store'])) {
				$content = $manager->getContent();
				if( $content == null) { 
					$bean = $this->app['storage']->createBean($contentType->getSlug());
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

			$this->app['session']->setFlashMessage('forms.errors.'.$formName, ['field' => $ex->getField(), 'message' => $ex->getMessage()]);
			
			if($type == "ajax") {
				$data = $manager->onProcess(BaseFormManager::PROCESS_GET_RESPONSE_JSON);
				if(!is_array($data))
					$data = ['success' => 0, 'redirect' => '__none__', 'messages' => [], 'errors' => $this->app['session']->getFlashMessages('forms.errors.'.$formName), '__fallback' => true];

				$this->json($data);
			}
			$this->app['session']->setFlashMessage('forms.data.'.$formName, $values);

			$route = $manager->onProcess(BaseFormManager::PROCESS_GET_REDIRECT_ROUTE);
			if(!is_string($route))
				$route = $refer;
			$this->app['path']->redirect($route);

			exit();
			return;
		}
		
		$manager->onProcess(BaseFormManager::PROCESS_VALIDE);
		
		if($type == "ajax") {
			$data = $manager->onProcess(BaseFormManager::PROCESS_GET_RESPONSE_JSON);
			if(!is_array($data))
				$data = [
					'success' => 1,
					'redirect' => (array_key_exists('success_redirect', $form)?$form['success_redirect']:'__none__'),
					'messages' => (array_key_exists('success_message', $form)?[$form['success_message']]:[]),
					'errors' => [],
					'__fallback' => true
				];
			$this->json($data);
		}

		$this->app['session']->setFlashMessage('forms.data.'.$formName, null);
		
		if(array_key_exists('success_message', $form)) {
			$this->app['session']->setFlashMessage('success', $form['success_message']);
		}

		$route = $manager->onProcess(BaseFormManager::PROCESS_GET_REDIRECT_ROUTE);
		if(!is_string($route)) {
			if(array_key_exists('success_redirect', $form)) {
				if($form['success_redirect'] != "__self__") {
					$route = $form['success_redirect'];
				} 
			}

			if(!is_string($route))
				$route = $refer;
		}

		$this->app['path']->redirect($route);
		exit();
		return;
	}

} // END class BackendController