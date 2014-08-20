<?php

function loader($class)
{
	$class = strtolower($class);
	$file = ClassDef::$ARR_CLASS[$class];
	if (is_file($file)) {
		require_once($file);
	}else{
		echo $file.'not exit';
	}
}

spl_autoload_register('loader');
