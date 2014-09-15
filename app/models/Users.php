<?php
use Phalcon\Logger\Adapter\File as FileAdapter;
class Users extends Phalcon\Mvc\Model
{
	public $name;
	public $email;
	private $app;
	private $table = 'users';
	private $logger;	
	public function log(){
		$logger = new FileAdapter("app/logs/test.log");
	}
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
	//	$phql= "insert into $this->table (uname, email) "." values ( :v_uname: , :v_email:)";
		
		Logger::info("before get %s %s",$this->name, $this->email);
		//$query = new Phalcon\Mvc\Model\Query($phql, $this->getDI());
		$info=array();
		if(isset($this->name)){
			$info[UserDef::SQL_USER_UNAME]=$this->name;
			$info[UserDef::SQL_USER_EMAIL]=$this->email;
		}else{
			Logger::warning("no name");
			echo Phalcon\Tag::linkTo("index", "Back!");
		}
		$ret=UserDao::insert($info);
		if($ret != 'ok'){
			Logger::info('insert err');
		}
		return true;
	}
	
	public function getUsers($name=NULL){
		$selectfield=array(
			UserDef::SQL_USER_UID,
			UserDef::SQL_USER_UNAME,
			UserDef::SQL_USER_EMAIL,
			UserDef::SQL_USER_BIRTHDAY,
			UserDef::SQL_USER_GOLD_NUM,
			);
		$wheres = array(array(UserDef::SQL_USER_UID,'>',0));
		if(!empty($name)){
			$wheres[]=array( UserDef::SQL_USER_UNAME,'like',$name);
		}
		$ret=UserDao::getInfo($selectfield,$wheres);
		Logger::info('user %s', $ret);
		echo json_encode($ret);
	}
}
