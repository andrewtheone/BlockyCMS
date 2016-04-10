<?php

namespace Blocky\Mail;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class MailService extends BaseService
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $transportation;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $config;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $mailer;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		parent::boot();
		
		require_once __DIR__."/swift_init.php";

		$this->config = $this->app['config']['mail'];

		$this->transportation =  \Swift_SmtpTransport::newInstance($this->config['transportation']['smtp'], $this->config['transportation']['port']);


		if($this->config['transportation']['username']) {
			$this->transportation->setUsername($this->config['transportation']['username']);
			$this->transportation->setPassword($this->config['transportation']['password']);
		}

		$this->mailer = \Swift_Mailer::newInstance($this->transportation);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function send($to, $subject, $content = '', $data = null)
	{
		if(is_array($data)) {
			$data['app'] = &$this->app;

			$content = $this->app['view']->twig->render($content, $data);
		}

		// Create a message
		$message = \Swift_Message::newInstance($subject)
		  ->setFrom([$this->config['sender']['email'] => $this->config['sender']['name']])
		  ->setTo($to)
		  ->setBody($content, 'text/html');

		// Send the message
		$this->mailer->send($message);
	}
} // END class ControllerService