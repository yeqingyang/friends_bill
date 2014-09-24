<?php
class Group extends Phalcon\Mvc\Model
{
	public $gid;
	public $gname;
	public $uid;
	public $create_time;
	public $status;	
	public $va_user;
	
	public function getSource(){
		return 't_group';
	}
	
	public function initialize(){
		
	}
	public function getGroup($name=NULL){
		// Instantiate the Query
		$query = new Phalcon\Mvc\Model\Query("insert into * FROM Group ORDER BY game", $this->getDI());
		// Execute the query returning a result if any
		$ret = $query->execute();
		return $ret;
	}
	
}
