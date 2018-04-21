#图片类型表
DROP TABLE IF EXISTS `sys_picture_type`;
CREATE TABLE `sys_picture_type` (
  `id`  INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR (20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `create_time` INT(11) NOT NULL DEFAULT 0,
  `update_time` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)ENGINE =innoDB AUTO_INCREMENT=1 DEFAULT CHARSET =UTF8;

#图片表
DROP TABLE IF EXISTS `sys_picture`;
CREATE TABLE `sys_picture`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type_id` INT(11) NOT NULL DEFAULT 0,
  `base_url` VARCHAR(1024) NOT NULL DEFAULT  '',
  `url` VARCHAR(1024) NOT NULL DEFAULT '',
  `path` VARCHAR(1024) NOT NULL DEFAULT '',
  `mime` VARCHAR(20) NOT NULL DEFAULT '',
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `caption` VARCHAR(255) NOT NULL DEFAULT '',
  `sort` INT(8) NOT NULL DEFAULT 0,
  `create_time` INT(11) NOT NULL DEFAULT 0,
  `update_time` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY type_id(`type_id`),
  FOREIGN KEY (`type_id`) REFERENCES sys_picture_type(`id`)
)ENGINE=innoDB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

#sys_picture insert

INSERT INTO sys_picture_type (`name`,`status`,`create_time`,`update_time`) VALUE ('轮播图',1,unix_timestamp(NOW()),unix_timestamp(NOW()));


#sys_picture insert

INSERT INTO
sys_picture (`type_id`,`base_url`,`url`,`path`,`mime`,`status`,`caption`,`sort`,`create_time`,`update_time`)
VALUES
(1,'http://www.cixin.com','/','assets/images/login_bg/1.jpg','image/jpeg','1','立春','1',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/2.jpg','image/jpeg','1','雨水','2',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/3.jpg','image/jpeg','1','惊蛰','3',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/4.jpg','image/jpeg','1','春分','4',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/5.jpg','image/jpeg','1','清明','5',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/6.jpg','image/jpeg','1','谷雨','6',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/7.jpg','image/jpeg','1','立夏','7',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/8.jpg','image/jpeg','1','小满','8',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/9.jpg','image/jpeg','1','芒种','9',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/10.jpg','image/jpeg','1','夏至','10',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/11.jpg','image/jpeg','1','小暑','11',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/12.jpg','image/jpeg','1','大暑','12',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/13.jpg','image/jpeg','1','立秋','13',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/14.jpg','image/jpeg','1','处暑','14',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/15.jpg','image/jpeg','1','白露','15',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/16.jpg','image/jpeg','1','秋分','16',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/17.jpg','image/jpeg','1','寒露','17',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/18.jpg','image/jpeg','1','霜降','18',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/19.jpg','image/jpeg','1','立冬','19',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/20.jpg','image/jpeg','1','小雪','20',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/21.jpg','image/jpeg','1','大雪','21',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/22.jpg','image/jpeg','1','冬至','22',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/23.jpg','image/jpeg','1','小寒','23',unix_timestamp(NOW()),unix_timestamp(NOW())),
(1,'http://www.cixin.com','/','assets/images/login_bg/24.jpg','image/jpeg','1','大寒','24',unix_timestamp(NOW()),unix_timestamp(NOW()));
