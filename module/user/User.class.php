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
		Logger::info("start addUser");
		$user->setCreateTime();
		$values = $user->getAllInfo();
		$ret = UserDao::insert($values);
		Logger::info("end adduser");
		return $ret;
	}

}
