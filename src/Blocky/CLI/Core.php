<?php

namespace Blocky\CLI;

use Blocky\Extension\CommandProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Core
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $app;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $commands;


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(&$app)
	{
		$this->app = $app;
		$this->commands = [];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		foreach($this->app['extension']->extensions as $ex) {
			if($ex instanceof CommandProvider) {
				$commands = $ex->getCommands();

				foreach($commands as &$c) {
					$c->attach($this->app, $this);
					$this->commands[str_replace("Extension", "", $ex->getName())."::".$c->getName()] = $c;
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
	public function exec($command, $params = [])
	{
		if(!array_key_exists($command, $this->commands)) {
			die("Unrecognized command!");
		}

		$command = $this->commands[$command];

		$command->exec($params);
	}
} // END class SimpleCommand