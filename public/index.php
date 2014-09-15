<?php

//require '../def/Classes.def.php';
//require_once '../lib/MyAutoLoad.class.php';
// global funciton to retrive $di
if (!function_exists("getDI")) {
	function getDI()
	{
		return \Phalcon\DI::getDefault();
		// return $GLOBALS['di'];
	}    
}
try {

	//Register an autoloader
	$loader = new \Phalcon\Loader();
	$loader->registerDirs(
			array(
				'../app/controllers/',
				'../app/models/',
				'../lib/',
				'../def/',
				)
			)->register();

	//Create a DI
	$di = new Phalcon\DI\FactoryDefault();

	//Set the database service
	$di->set('db', function(){
			return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
					"host" => "127.0.0.1",
					"username" => "root",
					"password" => "",
					"dbname" => "friends_bill",
					"charset" => "utf8"
					));
			});

	//Setting up the view component
	$di->set('view', function(){
			$view = new \Phalcon\Mvc\View();
			$view->setViewsDir('../app/views/');
			return $view;
			});

	$di->set('url', function(){
			$url = new \Phalcon\Mvc\Url();
			$url->setBaseUri('/');
			return $url;
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
