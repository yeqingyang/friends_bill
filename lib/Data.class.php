<?php
define(ROOT, dirname(__FILE__));
include_once ROOT.'/lib/Mysql.def.php';
class Data{
	private $mysqli;
	public function Data(){
		$this->mysqli=new mysqli(MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, "", MysqlDef::MYSQL_DB_NAME);
	}
	
	public function init(){
		$this->mysqli=new mysqli(MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, "", MysqlDef::MYSQL_DB_NAME);
	}
	
	public function query($query){
		if(empty($this->mysqli)){
			$this->init();
		}
		$result=$this->mysqli->query($query);
		$return=array();
		if ($result) {
			if($result->num_rows>0){                                               //�жϽ�������е���Ŀ�Ƿ����0
				while($row =$result->fetch_array() ){                        //ѭ�����������еļ�¼
					$return[]=$row;
				}
			}
		}
		$result->free();
		$this->mysqli->close();
		return 'ok';
	}
	function __destruct() {
		if(!empty($this->mysqli)){
			$this->mysqli->close();
		}
	}
}