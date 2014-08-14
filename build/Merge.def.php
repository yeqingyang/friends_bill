<?php
/***************************************************************************
 *
 * Copyright (c) 2011 babeltime.com, Inc. All Rights Reserved
 * $Id: Merge.def.php 16402 2012-03-14 02:36:38Z HaopingBai $
 *
 **************************************************************************/

 /**
 * @file $HeadURL: svn://192.168.1.80:3698/C/trunk/pirate/rpcfw/build/Merge.def.php $
 * @author $Author: HaopingBai $(jhd@babeltime.com)
 * @date $Date: 2012-03-14 10:36:38 +0800 (三, 2012-03-14) $
 * @version $Revision: 16402 $
 * @brief
 *
 **/

class MergeDef
{
	const PHP_START_TAG				=	"<?php";
	const PHP_END_TAG				=	"";
	const LINE_END_TAG				=	"\n";
	const DEFAULT_FILE_ARRAY_NAME	=	"\$FILE_ARRAY";
	const PHP_VAR_PRE				=	"\$";
	const PHP_STATEMENT_END			=	";";
	const DEFAULT_CLASS_NAME		=	"ClassDef";
	const PHP_CLASS					=	"class ";
	const PHP_PUBLIC_STATIC			=	"public static ";
	const PHP_CLASS_BODY			=	"{\n%s}\n";
	const PHP_ASSIGN				=	" = ";
	const DEFAULT_VAR_NAME			=	"\$ARR_CLASS";

	const OUTPUT_TYPE_FILE			=	0;
	const OUTPUT_TYPE_SERIALIZE		=	1;
	const OUTPUT_TYPE_APPEND		=	2;

	public static $PHP_FILE_EXT		=	array( ".php" );

}
/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */