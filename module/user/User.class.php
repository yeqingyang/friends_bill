<?php
class User{
	const tbl_name="t_user";
	public static function getUsers($selectFields){
		Logger::info("start getUsers");
		$data=new Data();
		$query="select * from ".self::tbl_name.";";
		$ret = $data->query($query);
		Logger::info("getUsers query end");
		return $ret;
	}
	
	public static function addUser($user){
		$data=new Data();
		$cols = implode(',',array_keys($user));
		$values = implode(',',$user);
		$query="insert into ".self::tbl_name."(".$cols.") "." values"."(\"".$values."\");";
		echo $query;
		$ret = $data->query($query);
		return $ret;
	}

}
