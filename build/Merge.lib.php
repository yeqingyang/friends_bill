<?php
/***************************************************************************
 *
 * Copyright (c) 2011 babeltime.com, Inc. All Rights Reserved
 * $Id: Merge.lib.php 16402 2012-03-14 02:36:38Z HaopingBai $
 *
 **************************************************************************/

 /**
 * @file $HeadURL: svn://192.168.1.80:3698/C/trunk/pirate/rpcfw/build/Merge.lib.php $
 * @author $Author: HaopingBai $(jhd@babeltime.com)
 * @date $Date: 2012-03-14 10:36:38 +0800 (三, 2012-03-14) $
 * @version $Revision: 16402 $
 * @brief
 *
 **/

require_once dirname ( __FILE__ ) . '/Merge.def.php';

function mergeDir($dirname, $target, $ignore_list, $line_number = 0 )
{
	fwrite(STDERR, "merge dir:$dirname" . MergeDef::LINE_END_TAG);

	$dir = opendir ( $dirname );
	$file_array = array();

	while ( true )
	{
		$filename = readdir ( $dir );
		if (empty ( $filename ))
		{
			break;
		}

		if($filename == '.' || $filename == '..')
		{
			continue;
		}

		$_filename = $dirname . '/' . $filename;
		if (is_file ( $_filename ))
		{
			$ignore_this = FALSE;
			foreach ( $ignore_list as $ignore )
			{
				if ( substr($filename, (-1)*strlen($ignore)) == $ignore )
				{
					$ignore_this = TRUE;
					break;
				}
			}

			if ( $ignore_this == FALSE )
			{
				$last_line_number = $line_number;
				$return = mergeFile ( $_filename, $target, $line_number );
				if ( $return['merge_success'] == TRUE )
				{
					$file_array[$last_line_number] = $_filename;
					$line_number = $return['line_number'];
				}
			}
		}
		else
		{
			$file_arr = mergeDir ( $_filename, $target, $ignore_list, $line_number );
			$file_array = array_merge($file_array, $file_arr);
		}
	}
	return $file_array;
}

function mergeFile($filename, $target, $line_number = 0)
{
	$extension_match = FALSE;
	foreach ( MergeDef::$PHP_FILE_EXT as $extension )
	{
		if ( substr ( $filename, (-1)*strlen($extension) ) == $extension )
		{
			$extension_match = TRUE;
		}
	}
	if ( $extension_match == FALSE )
	{
		fwrite(STDERR, "ignore file:" . $filename . MergeDef::LINE_END_TAG);
		return array( 'merge_success' => FALSE, 'line_number' => $line_number );
	}

	fwrite(STDERR, "merge file:$filename" . MergeDef::LINE_END_TAG);

	$file = fopen ( $filename, 'r' );
	while ( true )
	{
		$line = fgets ( $file );
		$line_number++;

		if (false == $line)
		{
			break;
		}

		if ( substr($line, -1) != MergeDef::LINE_END_TAG )
		{
			$line .= MergeDef::LINE_END_TAG;
		}

		if (preg_match ( '/^\s*require_once.+/', $line ))
		{
			fputs ( $target, MergeDef::LINE_END_TAG );
		}
		else if(preg_match('/^\<\?php.*/', $line))
		{
			fputs($target, MergeDef::LINE_END_TAG);
		}
		else
		{
			fputs ( $target, $line );
		}
	}
	return array( 'merge_success' => TRUE, 'line_number' => $line_number );
}
/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */