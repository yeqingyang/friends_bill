<?php
class BillControllere extends ControllerBase
{
	public function indexAction()
	{

		$bill = new Bill();
		$bills = $bill->find();
		$data = array ();
		foreach ( $bills as $oneBill ) {
			$data [] = array (
					'bid' =>$oneBill->bid,
					'gid' => $oneBill->gid,
					'uid' => $oneBill->uid,
					'create_time' =>$oneBill->create_time,
					'finish_time' =>$oneBill->finish_time,
					'place' =>$oneBill->place,
					'status' =>$oneBill->status,
			);
		}
		echo json_encode($data);
	}

}