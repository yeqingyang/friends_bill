CREATE TABLE IF NOT EXISTS `t_bill`
(
	`bid`	int(10) unsigned not null comment '�˵�id',
	`bname`	int(10) unsigned not null comment '�˵�����',
	`gid`	int(10) unsigned not null comment '����id',
	`cost`	int(10) unsigned not null comment '�ܹ�����',
	`uid`	int(10) unsigned not null comment '������uid',
	`create_time` int(10) unsigned not null comment '����ʱ��',
	`clear_time` int(10) unsigned not null comment '����ʱ��',
	`status` int(10) unsigned not null default 0 comment '״̬��0���� 1���� 2ɾ��',
	`va_userinfo` blob not null comment 'array(uid, prepay, afterpay,)',
	primary key(`bid`)
)default charset utf8 engine = InnoDb;