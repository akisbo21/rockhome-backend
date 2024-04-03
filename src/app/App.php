<?php


use Service\Di;

class App extends AppBase
{
	protected function registerRoutes()
	{
        $this->router->add('/', 'Controller\\Index::index');
		$this->router->add('/login', 'Controller\\Auth::login');
		$this->router->add('/ingatlan/lista', 'Controller\\List::index');
        $this->router->add('/ingatlan/adatlap/{id}', 'Controller\\Show::index');
	}

	protected function getPhalconServicesClasses()
	{
        $di = Di::get();

        $services = parent::getPhalconServicesClasses();

        \Phalcon\Mvc\Model::setup([
            'notNullValidations' => false
        ]);

		return $services;
	}
}