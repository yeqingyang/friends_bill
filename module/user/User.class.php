<?php
class User{
	const tbl_name="t_user";
	public static function getUsers($selectFields,$wheres){
		Logger::info("start getUsers");
		$ret = UserDao::getInfo($selectFields, $wheres);
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
