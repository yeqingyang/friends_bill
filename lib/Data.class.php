<?php
#define("ROOT", dirname(dirname(__FILE__)));
require_once ROOT.'/def/Mysql.def.php';
class Data{
	private $mysqli;
	public function Data(){
		$this->mysqli=new mysqli(MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, "", MysqlDef::MYSQL_DB_NAME);
		if (mysqli_connect_error()) { 
			die('Connect Error (' . mysqli_connect_errno() . ') '
					. mysqli_connect_error()); 
		} 
	}

	public function init(){
		$this->mysqli=new mysqli(MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, "", MysqlDef::MYSQL_DB_NAME);
		if (mysqli_connect_error()) { 
			die('Connect Error (' . mysqli_connect_errno() . ') '
					. mysqli_connect_error()); 
		} 
	}

	public function query($query){
		if(empty($this->mysqli)){
			$this->init();
		}
		$result=$this->mysqli->query($query);
		$return=array();
		if ($result) {
			if($result->num_rows>0){                                               //判断结果集中行的数目是否大于0
				while($row =$result->fetch_array() ){                        //循环输出结果集中的记录
					$return[]=$row;
				}
			}
		}
		//	$result->free();
		$this->mysqli->close();
		return $return;
	}
	function __destruct() {
	}
}
