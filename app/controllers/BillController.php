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
	
	public function addBillAction(){
		$bill = new Bill();
		$bill->init();
		$bill->bname = $this->request->get('bname','string');
		$bill->gid = $this->request->get('gid');
		$bill->uid = $this->request->get('uid');
		$bill->cost = $this->request->get('cost');
		$bill->create_time = Util::getTime();
		$bill->finish_time = 0;
		$bill->place = $this->request->get('place','string');
		$bill->status = 0;
		//check gid
		$group = Group::find("gid='$bill->gid'");
		if(count($group)==0){
			Logger::warning('no this group %d',$bill->gid);
			return "no this group id $bill->gid";
		}
		if($bill->cost <= 0){
			Logger::warning('cost <= 0 %d',$bill->cost);
			return "cost must > 0";
		}
		$ret = $bill->save();
		if(!$ret){
			foreach ($bill->getMessages() as $message) {
				$this->flash->error((string) $message);
			}
		}else{
			$this->flash->success("Bill was successfully added");
			return $this->response->redirect("Bill/index");
		}
	}

}