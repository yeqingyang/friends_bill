create table t_friend(
	gid int unsigned not null comment '����id',
	uid int unsigned not null comment '�û�id',
	user_type int unsigned not null comment '�û�����',
	status tinyint unsigned not null comment '״̬��1��ʾɾ����0��ʾ����',
	primary key(gid, uid),
)engine = InnoDb default charset utf8;