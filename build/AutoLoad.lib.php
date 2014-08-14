<?php
/***************************************************************************
 *
 * Copyright (c) 2011 babeltime.com, Inc. All Rights Reserved
 * $Id: AutoLoad.lib.php 18343 2012-04-09 07:27:48Z HaidongJia $
 *
 **************************************************************************/

 /**
 * @file $HeadURL: svn://192.168.1.80:3698/C/trunk/pirate/rpcfw/build/AutoLoad.lib.php $
 * @author $Author: HaidongJia $(jhd@babeltime.com)
 * @date $Date: 2012-04-09 15:27:48 +0800 (一, 2012-04-09) $
 * @version $Revision: 18343 $
 * @brief
 *
 **/

require_once dirname ( __FILE__ ) . '/Merge.def.php';

function autoLoadDir($dirname, $ignore_list, $dirname_base)
{
	fwrite(STDERR, "autoload dir:$dirname" . MergeDef::LINE_END_TAG);

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
				$matches = preg_match($ignore, substr($_filename, strlen($dirname_base)));
				if ( !empty($matches) )
				{
					$ignore_this = TRUE;
					break;
				}
			}

			if ( $ignore_this == FALSE )
			{
				$file_arr = autoLoadFile ( $_filename );
				$file_array = array_merge($file_array, $file_arr);
			}
			else
			{
				fwrite(STDERR, "ignore file:" . $_filename . MergeDef::LINE_END_TAG);
			}
		}
		else
		{
			$file_arr = autoLoadDir ( $_filename, $ignore_list, $dirname_base );
			$file_array = array_merge($file_array, $file_arr);
		}
	}
	return $file_array;
}


function autoLoadFile( $filename )
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
		fwrite(STDERR, "invalid file:" . $filename . MergeDef::LINE_END_TAG);
		return array();
	}

	fwrite(STDERR, "autoload file:$filename" . MergeDef::LINE_END_TAG);

	$file = fopen ( $filename, 'r' );
	$return = array();
	while ( true )
	{
		$line = fgets ( $file );

		if (false == $line)
		{
			break;
		}

		if ( preg_match ( '/^\s*class\s+([A-Za-z0-9\_]+)/', $line, $matches ) )
		{
			$className = strtolower($matches[1]);
			$return[$className] = $filename;
		}

		if ( preg_match ( '/^\s*interface\s+([A-Za-z0-9\_]+)/', $line, $matches ) )
		{
			$className = strtolower($matches[1]);
			$return[$className] = $filename;
		}
	}
	return $return;
}
/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */