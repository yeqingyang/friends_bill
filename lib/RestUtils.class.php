<?php
class RestUtils {
	public static function processRequest() {
		// get our verb ��ȡ����
		$request_method = strtolower ( $_SERVER ['REQUEST_METHOD'] );
		$return_obj = new RestRequest ();
		$return_obj->setMethod($request_method);
		// we'll store our data here ������洢�������
		$data = array ();
		
		switch ($return_obj->getMethod) {
			// this is a request for all users, not one in particular
			case 'get' :
				Logger::info("http get request");
				$user_list = User::getUsers(); // assume this returns an array
				var_dump($user_list);
				if ($return_obj->getHttpAccept() == 'json') {
					Logger::info("getHttpAccept is json");
					RestUtils::sendResponse ( 200, json_encode ( $user_list ), 'application/json' );
				} else if ($return_obj->getHttpAccept() == 'xml') {
					// using the XML_SERIALIZER Pear Package
					Logger::info("getHttpAccept is xml");
					$options = array (
							'indent' => '     ',
							'addDecl' => false,
							'rootName' => 'userlist',
							XML_SERIALIZER_OPTION_RETURN_RESULT => true 
					);
					Logger::info("start XML_Serializer");
					$serializer = new XML_Serializer ( $options );
					Logger::info("start sendResponse");
					RestUtils::sendResponse ( 200, $serializer->serialize ( $user_list ), 'application/xml' );
				}
				
				break;
			// new user create
			case 'post' :
				$user = new User ();
				$user->setFirstName ( $return_obj->getData ()->first_name ); // just for example, this should be done cleaner
				                                                   // and so on...
				$user->save ();
				
				// just send the new ID as the body
				RestUtils::sendResponse ( 201, $user->getId () );
				break;
		}
		
		// store the method
		$return_obj->setMethod ( $request_method );
		
		// set the raw data, so we can access it if needed (there may be
		// other pieces to your requests)
		$return_obj->setRequestVars ( $data );
		
		if (isset ( $data ['data'] )) {
			// translate the JSON to an Object for use however you want
			$return_obj->setData ( json_decode ( $data ['data'] ) );
		}
		return $return_obj;
	}
	public static function sendResponse($status = 200, $body = '', $content_type = 'text/html') {
		$status_header = 'HTTP/1.1 ' . $status . ' ' . RestUtils::getStatusCodeMessage ( $status );
		// set the status
		header ( $status_header );
		// set the content type
		header ( 'Content-type: ' . $content_type );
		
		// pages with body are easy
		if ($body != '') {
			// send the body
			echo $body;
			exit ();
		} 		// we need to create the body if none is passed
		else {
			// create some body messages
			$message = '';
			
			// this is purely optional, but makes the pages a little nicer to read
			// for your users. Since you won't likely send a lot of different status codes,
			// this also shouldn't be too ponderous to maintain
			switch ($status) {
				case 401 :
					$message = 'You must be authorized to view this page.';
					break;
				case 404 :
					$message = 'The requested URL ' . $_SERVER ['REQUEST_URI'] . ' was not found.';
					break;
				case 500 :
					$message = 'The server encountered an error processing your request.';
					break;
				case 501 :
					$message = 'The requested method is not implemented.';
					break;
			}
			
			// servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
			$signature = ($_SERVER ['SERVER_SIGNATURE'] == '') ? $_SERVER ['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER ['SERVER_NAME'] . ' Port ' . $_SERVER ['SERVER_PORT'] : $_SERVER ['SERVER_SIGNATURE'];
			
			// this should be templatized in a real-world solution
			$body = ' "http://www.w3.org/TR/html4/strict.dtd">
                    "Content-Type" content="text/html; charset=iso-8859-1">
                    ' . $status . ' ' . RestUtils::getStatusCodeMessage ( $status ) . '
					' . RestUtils::getStatusCodeMessage ( $status ) . '
                    ' . $message . '
					' . $signature . '
                    ';
			
			echo $body;
			exit ();
		}
	}
	public static function getStatusCodeMessage($status) {
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		// ��ЩӦ�ñ��洢��һ��.ini���ļ��У�Ȼ��ͨ��parse_ini_file()����������������Ȼ������Ҳ�㹻�ˣ����磺
		$codes = Array (
				100 => 'Continue',
				101 => 'Switching Protocols',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				306 => '(Unused)',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported' 
		);
		
		return (isset ( $codes [$status] )) ? $codes [$status] : '';
	}
}
class RestRequest {
	private $request_vars;
	private $data;
	private $http_accept;
	private $method;
	public function __construct() {
		$this->request_vars = array ();
		$this->data = '';
		$this->http_accept = (strpos ( $_SERVER ['HTTP_ACCEPT'], 'json' )) ? 'json' : 'xml';
		$this->method = 'get';
	}
	public function setData($data) {
		$this->data = $data;
	}
	public function setMethod($method) {
		$this->method = $method;
	}
	public function setRequestVars($request_vars) {
		$this->request_vars = $request_vars;
	}
	public function getData() {
		return $this->data;
	}
	public function getMethod() {
		return $this->method;
	}
	public function getHttpAccept() {
		return $this->http_accept;
	}
	public function getRequestVars() {
		return $this->request_vars;
	}
}
