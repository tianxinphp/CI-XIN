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
INSERT INTO sys_picture_type (`name`,`status`,`create_time`,`update_time`) VALUE ('首页logo',1,unix_timestamp(NOW()),unix_timestamp(NOW()));


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

INSERT INTO
  sys_picture (`type_id`,`base_url`,`url`,`path`,`mime`,`status`,`caption`,`sort`,`create_time`,`update_time`)
VALUES
(2,'http://www.cixin.com','/','assets/images/CI3.jpg','image/jpeg','1','CI3','1',unix_timestamp(NOW()),unix_timestamp(NOW()));

#用户表
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `username` VARCHAR(20) NOT NULL DEFAULT '',
   `password_hash` VARCHAR(255) NOT NULL DEFAULT '',
   `create_time` INT(11) NOT NULL DEFAULT 0,
   `email` VARCHAR(50) NOT NULL DEFAULT '',
   `status` TINYINT(1) NOT NULL DEFAULT 0,
   `update_time` INT(11) NOT NULL DEFAULT 0,
   `last_login_time` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY username(`username`),
    UNIQUE KEY email(`email`)
) ENGINE =innodb AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

#sys_user insert
INSERT INTO
`sys_user` (`username`,`password_hash`,`create_time`,`email`,`status`,`update_time`)
VALUE
('showuser','$2y$13$LPhhK0AHOLdS6FCFiBASg.OXWuYdpo7GoPmENI.biVtJHVERvNhQ2',unix_timestamp(now()),'844577216@qq.com','1',unix_timestamp(now()));

#字典项类型表
DROP TABLE IF EXISTS `sys_dictionary_type`;
CREATE TABLE `sys_dictionary_type` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(20) NOT NULL DEFAULT '',
  `name` VARCHAR(20) NOT NULL DEFAULT '',
  `remark` TEXT NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `create_time` INT(11) NOT NULL DEFAULT 0,
  `update_time` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY code(`code`)
)ENGINE =innodb AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

#sys_dictionary_type insert
INSERT INTO `sys_dictionary_type` (`code`,`name`,`status`,`remark`,`create_time`,`update_time`) VALUE ('SYSTEM_PRAMS','系统参数',1,'系统参数',unix_timestamp(now()),unix_timestamp(now()));


#字典项表
DROP TABLE IF EXISTS `sys_dictionary`;
CREATE TABLE `sys_dictionary` (
     `id` INT(11) NOT NULL AUTO_INCREMENT,
     `code` VARCHAR(20) NOT NULL DEFAULT '',
     `type_id` INT(11) NOT NULL DEFAULT 0,
     `name` VARCHAR(20) NOT NULL DEFAULT '',
     `value` VARCHAR(50) NOT NULL DEFAULT '',
     `status` TINYINT(1) NOT NULL DEFAULT 0,
     `create_time` INT(11) NOT NULL DEFAULT 0,
     `update_time` INT(11) NOT NULL DEFAULT 0,
     `remark` TEXT NOT NULL,
     PRIMARY KEY (`id`),
     UNIQUE KEY code (`code`),
     FOREIGN KEY (type_id) REFERENCES sys_dictionary_type(`id`)
)ENGINE =innodb AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

#sys_dictionary insert
INSERT INTO `sys_dictionary` (`code`,`type_id`,`name`,`value`,`status`,`create_time`,`update_time`,`remark`) VALUES ('SYSTEM_NAME','1','系统名称','CI-XIN','1',unix_timestamp(now()),unix_timestamp(now()),'系统名称'),
  ('SYSTEM_VERSION','1','系统版本','1.0','1',unix_timestamp(now()),unix_timestamp(now()),'系统版本');

#菜单表
DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE `sys_menu`(
     `id` INT(11) NOT NULL AUTO_INCREMENT,
     `name` VARCHAR(20) NOT NULL DEFAULT '',
     `parent_id` INT(11) NOT NULL DEFAULT 0,
     `route` VARCHAR(255) NOT NULL DEFAULT '',
     `icon`  VARCHAR(30) NOT NULL DEFAULT '',
     `visible` TINYINT(1) NOT NULL DEFAULT 0,
     `sort` INT(8) NOT NULL DEFAULT 0,
     `create_time` INT(11) NOT NULL DEFAULT 0,
     `update_time` INT(11) NOT NULL DEFAULT 0,
     PRIMARY KEY (`id`),
     KEY p_id(`parent_id`)
)ENGINE =innodb AUTO_INCREMENT=1 DEFAULT CHARSET =utf8;

#sys_menu insert
INSERT INTO `sys_menu` (`name`,`parent_id`,`route`,`icon`,`visible`,`sort`,`create_time`,`update_time`) VALUES ('业务管理',0,'/business/index','&#xe63c;','1','1',unix_timestamp(now()),unix_timestamp(now())),
('用户中心',0,'/user/index','&#xe612;','1','2',unix_timestamp(now()),unix_timestamp(now())),
('系统设置',0,'/config/index','&#xe620;','1','3',unix_timestamp(now()),unix_timestamp(now()));

INSERT INTO `sys_menu` (`name`,`parent_id`,`route`,`icon`,`visible`,`sort`,`create_time`,`update_time`) VALUES ('文章列表',1,'/article/index','&#xe705;','1','4',unix_timestamp(now()),unix_timestamp(now()));
INSERT INTO `sys_menu` (`name`,`parent_id`,`route`,`icon`,`visible`,`sort`,`create_time`,`update_time`) VALUES ('图片列表',1,'/picture/index','&#xe64a;','1','5',unix_timestamp(now()),unix_timestamp(now()));







