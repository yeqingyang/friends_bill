<?php
class Bill extends Phalcon\Mvc\Model
{
	public $bid;
	public $gid;
	public $bname;
	public $uid;
	public $cost;
	public $create_time;
	public $finish_time;
	public $place;
	public $status;

	public function getSource(){
		return "t_bill";
	}

	public function init(){
	}
	

}