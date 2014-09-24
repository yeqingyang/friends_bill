<?php

class GroupController extends Phalcon\Mvc\Controller
{

	public function indexAction()
	{
		$group = new Group();
		$groups = $group->find();
		$data = array ();
		foreach ( $groups as $group ) {
			$data [] = array (
					'id' => $group->gid,
					'uid' =>$group->uid,
					'name' => $group->gname,
					'ctime' => $group->create_time,
					 'va_user' =>unserialize($group->va_user)
			);
		}
		echo json_encode($data);
	}

	public function getGroupAction($name=NULL)
	{
		$gname = $this->request->get('gname','string');
		if(empty($gname)){
			$groups = Group::find();
		}else{
			$groups = Group::find("gname='$gname'");
		}
		$data = array ();
		foreach ( $groups as $group ) {
			$data [] = array (
					'id' => $group->gid,
					'uid' =>$group->uid,
					'name' => $group->gname,
					'ctime' => $group->create_time,
					'va_user' =>unserialize($group->va_user)
			);
		}
		echo json_encode($data);
	}
	
	public function addGroupAction(){
		$group = new Group();
		$group->gname = $this->request->get('gname','string');
		$group->uid = 0;
		$group->create_time = Util::getTime();
		$group->va_user = serialize(array());
		$group->status = 1;
		$group->save();
		echo 'ok';
	}
	
	public function addUserGroupAction(){
		$gid = $this->request->get('gid');
		$uid = $this->request->get('uid');
		$group = Group::findFirst("gid=$gid");
		$users = unserialize($group->va_user) ;
		if(in_array($uid, $users)){
			echo "$uid already in the group<p>";
			return;
		}else{
			$users[]=$uid;
		}
		$group->va_user = serialize($users);
		$group->update();
		echo 'ok';
	}
	
	public function deleteGroupAction($gid){
		$group = Group::findFirst("gid=$gid");
		$group->delete();
	}

}
