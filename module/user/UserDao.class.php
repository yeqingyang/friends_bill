<?php
class UserDao{
	private $tbl_name='t_user';
	public static function getInfo($selectFields, $wheres, $limit=100){
		$data=new Data();
		$data->select($selectFields);
		$data->from($this->tbl_name);
		$data->where($wheres);
		$data->limit($limit);
		$ret = $data->query();
		return $ret;
	}
}