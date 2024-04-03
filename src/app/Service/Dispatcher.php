<?php

namespace Service;

use Phalcon\Events\Manager;

class Dispatcher extends \Phalcon\Mvc\Dispatcher
{
	public function __construct()
	{
		$this->setEventsManager(new Manager());
	}
}