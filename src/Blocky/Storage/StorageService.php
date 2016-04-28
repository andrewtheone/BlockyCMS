<?php

namespace Blocky\Storage;

use Blocky\BaseService;
use RedBeanPHP\R as R;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class StorageService extends BaseService
{

	/**
	 * @inherit
	 * Sets up database connection based on config.yml
	 * @todo USE CONFIG.YML
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		R::setup( $this->app['config']['database'] );
	}

	/**
	 * Count beans
	 *
	 * @return void
	 * @author 
	 **/
	public function countBeans($bean, $where = '', $args = [])
	{
		return count(R::find($bean, $where, $args));
	}

	/**
	 * Fetching content
	 *
	 * @return void
	 * @author 
	 **/
	public function findBean($bean, $where = '', $args = [])
	{
		return R::find($bean, $where, $args);
	}

	/**
	 * Creating an empty bean
	 *
	 * @return void
	 * @author 
	 **/
	public function createBean($bean)
	{
		return R::dispense($bean);
	}

	/**
	 * Storing a bean
	 *
	 * @return void
	 * @author 
	 **/
	public function storeBean($bean)
	{
		R::store($bean);
	}
} // END class Service