#/bin/bash

PHP=/home/pirate/programs/php/bin/php
DIR=`dirname $0`
cd $DIR
DIR=`pwd`
RPCFW_DIR=$DIR/..
cd $RPCFW_DIR
RPCFW_DIR=`pwd`

$PHP $DIR/AutoLoad.php -s $RPCFW_DIR \
	-o $RPCFW_DIR/def/Classes.def.php -i "#(test|exlib|script|build|Dummy|log|gsc|output)#"

