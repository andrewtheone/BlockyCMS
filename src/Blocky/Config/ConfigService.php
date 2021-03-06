<?php

namespace Blocky\Config;

use Blocky\BaseService;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ConfigService extends BaseService implements \ArrayAccess
{

    private $values = array();

    /**
     * undocumented class variable
     *
     * @var string
     **/
    public $mobileDetect;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		$this->values = YamlWrapper::parse($this->app['path']->to('config', 'config.yml'));
        if(file_exists($this->app['path']['root']."/app/cache/installed.yml") && ($this->values['environment'] == "prod")) {
            $this->values['installed'] = YamlWrapper::parse($this->app['path']['root']."/app/cache/installed.yml");
        } else {
            $this->values['installed'] = [];
        }
		$this->app['path']['theme'] = $this->app['path']['themes']."/".$this->values['theme'];
        $this->app['path']['theme_url'] = $this->values['host']."/themes/".$this->values['theme'];
        $this->app['path']['files_url'] = $this->values['host']."/files";
		$this->values['routes'] = YamlWrapper::parse($this->app['path']->to('config', 'routes.yml'));
        $this->values['locales'] = YamlWrapper::parse($this->app['path']->to('config', 'locales.yml'));
        if(!array_key_exists('backend_menu', $this->values)) {
            $this->values['backend_menu'] = [];
        }

        $this->values['menu'] = YamlWrapper::parse($this->app['path']->to('config', 'menu.yml'));
        $this->mobileDetect = new MobileDetect();
	}

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function isMobile()
    {
        return $this->mobileDetect->isMobile();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function isTablet()
    {
        return $this->mobileDetect->isTablet();
    }

    public function offsetSet($id, $value)
    {
    	$this->values[$id] = $value;
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