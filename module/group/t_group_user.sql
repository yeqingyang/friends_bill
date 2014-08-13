create table t_friend(
	gid int unsigned not null comment '分组id',
	uid int unsigned not null comment '用户id',
	user_type int unsigned not null comment '用户类型',
	status tinyint unsigned not null comment '状态，1表示删除，0表示正常',
	primary key(gid, uid),
)engine = InnoDb default charset utf8;