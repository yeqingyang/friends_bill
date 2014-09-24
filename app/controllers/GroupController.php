<?php

class GroupController extends Phalcon\Mvc\Controller
{

	public function indexAction()
	{
		echo "hello in group";
	}

	public function getGroupAction($name=NULL)
	{
		$group = new Group();
		$groups = $group->getGroup();
// 		$phql = "SELECT * FROM Group";
// 		$query = $this->modelsManager->createQuery( $phql );
// 		$groups = $query->execute();
		$data = array ();
		foreach ( $groups as $group ) {
			$data [] = array (
					id => $group->gid,
					name => $group->gname 
			);
		}
		echo json_encode($data);
	}

}
