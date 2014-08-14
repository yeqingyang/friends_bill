<?php
class User{
	private $tbl_name="t_user";
	public static function getUsers(){
		$data=new Data();
		$query="select * from $this->tbl_name;";
		$ret = $data->query($query);
		return $ret;
	}
	
	public static function addUser($user){
		$data=new Data();
		$query="insert into $this->tbl_name values";
		$query .= "(".$user['uid'].",".$user['name'].");";
		$ret = $data->query($query);
		return 'ok';
	}
}