<?php
/***************************************************************************
 *
 * Copyright (c) 2011 babeltime.com, Inc. All Rights Reserved
 * $Id: AutoLoad.php 18343 2012-04-09 07:27:48Z HaidongJia $
 *
 **************************************************************************/

 /**
 * @file $HeadURL: svn://192.168.1.80:3698/C/trunk/pirate/rpcfw/build/AutoLoad.php $
 * @author $Author: HaidongJia $(jhd@babeltime.com)
 * @date $Date: 2012-04-09 15:27:48 +0800 (一, 2012-04-09) $
 * @version $Revision: 18343 $
 * @brief
 *
 **/
require_once dirname ( __FILE__ ) . '/Merge.def.php';
require_once dirname ( __FILE__ ) . '/AutoLoad.lib.php';

function print_usage()
{
	echo	"Usage:php AutoLoad.php [options]:
				-s		autoload directory;
				-i		ignore list(need match preg regularity), used with -s option, like '/index\\.php/;/index\\.def\\.php/';
				-o		output file name, if not set, standard ouput is used;
				-h		help list;
				-?		help list!\n";
}

function main()
{
	global $argc, $argv;

	$result = getopt('s:i:f:t:o:h:?');

	$source_dir_name = '';
	$ignore_file_names = '';
	$file_list_file_name = '';
	$output_file_name = '';

	foreach ( $result as $key => $value )
	{
		switch ( $key )
		{
			case 's':
				$source_dir_name = $value;
				break;
			case 'i':
				$ignore_file_names = $value;
				break;
			case 'f':
				$file_list_file_name = $value;
				break;
			case 'o':
				$output_file_name = $value;
				break;
			case 'h':
			case '?':
			default:
				print_usage();
				exit;
		}
	}

	if  ( ( file_exists($source_dir_name) == FALSE || is_dir($source_dir_name) == FALSE ) )
	{
		fwrite(STDERR,"-s should be set!\n");
		print_usage();
		exit;
	}

	if ( file_exists(dirname($output_file_name)) == FALSE )
	{
		exec( "mkdir -p " . dirname($output_file_name) );
	}

	$file_array = array();
	if ( !empty($source_dir_name) )
	{
		if ( !empty($ignore_file_names) )
		{
			$ignore_file_list = explode(';', $ignore_file_names);
		}
		else
		{
			$ignore_file_list = array();
		}
		$file_array = autoLoadDir ( $source_dir_name, $ignore_file_list, $source_dir_name );
	}

	foreach ( $file_array as $key => $value )
	{
		$value = substr($value, strlen($source_dir_name));
		if ( $value[0] == '/' || $value[0] == '\\' )
		{
			$value = substr($value, 1);
		}
		$file_array[$key] = $value;
	}

	$data = MergeDef::PHP_START_TAG . MergeDef::LINE_END_TAG
		. MergeDef::PHP_CLASS . MergeDef::DEFAULT_CLASS_NAME . MergeDef::LINE_END_TAG;


	$var = MergeDef::PHP_PUBLIC_STATIC . MergeDef::DEFAULT_VAR_NAME
		. MergeDef::PHP_ASSIGN . var_export($file_array, TRUE)
		. MergeDef::PHP_STATEMENT_END . MergeDef::LINE_END_TAG;
	$var = sprintf(MergeDef::PHP_CLASS_BODY, $var);
	$data .= $var;

	if ( empty($output_file_name) )
	{
		echo $data;
	}
	else
	{
		$output_file = fopen($output_file_name, 'w+');
		fwrite($output_file, $data);
		fclose($output_file);
	}
}

main ($argc, $argv);
/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */