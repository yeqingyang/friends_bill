<?php
define("ROOT",dirname(__FILE__));
require_once ROOT."/build/AutoLoad.lib.php";
$ret=User::getUsers();
var_dump( $ret);
