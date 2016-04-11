<?php

namespace Blocky\Setup\Controller;

use Blocky\Controller\SimpleController;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SetupController extends SimpleController
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setup()
	{
		$this->render("@setup/index.twig", []);
	}
} // END class SetupController