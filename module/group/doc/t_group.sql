CREATE TABLE IF NOT EXISTS `t_group`
(
	`gid`	int(10) unsigned not null comment '����id',
	`gname`	int(10) unsigned not null comment '��������',
	`uid`	int(10) unsigned not null comment '������uid',
	`create_time` int(10) unsigned not null comment '����ʱ��',
	`status` int(10) unsigned not null default 0 comment '״̬��0���� 1ɾ��',
	primary key(`gid`)
)default charset utf8 engine = InnoDb;