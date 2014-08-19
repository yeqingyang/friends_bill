<?php
class User{
	const tbl_name="t_user";
	public static function getUsers(){
		$data=new Data();
		$query="select * from ".self::tbl_name.";";
		$ret = $data->query($query);
		return $ret;
	}
	
	public static function addUser($user){
		$data=new Data();
		$cols = implode(',',array_keys($user));
		$values = implode(',',$user);
		$query="insert into ".self::tbl_name."(".$cols.") "." values"."(".$values.");";
		echo $query;
		$ret = $data->query($query);
		return $ret;
	}

}
