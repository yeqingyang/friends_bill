<?php
class Bill extends Phalcon\Mvc\Model
{
	public $bid;
	public $gid;
	public $uid;
	public $cost;
	public $create_time;
	public $finish_time;
	public $place;
	public $comments;
	public $status;

	public function getSource(){
		return "t_bill";
	}

	public function init(){
	}

}