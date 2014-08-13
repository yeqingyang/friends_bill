CREATE TABLE IF NOT EXISTS `t_user`
(
	`uid`	int(10) unsigned not null comment '玩家uid',
	`usetime` int(10) unsigned not null comment '上次登录时间',
	`uname`	varchar(16) not null comment '用户uname',
	`status` int unsigned not null default 1 comment '用户状态，0：deleted，1：online, 2：offline, 3:suspend ',
	`create_time` int unsigned not null comment '用户创建时间',
	`dtime` int unsigned default 0 comment '用户删除时间',
	`birthday` int unsigned not null default 0 comment '用户生日',
	`gold_num` int unsigned not null default 0 comment '金币RMB',
	`reward_point` int unsigned not null default 0 comment '积分',
	`last_login_time` int unsigned not null default 0 comment '上次登录的时间',
    `online_accum_time` int unsigned not null default 0 comment '在线累计时间',
	primary key(`uid`)
)default charset utf8 engine = InnoDb;