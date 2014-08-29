<?php

class Users extends Phalcon\Mvc\Model
{
	public $name;
	public $email;
	private $app;
	private $table = 't_user';
	
	function __construct(){
		//Register an autoloader
		$loader = new \Phalcon\Loader();
		$loader->registerDirs(
				array(
						'../app/controllers/',
						'../app/models/',
						'../lib/'
				)
		)->register();
		
		//Create a DI
		$di = new Phalcon\DI\FactoryDefault();
		
		//Set the database service
		$di->set('db', function(){
			return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
					"host" => "192.168.1.41",
					"username" => "root",
					"password" => "",
					"dbname" => "friends_bill",
					"charset" => "utf8"
			));
		});
		$this->app = new \Phalcon\Mvc\Application();
    	$this->app->setDI($di);
	}
	
	public function save(){
		$phql= "insert into :table: ('uname','email') value (:uname:, :email:);";
		$status = $this->app->modelsManager->executeQuery($phql, 
				array('table'=>$this->table, 
						'uname'=>$this->name,
						'email'=>$this->email
		));
		//Create a response
		$response = new Phalcon\Http\Response();
		//Check if the insertion was successful
		if ($status->success() == true) {
			//Change the HTTP status
			$response->setStatusCode(201, "Created");
			$robot->id = $status->getModel()->id;
			$response->setJsonContent(array(’status’ => ’OK’, ’data’ => $robot));
		} else {
			//Change the HTTP status
			$response->setStatusCode(409, "Conflict");
			//Send errors to the client
			$errors = array();
			foreach ($status->getMessages() as $message) {
				$errors[] = $message->getMessage();
			}
			$response->setJsonContent(array(’status’ => ’ERROR’, ’messages’ => $errors));
		}
		return $response;
	}
}
