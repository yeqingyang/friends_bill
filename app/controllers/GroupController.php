<?php

class GroupController extends Phalcon\Mvc\Controller
{

	public function indexAction()
	{
		echo "hello in group";
	}

	public function registerAction()
	{

		//Request variables from html form
		$name = $this->request->getPost('name', 'string');
		$member = $this->request->getPost('member', 'string');

		$group = new Group();
		$group->name = $name;

		//Store and check for errors
		if ($user->save() == true) {
			echo 'Thanks for register!</p>';
			echo Phalcon\Tag::linkTo("index","Back");
		} else {
			echo 'Sorry, the next problems were generated: ';
			foreach ($user->getMessages() as $message){
				echo $message->getMessage(), '<br/>';
			}
			echo Phalcon\Tag::linkTo("index","Back");	
		}
	}
	
	public function getGroupAction($name=NULL)
	{
	
		$phql = "SELECT * FROM Group";
		echo $phql;
		$query = $this->modelsManager->createQuery( $phql );
		$groups = $query->execute();
		echo 'ok';
		$data = array ();
		foreach ( $groups as $group ) {
			$data [] = array (
					id => $group->id,
					name => $group->name 
			);
		}
		echo json_encode($data);
	}

}
