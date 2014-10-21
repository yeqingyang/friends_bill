<?php
class BillUser extends Phalcon\Mvc\Model
{
	public $uid;
	public $gid;

	public $prepay;
	public $afterpay;
	public $payment;

	public function getSource(){
		return "t_billuser";
	}

}