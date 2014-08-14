<?php
/***************************************************************************
 *
 * Copyright (c) 2010 babeltime.com, Inc. All Rights Reserved
 * $Id: Merge.php 16402 2012-03-14 02:36:38Z HaopingBai $
 *
 **************************************************************************/

/**
 * @file $HeadURL: svn://192.168.1.80:3698/C/trunk/pirate/rpcfw/build/Merge.php $
 * @author $Author: HaopingBai $(hoping@babeltime.com)
 * @date $Date: 2012-03-14 10:36:38 +0800 (三, 2012-03-14) $
 * @version $Revision: 16402 $
 * @brief
 *
 **/

require_once dirname ( __FILE__ ) . '/Merge.def.php';
require_once dirname ( __FILE__ ) . '/Merge.lib.php';

function print_usage()
{
	echo	"Usage:php Merge.php [options]:
				-s		merge directory;
				-i		ignore list, used with -s option, like 'index.php,index.def.php';
				-f		file list file name;
				-t		target file name, if dir is not exist, will be create;
				-p		output type:
							0:php file -o option will be used
							1:searlize data file -o option will be used
							2:append target file -o will be ignore
							other value will be set 0;
				-o		output file name, if not set, standard ouput is used;
				-a		array name, default FILE_ARRAY;
				-h		help list;
				-?		help list!\n";
}

function main()
{
	global $argc, $argv;

	$result = getopt('s:i:f:t:p:o:a:h:?');

	$target_file_name = '';
	$source_dir_name = '';
	$ignore_file_names = '';
	$file_list_file_name = '';
	$output_file_name = '';
	$array_name = '';
	$output_type = 0;

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
			case 't':
				$target_file_name = $value;
				break;
			case 'f':
				$file_list_file_name = $value;
				break;
			case 'p':
				$output_type = intval($value);
				break;
			case 'o':
				$output_file_name = $value;
				break;
			case 'a':
				$array_name = $value;
				break;
			case 'h':
			case '?':
			default:
				print_usage();
				exit;
		}
	}

	if  ( ( file_exists($source_dir_name) == FALSE || is_dir($source_dir_name) == FALSE ) &&
		file_exists($file_list_file_name) == FALSE )
	{
		fwrite(STDERR,"-f or -s should be set!\n");
		print_usage();
		exit;
	}

	if ( empty($target_file_name) )
	{
		fwrite(STDERR,"-t should be set!\n");
		print_usage();
		exit;
	}

	if ( file_exists(dirname($target_file_name)) == FALSE )
	{
		exec( "mkdir -p " . dirname($target_file_name) );
	}

	$target_file = fopen ( $target_file_name, 'w' );
	fputs($target_file, MergeDef::PHP_START_TAG.MergeDef::LINE_END_TAG);

	if ( !empty($source_dir_name) )
	{
		if ( !empty($ignore_file_names) )
		{
			$ignore_file_list = explode(',', $ignore_file_names);
		}
		else
		{
			$ignore_file_list = array();
		}
		$file_array = mergeDir ( $source_dir_name, $target_file, $ignore_file_list, 1 );
	}
	else if ( !empty($file_list_file_name) )
	{
		$file_list_file = fopen($file_list_file_name, 'r');
		$file_array = array();
		$line_number = 1;
		while ( TRUE )
		{
			$line = trim(fgets($file_list_file));
			if ( empty($line) )
			{
				break;
			}
			$return = mergeFile($line, $target_file, $line_number);
			if ( $return['merge_success'] == TRUE )
			{
				$file_array[$line_number] = $line;
				$line_number = $return['line_number'];
			}
		}
		fclose($file_list_file);
	}

	if ( empty($array_name) )
	{
		$array_name = MergeDef::DEFAULT_FILE_ARRAY_NAME;
	}
	else
	{
		if ( $array_name[0] != MergeDef::PHP_VAR_PRE )
		{
			$array_name = MergeDef::PHP_VAR_PRE . $array_name;
		}
	}
	$data = $array_name . ' = ' . var_export($file_array, TRUE)
		. MergeDef::PHP_STATEMENT_END . MergeDef::LINE_END_TAG;

	if ( $output_type == MergeDef::OUTPUT_TYPE_APPEND )
	{
		fwrite( $target_file, $data );
	}
	else if ( $output_type == MergeDef::OUTPUT_TYPE_SERIALIZE )
	{
		$data = serialize($file_array);
	}
	else
	{
		$data = MergeDef::PHP_START_TAG . MergeDef::LINE_END_TAG . $data;
	}

	if ( $output_type != MergeDef::OUTPUT_TYPE_APPEND )
	{
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

	fclose ( $target_file );
}

main ($argc, $argv);

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
