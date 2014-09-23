<?php
class Group extends Phalcon\Mvc\Model
{
	public $gid;
	public $gname;
	public $uid;
	public $create_time;
	public $status;	
	public $va_user;
	
	public function getSource(){
		return 't_group';
	}
	
	public function initialize(){
		
	}
	// Use Loader() to autoload our model
	// $loader = new \Phalcon\Loader();
	// $loader->registerDirs(
	// 			array(
	// 				'../app/controllers/',
	// 				'../app/models/',
	// 				'../lib/',
	// 				'../def/',
	// 				)
	// 			)->register();
	// $di = new \Phalcon\DI\FactoryDefault();
	// //Set up the database service
	// $di->set('db', function(){
	// 	return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
	// 			"host" => "192.168.1.41",
	// 			"username" => "root",
	// 			"password" => "",
	// 			"dbname" => "frends_bill"
	// 	));
	// });
	// $app = new Phalcon\Mvc\Micro($di);
	// //Retrieves all group
	// $app->get('/group', function() use($app){
	// 	$phql = "SELECT * FROM t_group ORDER BY name";
	// 	echo $phql;
	// // 	return;
	// 	$groups = $app->modelsManager->executeQuery($phql);
	// 	echo 'ok';
	// 	$data = array();
	// 	foreach ($groups as $group) {
	// 		$data[] = array(
	// 				’id’ => $group->id,
	// 				’name’ => $group->name,
	// 		);
	// 	}
	// 	echo json_encode($data);
	// });
	// //Searches for group with $name in their name
	// $app->get('/group/search/{name}', function($name) {
	// });
	// //Retrieves group based on primary key
	// $app->get('/group/{id:[0-9]+}', function($id) {
	// });
	// //Adds a new group
	// $app->post('/group', function() {
	// });
	// //Updates group based on primary key
	// $app->put('/group/{id:[0-9]+}', function() {
	// });
	// //Deletes group based on primary key
	// $app->delete('/group/{id:[0-9]+}', function() {
	// });
	// $app->handle();
	
}
