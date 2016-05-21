<?php

namespace Blocky\Backend\Controller;

use Blocky\Controller\SimpleController;
use Blocky\Content\Content;
use Blocky\Content\Exception\ContentSaveException;
/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ScheduleController extends SimpleController
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function tick()
	{
		$schedules = $this->app['schedule']->getPriorTo(date("Y-m-d H:i:s"));
		foreach($schedules as $s) {
			$this->app['schedule']->apply($s);
		}

		die("OK");
	}


} // END class BackendController