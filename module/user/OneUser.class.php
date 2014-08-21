<?php
class OneUser{
	private $uid;
	private $uname;
	private $status;
	private $create_time;
	private $dtime;
	private $birthday;
	private $gold_num;
	private $reward_point;
	private $last_login_time;
	private $online_accum_time;
	
	public function OneUser($name){
		$this->uname = $name;
	}
	
	public function setUid($uid){
		$this->uid=$uid;
	}
	
	public function getUid($uid){
		return $this->uid;
	}
	
	public function setCreateTime(){
		$this->create_time = Util::getTime();
	}
	
	public function getAllInfo(){
		$info=array();
		if(isset($this->uid)){
			$info[UserDef::SQL_USER_UID]=$this->uid;
		}
		if(isset($this->uname)){
			$info[UserDef::SQL_USER_UNAME]=$this->uname;
		}
		if(isset($this->status)){
			$info[UserDef::SQL_USER_STATUS]=$this->status;
		}
		if(isset($this->create_time)){
			$info[UserDef::SQL_USER_CREATE_TIME]=$this->create_time;
		}
		if(isset($this->birthday)){
			$info[UserDef::SQL_USER_BIRTHDAY]=$this->birthday;
		}
// 		if(isset($this->uid)){
// 			$info[UserDef::SQL_USER_UID]=$this->uid;
// 		}
// 		if(isset($this->uid)){
// 			$info[UserDef::SQL_USER_UID]=$this->uid;
// 		}
// 		if(isset($this->uid)){
// 			$info[UserDef::SQL_USER_UID]=$this->uid;
// 		}
// 		if(isset($this->uid)){
// 			$info[UserDef::SQL_USER_UID]=$this->uid;
// 		}
// 		if(isset($this->uid)){
// 			$info[UserDef::SQL_USER_UID]=$this->uid;
// 		}
		
	}
}