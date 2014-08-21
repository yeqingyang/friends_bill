<?php
class Data{
	private $mysqli;
	public function Data(){
		$this->mysqli=new mysqli(MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, "", MysqlDef::MYSQL_DB_NAME);
		if (mysqli_connect_error()) { 
			Logger::fatal('Connect Error %d %s', mysqli_connect_errno(), mysqli_connect_error()); 
		} 
	}

	public function init(){
		$this->mysqli=new mysqli(MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, "", MysqlDef::MYSQL_DB_NAME);
		if (mysqli_connect_error()) { 
			Logger::fatal('Connect Error %d %s', mysqli_connect_errno(), mysqli_connect_error()); 
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
					Logger::info("data $row %s",$row);
					$return[]=$row;
				}
			}
		}
		if(!empty($result)){
			$result->free();
		}
		$this->mysqli->close();
		return $return;
	}
	public function queryArrayToStirng($type, $query){

		$cols = implode(',',array_keys($user));
		$values = implode(',',$user);
		$query="";
		switch($type){
			case "insert";
		}
	}

	function __destruct() {
	}
}
