CREATE TABLE IF NOT EXISTS `t_bill`
(
	`bid`	int(10) unsigned not null comment '账单id',
	`bname`	int(10) unsigned not null comment '账单名字',
	`gid`	int(10) unsigned not null comment '分组id',
	`cost`	int(10) unsigned not null comment '总共消费',
	`uid`	int(10) unsigned not null comment '创建者uid',
	`create_time` int(10) unsigned not null comment '创建时间',
	`clear_time` int(10) unsigned not null comment '结账时间',
	`status` int(10) unsigned not null default 0 comment '状态：0正常 1清帐 2删除',
	`va_userinfo` blob not null comment 'array(uid, prepay, afterpay,)',
	primary key(`bid`)
)default charset utf8 engine = InnoDb;