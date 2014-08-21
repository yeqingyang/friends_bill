<?php
class UserDao{
	private $tbl_name='t_user';
	public static function getInfo($selectfield,$wheres){
		$data=new Data();
		$selectfieldstring = implode(',', $selectfield);
		$query="select $selectfieldstring from ".self::tbl_name.";";
		$ret = $data->query($query);
	}
}