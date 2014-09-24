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
	
}
