<?php

class GroupController extends Phalcon\Mvc\Controller
{

	public function indexAction()
	{

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

}
