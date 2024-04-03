<?php

namespace Service;

use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Session\Manager;

class Di
{
	/** @var Di */
	protected static $instance;

	/** @var \Phalcon\Di */
	protected $phalconDi;

	protected $instances = [];

	/**
	 * @return static
	 */
	public static function get()
	{
		if (!static::$instance) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	public function getPhalconDi(): \Phalcon\Di
	{
		return $this->phalconDi;
	}

	private function __construct()
	{
		$this->phalconDi = \Phalcon\Di::getDefault();
	}

	public function _get($class)
	{
		return $this->instances[$class] ?? $this->instances[$class] = new $class;
	}

	/**
	 * @return Request
	 */
	public function getRequest()
	{
		return $this->phalconDi->get('request');
	}

	/**
	 * @return Response
	 */
	public function getResponse()
	{
		return $this->phalconDi->get('response');
	}

	/**
	 * @return Router
	 */
	public function getRouter()
	{
		return $this->phalconDi->get('router');
	}

	/**
	 * @return Dispatcher
	 */
	public function getDispatcher()
	{
		return $this->phalconDi->get('dispatcher');
	}

	/**
	 * @return Manager
	 */
	public function getSession()
	{
		return $this->phalconDi->get('session');
	}

	/**
	 * @return Manager
	 */
	public function getCookie()
	{
		return $this->phalconDi->get('cookies');
	}

	/**
	 * @return \Phalcon\Cache
	 */
	public function getModelsCache()
	{
		return $this->phalconDi->get('modelsCache');
	}

    public function getMongo(): Mongo
    {
        return self::_get(Mongo::class);
    }
}