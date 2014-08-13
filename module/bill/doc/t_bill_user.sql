CREATE TABLE IF NOT EXISTS `t_bill_user`
(
	`bid`	int(10) unsigned not null comment '账单id',
	`uid`	int(10) unsigned not null comment '分组id',
	`pay`	int(10) unsigned not null comment '花费或应得',
	`create_time` int(10) unsigned not null comment '创建时间',
	`clear_time` int(10) unsigned not null comment '结账时间',
	`status` int(10) unsigned not null default 0 comment '状态：0正常 1清帐 2删除',
	primary key(`bid`)
)default charset utf8 engine = InnoDb;