<?php
class UserDao{
	private static $tbl_name='t_user';
	public static function getInfo($selectFields, $wheres, $limit=100){
		$data=new Data();
		$data->select($selectFields);
		$data->from(self::$tbl_name);
		foreach($wheres as $where){
			$data->where($where);
		}
		$data->limit($limit);
		$ret = $data->query();
		return $ret;
	}
	
	public static function insert($values){
		$data=new Data();
		$data->insertIgnore(self::$tbl_name);
		$data->values($values);
		$ret = $data->query();
		return 'ok';
	}
}
