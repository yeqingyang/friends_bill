<?php

class GetusersController extends Phalcon\Mvc\Controller
{

	public function indexAction()
	{

	}

	public function getusersAction()
	{

		//Request variables from html form
		$name = $this->request->getPost('name', 'string');
		//$email = $this->request->getPost('email', 'email');

		$user = new Users();
		$user->getUsers($name);
	}

}
