<?php

class Users extends Phalcon\Mvc\Model
{
	public $name;
	public $email;
	private $app;
	private $table = 'users';
	
	public function init(){
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
					"host" => "127.0.0.1",
					"username" => "root",
					"password" => "",
					"dbname" => "friends_bill",
					"charset" => "utf8"
			));
		});
		$this->app = new \Phalcon\Mvc\Application();
    	$this->app->setDI($di);
	}
	
	public function save($data=NULL,$whitelist=NULL){
		//$this->init();
		$phql= "insert into $this->table (uname, email) "." values ( :v_uname: , :v_email:)";
		$status = $this->getModelsManager()->executeQuery($phql, 
				array( 
						'v_uname'=>$this->name,
						'v_email'=>$this->email,
		));
		//Create a response
		$response = new Phalcon\Http\Response();
		//Check if the insertion was successful
		if ($status->success() == true) {
			//Change the HTTP status
			$response->setStatusCode(201, "Created");
			$robot->id = $status->getModel()->id;
			$response->setJsonContent(array(’status’ => ’OK’, ’data’ => 'ok'));
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
		return true;
	}
}
