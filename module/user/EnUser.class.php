<?php
/***************************************************************************
 * 
 * Copyright (c) 2010 babeltime.com, Inc. All Rights Reserved
 * $Id$
 * 
 **************************************************************************/

 /**
 * @file $HeadURL$
 * @author $Author$(liupengzhan@babeltime.com)
 * @date $Date$
 * @version $Revision$
 * @brief 
 *  
 **/
class EnUser{
	
	public static function getUsers(){
		$selectFields=array(
				'uid','usetime','uname','birthday','reward_point'
		);
		$wheres = array('uid', '!=', 0);
		$ret = User::getUsers($selectFields, $wheres);
		return $ret;
	}
	public static function adduser($oneUser){
		$ret = User::addUser($oneUser);
		return $ret;
	}
}
/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */