<?php

use Phalcon\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Loader;

abstract class AppBase extends \Phalcon\Mvc\Application
{
    public function __construct(DiInterface $dependencyInjector = null)
    {
        $this->registerLoader();
        parent::__construct($this->createDi());
        $this->registerRoutes();
    }

    abstract protected function registerRoutes();

    protected function registerLoader()
    {
        (new Loader())
            ->registerDirs([
                '/srv/app'
            ])
            ->register();
    }

    protected function getPhalconServicesClasses()
    {
        return [
            'request'       => \Phalcon\Http\Request::class,
            'response'      => \Phalcon\Http\Response::class,
            'router'        => \Phalcon\Mvc\Router::class,
            'view'          => \Phalcon\Mvc\View::class,
            'dispatcher'    => \Service\Dispatcher::class,
        ];
    }

    protected function createDi()
    {
        $di = new Di();

        foreach ($this->getPhalconServicesClasses() as $name => $service) {
            if (is_string($service)) {
                $di->setShared($name, new $service);
            }
            elseif (is_callable($service)) {
                $di->setShared($name, $service);
            }
        }

        return $di;
    }

    public function handle($uri = null)
    {
        parent::handle($_SERVER["REQUEST_URI"])->send();
    }
}