<?php
define("ROOT",dirname(dirname(dirname(dirname(__FILE__)))));
require_once dirname(dirname(__FILE__))."/User.class.php";
require_once ROOT."/build/AutoLoad.lib.php";
require_once ROOT."/lib/Data.class.php";
$user=array('uname'=>"liupengzhan");
//var_dump( $user);
//$ret=User::addUser($user);
$ret=User::getUsers();
var_dump( $ret);
