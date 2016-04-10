<?php

namespace Blocky\Storage;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SessionService extends BaseService implements \ArrayAccess
{

    private $values = array();

	/**
	 * @inherit
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		session_start();
		if(!array_key_exists('_data', $_SESSION)) {
			$_SESSION['_data'] = [
				'_site' => 'prod'
			];
		}
		$this->values = $_SESSION['_data'];

		if(!$this->values)
			$this->values = [];
	}

	/**
	 * Sets a flash message under $type
	 *
	 * @return void
	 * @author 
	 **/
	public function setFlashMessage($type, $text)
	{
		$messages = $this->offsetGet('flash_message');
		if(!is_array($messages))
			$messages = [];
		if(!array_key_exists($type, $messages))
			$messages[$type] = [];

		$messages[$type][] = $text;
		$this->offsetSet('flash_message', $messages);
	}

	/**
	 * Retrieves and delete flash messages under  $type
	 *
	 * @return void
	 * @author 
	 **/
	public function getFlashMessages($type)
	{
		$messages = $this->offsetGet('flash_message');
		if(!is_array($messages))
			return [];
		if(!array_key_exists($type, $messages))
			return [];

		$flash = $messages[$type];
		unset($messages[$type]);
		$this->offsetSet('flash_message', $messages);

		return $flash;
	}

	/**
	 * Get a session value by key, or fallsback to default
	 *
	 * @return void
	 * @author 
	 **/
	public function get($key, $default = '')
	{
		return array_key_exists($key, $this->values)?$this->values[$key]:$default;
	}

    public function offsetSet($id, $value)
    {
    	$this->values[$id] = $value;
    	$_SESSION['_data'] = $this->values;
    }

    public function offsetGet($id)
    {
    	return (array_key_exists($id, $this->values)?$this->values[$id]:null);
    }


    public function offsetExists($id)
    {
        return array_key_exists($id, $this->values);
    }

    public function offsetUnset($id)
    {
    }

} // END class Service