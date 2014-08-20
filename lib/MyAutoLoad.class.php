<?php

function loader($class)
{
	$file = ClassDef::$ARR_CLASS[$class];
	if (is_file($file)) {
		require_once($file);
	}
}

spl_autoload_register('loader');