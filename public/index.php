<?php

try {

	//Register an autoloader
	$loader = new \Phalcon\Loader();
	$loader->registerDirs(
			array(
				'../app/controllers/',
				'../app/models/',
				'../app/library/',
				'../lib/',
				'../def/',
				)
			)->register();

	//Create a DI
	$di = new Phalcon\DI\FactoryDefault();

	//Set the database service
	$di->set('db', function(){
			return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
					"host" => "192.168.1.41",
					"username" => "root",
					"password" => "123456",
					"dbname" => "friends_bill",
					"charset" => "utf8"
					));
			});

	//Setting up the view component
	$di->set('view', function(){
			$view = new \Phalcon\Mvc\View();
			$view->setViewsDir('../app/views/');
			$view->registerEngines(array(
					".volt" => 'Phalcon\Mvc\View\Engine\Volt',
					".phtml" => 'Phalcon\Mvc\View\Engine\Php',
					".php" => 'Phalcon\Mvc\View\Engine\Php'
			));
			return $view;
			});

	$di->set('url', function(){
			$url = new \Phalcon\Mvc\Url();
			$url->setBaseUri('/');
			return $url;
			});
	$di->set('session', function(){
			$session = new Phalcon\Session\Adapter\Files();
			$session->start();
			return $session;
		});
	Logger::init("../log/fb.log", 1);
	Logger::info('start');

	//Handle the request
	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);
	echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
	echo "PhalconException: ", $e->getMessage();
}
