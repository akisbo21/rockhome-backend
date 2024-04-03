<?php

namespace Controller;

use Phalcon\Events\Event;
use Phalcon\Mvc\Controller;

class ApiController extends Controller
{
	protected $errors = [];
	protected $useRawResponse = false;

	public function initialize()
	{
		$this->dispatcher->getEventsManager()->attach('dispatch:beforeException', $this);
		$this->dispatcher->getEventsManager()->attach('dispatch:afterExecuteRoute', $this);
	}

	protected function addBadRequest($error)
	{
		$this->errors[] = $error;
	}

	public function beforeException(Event $event, $dispatcher, \Exception $exception)
	{
		$statusCode = 500;
		$error = [
			'message' => $exception->getMessage(),
			'code' => $exception->getCode(),
		];

		$this->response->setJsonContent([
			'code' => $statusCode,
			'error' => $error,
			'body' => ''
		]);

		$this->dispatcher->setReturnedValue($this->response);
		return false;
	}

	public function beforeExecuteRoute()
	{

	}

	public function afterExecuteRoute()
	{
		if ($this->useRawResponse) {
			return true;
		}

		if (!$this->response->getStatusCode()) {
			$this->response->setStatusCode(200);
		}

		$this->response->setJsonContent([
			'code' => $this->response->getStatusCode(),
			'error' => [],
			'body' => $this->dispatcher->getReturnedValue(),
		]);

		$this->dispatcher->setReturnedValue($this->response);
		return false;
	}
}