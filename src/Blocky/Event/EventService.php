<?php

namespace Blocky\Event;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class EventService extends BaseService
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	var $events = [];

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function subscribe($eventCode, $callback, $priority = 500)
	{
		if(!array_key_exists($eventCode, $this->events)) {
			$this->events[$eventCode] = [
				'listeners' => [],
				'isOrderDirty' => false
			];
		}

		$this->events[$eventCode]['listeners'][] = [
			'callback' => $callback,
			'priority' => $priority
		];

		if(count($this->events[$eventCode]['listeners']) != 1) {
			$this->events[$eventCode]['isOrderDirty'] = true;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return 
	 * @author 
	 **/
	public function on($eventCode, $cb, $priority = 500)
	{
		$this->subscribe($eventCode, $cb, $priority);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function trigger($eventCode, $data = null)
	{
		if(!array_key_exists($eventCode, $this->events))
			return $data;
		
		if($this->events[$eventCode]['isOrderDirty']) {
			$this->events[$eventCode]['listeners'] = $this->sortListeners($this->events[$eventCode]['listeners']);
			$this->events[$eventCode]['isOrderDirty'] = false;
		}

		foreach($this->events[$eventCode]['listeners'] as $listener) {
			if($data) {
				call_user_func_array($listener['callback'], [$data]);
			} else {
				call_user_func($listener['callback']);
			}
		}

		return $data;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function sortListeners($listeners)
	{
		usort($listeners, function($a, $b) {
			return $a['priority'] > $b['priority'];
		});

		return $listeners;
	}
} // END class Service