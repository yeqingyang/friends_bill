<?php
use Phalcon\Logger\Adapter\File as FileAdapter;
class Group extends Phalcon\Mvc\Model
{
	public $name;
	private $app;
	private $table = 't_group';
	private $logger;	
	public function log(){
		$logger = new FileAdapter("app/logs/test.log");
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
