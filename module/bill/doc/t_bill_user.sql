CREATE TABLE IF NOT EXISTS `t_bill_user`
(
	`bid`	int(10) unsigned not null comment '�˵�id',
	`uid`	int(10) unsigned not null comment '����id',
	`pay`	int(10) unsigned not null comment '���ѻ�Ӧ��',
	`create_time` int(10) unsigned not null comment '����ʱ��',
	`clear_time` int(10) unsigned not null comment '����ʱ��',
	`status` int(10) unsigned not null default 0 comment '״̬��0���� 1���� 2ɾ��',
	primary key(`bid`)
)default charset utf8 engine = InnoDb;