CREATE TABLE IF NOT EXISTS `t_group`
(
	`gid`	int(10) unsigned not null comment '分组id',
	`gname`	int(10) unsigned not null comment '分组名字',
	`uid`	int(10) unsigned not null comment '创建者uid',
	`create_time` int(10) unsigned not null comment '创建时间',
	`status` int(10) unsigned not null default 0 comment '状态：0正常 1删除',
	primary key(`gid`)
)default charset utf8 engine = InnoDb;