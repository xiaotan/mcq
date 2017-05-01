/*
SQLyog Ultimate - MySQL GUI v8.21 
MySQL - 5.5.5-10.1.8-MariaDB : Database - mcq
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`mcq` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `mcq`;

/*Table structure for table `mc_admin_access` */

DROP TABLE IF EXISTS `mc_admin_access`;

CREATE TABLE `mc_admin_access` (
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '模型名称',
  `group` varchar(16) NOT NULL DEFAULT '' COMMENT '权限分组标识',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `nid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '授权节点id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='统一授权表';

/*Data for the table `mc_admin_access` */

/*Table structure for table `mc_admin_action` */

DROP TABLE IF EXISTS `mc_admin_action`;

CREATE TABLE `mc_admin_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '所属模块名',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '行为标题',
  `remark` varchar(128) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='系统行为表';

/*Data for the table `mc_admin_action` */

insert  into `mc_admin_action`(`id`,`module`,`name`,`title`,`remark`,`rule`,`log`,`status`,`create_time`,`update_time`) values (1,'user','user_add','添加用户','添加用户','','[user|get_nickname] 添加了用户：[record|get_nickname]',1,1480156399,1480163853),(2,'user','user_edit','编辑用户','编辑用户','','[user|get_nickname] 编辑了用户：[details]',1,1480164578,1480297748),(3,'user','user_delete','删除用户','删除用户','','[user|get_nickname] 删除了用户：[details]',1,1480168582,1480168616),(4,'user','user_enable','启用用户','启用用户','','[user|get_nickname] 启用了用户：[details]',1,1480169185,1480169185),(5,'user','user_disable','禁用用户','禁用用户','','[user|get_nickname] 禁用了用户：[details]',1,1480169214,1480170581),(6,'user','user_access','用户授权','用户授权','','[user|get_nickname] 对用户：[record|get_nickname] 进行了授权操作。详情：[details]',1,1480221441,1480221563),(7,'user','role_add','添加角色','添加角色','','[user|get_nickname] 添加了角色：[details]',1,1480251473,1480251473),(8,'user','role_edit','编辑角色','编辑角色','','[user|get_nickname] 编辑了角色：[details]',1,1480252369,1480252369),(9,'user','role_delete','删除角色','删除角色','','[user|get_nickname] 删除了角色：[details]',1,1480252580,1480252580),(10,'user','role_enable','启用角色','启用角色','','[user|get_nickname] 启用了角色：[details]',1,1480252620,1480252620),(11,'user','role_disable','禁用角色','禁用角色','','[user|get_nickname] 禁用了角色：[details]',1,1480252651,1480252651),(12,'user','attachment_enable','启用附件','启用附件','','[user|get_nickname] 启用了附件：附件ID([details])',1,1480253226,1480253332),(13,'user','attachment_disable','禁用附件','禁用附件','','[user|get_nickname] 禁用了附件：附件ID([details])',1,1480253267,1480253340),(14,'user','attachment_delete','删除附件','删除附件','','[user|get_nickname] 删除了附件：附件ID([details])',1,1480253323,1480253323),(15,'admin','config_add','添加配置','添加配置','','[user|get_nickname] 添加了配置，[details]',1,1480296196,1480296196),(16,'admin','config_edit','编辑配置','编辑配置','','[user|get_nickname] 编辑了配置：[details]',1,1480296960,1480296960),(17,'admin','config_enable','启用配置','启用配置','','[user|get_nickname] 启用了配置：[details]',1,1480298479,1480298479),(18,'admin','config_disable','禁用配置','禁用配置','','[user|get_nickname] 禁用了配置：[details]',1,1480298506,1480298506),(19,'admin','config_delete','删除配置','删除配置','','[user|get_nickname] 删除了配置：[details]',1,1480298532,1480298532),(20,'admin','database_export','备份数据库','备份数据库','','[user|get_nickname] 备份了数据库：[details]',1,1480298946,1480298946),(21,'admin','database_import','还原数据库','还原数据库','','[user|get_nickname] 还原了数据库：[details]',1,1480301990,1480302022),(22,'admin','database_optimize','优化数据表','优化数据表','','[user|get_nickname] 优化了数据表：[details]',1,1480302616,1480302616),(23,'admin','database_repair','修复数据表','修复数据表','','[user|get_nickname] 修复了数据表：[details]',1,1480302798,1480302798),(24,'admin','database_backup_delete','删除数据库备份','删除数据库备份','','[user|get_nickname] 删除了数据库备份：[details]',1,1480302870,1480302870),(25,'admin','hook_add','添加钩子','添加钩子','','[user|get_nickname] 添加了钩子：[details]',1,1480303198,1480303198),(26,'admin','hook_edit','编辑钩子','编辑钩子','','[user|get_nickname] 编辑了钩子：[details]',1,1480303229,1480303229),(27,'admin','hook_delete','删除钩子','删除钩子','','[user|get_nickname] 删除了钩子：[details]',1,1480303264,1480303264),(28,'admin','hook_enable','启用钩子','启用钩子','','[user|get_nickname] 启用了钩子：[details]',1,1480303294,1480303294),(29,'admin','hook_disable','禁用钩子','禁用钩子','','[user|get_nickname] 禁用了钩子：[details]',1,1480303409,1480303409),(30,'admin','menu_add','添加节点','添加节点','','[user|get_nickname] 添加了节点：[details]',1,1480305468,1480305468),(31,'admin','menu_edit','编辑节点','编辑节点','','[user|get_nickname] 编辑了节点：[details]',1,1480305513,1480305513),(32,'admin','menu_delete','删除节点','删除节点','','[user|get_nickname] 删除了节点：[details]',1,1480305562,1480305562),(33,'admin','menu_enable','启用节点','启用节点','','[user|get_nickname] 启用了节点：[details]',1,1480305630,1480305630),(34,'admin','menu_disable','禁用节点','禁用节点','','[user|get_nickname] 禁用了节点：[details]',1,1480305659,1480305659),(35,'admin','module_install','安装模块','安装模块','','[user|get_nickname] 安装了模块：[details]',1,1480307558,1480307558),(36,'admin','module_uninstall','卸载模块','卸载模块','','[user|get_nickname] 卸载了模块：[details]',1,1480307588,1480307588),(37,'admin','module_enable','启用模块','启用模块','','[user|get_nickname] 启用了模块：[details]',1,1480307618,1480307618),(38,'admin','module_disable','禁用模块','禁用模块','','[user|get_nickname] 禁用了模块：[details]',1,1480307653,1480307653),(39,'admin','module_export','导出模块','导出模块','','[user|get_nickname] 导出了模块：[details]',1,1480307682,1480307682),(40,'admin','packet_install','安装数据包','安装数据包','','[user|get_nickname] 安装了数据包：[details]',1,1480308342,1480308342),(41,'admin','packet_uninstall','卸载数据包','卸载数据包','','[user|get_nickname] 卸载了数据包：[details]',1,1480308372,1480308372),(42,'admin','system_config_update','更新系统设置','更新系统设置','','[user|get_nickname] 更新了系统设置：[details]',1,1480309555,1480309642),(43,'cms','slider_delete','删除滚动图片','删除滚动图片','','[user|get_nickname] 删除了滚动图片：[details]',1,1493645576,1493645576),(44,'cms','slider_edit','编辑滚动图片','编辑滚动图片','','[user|get_nickname] 编辑了滚动图片：[details]',1,1493645576,1493645576),(45,'cms','slider_add','添加滚动图片','添加滚动图片','','[user|get_nickname] 添加了滚动图片：[details]',1,1493645576,1493645576),(46,'cms','document_delete','删除文档','删除文档','','[user|get_nickname] 删除了文档：[details]',1,1493645576,1493645576),(47,'cms','document_restore','还原文档','还原文档','','[user|get_nickname] 还原了文档：[details]',1,1493645576,1493645576),(48,'cms','nav_disable','禁用导航','禁用导航','','[user|get_nickname] 禁用了导航：[details]',1,1493645576,1493645576),(49,'cms','nav_enable','启用导航','启用导航','','[user|get_nickname] 启用了导航：[details]',1,1493645576,1493645576),(50,'cms','nav_delete','删除导航','删除导航','','[user|get_nickname] 删除了导航：[details]',1,1493645576,1493645576),(51,'cms','nav_edit','编辑导航','编辑导航','','[user|get_nickname] 编辑了导航：[details]',1,1493645576,1493645576),(52,'cms','nav_add','添加导航','添加导航','','[user|get_nickname] 添加了导航：[details]',1,1493645576,1493645576),(53,'cms','model_disable','禁用内容模型','禁用内容模型','','[user|get_nickname] 禁用了内容模型：[details]',1,1493645576,1493645576),(54,'cms','model_enable','启用内容模型','启用内容模型','','[user|get_nickname] 启用了内容模型：[details]',1,1493645576,1493645576),(55,'cms','model_delete','删除内容模型','删除内容模型','','[user|get_nickname] 删除了内容模型：[details]',1,1493645576,1493645576),(56,'cms','model_edit','编辑内容模型','编辑内容模型','','[user|get_nickname] 编辑了内容模型：[details]',1,1493645576,1493645576),(57,'cms','model_add','添加内容模型','添加内容模型','','[user|get_nickname] 添加了内容模型：[details]',1,1493645576,1493645576),(58,'cms','menu_disable','禁用导航菜单','禁用导航菜单','','[user|get_nickname] 禁用了导航菜单：[details]',1,1493645576,1493645576),(59,'cms','menu_enable','启用导航菜单','启用导航菜单','','[user|get_nickname] 启用了导航菜单：[details]',1,1493645576,1493645576),(60,'cms','menu_delete','删除导航菜单','删除导航菜单','','[user|get_nickname] 删除了导航菜单：[details]',1,1493645576,1493645576),(61,'cms','menu_edit','编辑导航菜单','编辑导航菜单','','[user|get_nickname] 编辑了导航菜单：[details]',1,1493645576,1493645576),(62,'cms','menu_add','添加导航菜单','添加导航菜单','','[user|get_nickname] 添加了导航菜单：[details]',1,1493645576,1493645576),(63,'cms','link_disable','禁用友情链接','禁用友情链接','','[user|get_nickname] 禁用了友情链接：[details]',1,1493645576,1493645576),(64,'cms','link_enable','启用友情链接','启用友情链接','','[user|get_nickname] 启用了友情链接：[details]',1,1493645576,1493645576),(65,'cms','link_delete','删除友情链接','删除友情链接','','[user|get_nickname] 删除了友情链接：[details]',1,1493645576,1493645576),(66,'cms','link_edit','编辑友情链接','编辑友情链接','','[user|get_nickname] 编辑了友情链接：[details]',1,1493645576,1493645576),(67,'cms','link_add','添加友情链接','添加友情链接','','[user|get_nickname] 添加了友情链接：[details]',1,1493645576,1493645576),(68,'cms','field_disable','禁用模型字段','禁用模型字段','','[user|get_nickname] 禁用了模型字段：[details]',1,1493645576,1493645576),(69,'cms','field_enable','启用模型字段','启用模型字段','','[user|get_nickname] 启用了模型字段：[details]',1,1493645576,1493645576),(70,'cms','field_delete','删除模型字段','删除模型字段','','[user|get_nickname] 删除了模型字段：[details]',1,1493645576,1493645576),(71,'cms','field_edit','编辑模型字段','编辑模型字段','','[user|get_nickname] 编辑了模型字段：[details]',1,1493645576,1493645576),(72,'cms','field_add','添加模型字段','添加模型字段','','[user|get_nickname] 添加了模型字段：[details]',1,1493645576,1493645576),(73,'cms','column_disable','禁用栏目','禁用栏目','','[user|get_nickname] 禁用了栏目：[details]',1,1493645576,1493645576),(74,'cms','column_enable','启用栏目','启用栏目','','[user|get_nickname] 启用了栏目：[details]',1,1493645576,1493645576),(75,'cms','column_delete','删除栏目','删除栏目','','[user|get_nickname] 删除了栏目：[details]',1,1493645576,1493645576),(76,'cms','column_edit','编辑栏目','编辑栏目','','[user|get_nickname] 编辑了栏目：[details]',1,1493645576,1493645576),(77,'cms','column_add','添加栏目','添加栏目','','[user|get_nickname] 添加了栏目：[details]',1,1493645576,1493645576),(78,'cms','advert_type_disable','禁用广告分类','禁用广告分类','','[user|get_nickname] 禁用了广告分类：[details]',1,1493645576,1493645576),(79,'cms','advert_type_enable','启用广告分类','启用广告分类','','[user|get_nickname] 启用了广告分类：[details]',1,1493645576,1493645576),(80,'cms','advert_type_delete','删除广告分类','删除广告分类','','[user|get_nickname] 删除了广告分类：[details]',1,1493645576,1493645576),(81,'cms','advert_type_edit','编辑广告分类','编辑广告分类','','[user|get_nickname] 编辑了广告分类：[details]',1,1493645576,1493645576),(82,'cms','advert_type_add','添加广告分类','添加广告分类','','[user|get_nickname] 添加了广告分类：[details]',1,1493645576,1493645576),(83,'cms','advert_disable','禁用广告','禁用广告','','[user|get_nickname] 禁用了广告：[details]',1,1493645576,1493645576),(84,'cms','advert_enable','启用广告','启用广告','','[user|get_nickname] 启用了广告：[details]',1,1493645576,1493645576),(85,'cms','advert_delete','删除广告','删除广告','','[user|get_nickname] 删除了广告：[details]',1,1493645576,1493645576),(86,'cms','advert_edit','编辑广告','编辑广告','','[user|get_nickname] 编辑了广告：[details]',1,1493645576,1493645576),(87,'cms','advert_add','添加广告','添加广告','','[user|get_nickname] 添加了广告：[details]',1,1493645576,1493645576),(88,'cms','document_disable','禁用文档','禁用文档','','[user|get_nickname] 禁用了文档：[details]',1,1493645576,1493645576),(89,'cms','document_enable','启用文档','启用文档','','[user|get_nickname] 启用了文档：[details]',1,1493645576,1493645576),(90,'cms','document_trash','回收文档','回收文档','','[user|get_nickname] 回收了文档：[details]',1,1493645576,1493645576),(91,'cms','document_edit','编辑文档','编辑文档','','[user|get_nickname] 编辑了文档：[details]',1,1493645576,1493645576),(92,'cms','document_add','添加文档','添加文档','','[user|get_nickname] 添加了文档：[details]',1,1493645576,1493645576),(93,'cms','slider_enable','启用滚动图片','启用滚动图片','','[user|get_nickname] 启用了滚动图片：[details]',1,1493645576,1493645576),(94,'cms','slider_disable','禁用滚动图片','禁用滚动图片','','[user|get_nickname] 禁用了滚动图片：[details]',1,1493645576,1493645576),(95,'cms','support_add','添加客服','添加客服','','[user|get_nickname] 添加了客服：[details]',1,1493645576,1493645576),(96,'cms','support_edit','编辑客服','编辑客服','','[user|get_nickname] 编辑了客服：[details]',1,1493645576,1493645576),(97,'cms','support_delete','删除客服','删除客服','','[user|get_nickname] 删除了客服：[details]',1,1493645576,1493645576),(98,'cms','support_enable','启用客服','启用客服','','[user|get_nickname] 启用了客服：[details]',1,1493645576,1493645576),(99,'cms','support_disable','禁用客服','禁用客服','','[user|get_nickname] 禁用了客服：[details]',1,1493645576,1493645576);

/*Table structure for table `mc_admin_attachment` */

DROP TABLE IF EXISTS `mc_admin_attachment`;

CREATE TABLE `mc_admin_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `module` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名，由哪个模块上传的',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `mime` varchar(64) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` char(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `download` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

/*Data for the table `mc_admin_attachment` */

/*Table structure for table `mc_admin_config` */

DROP TABLE IF EXISTS `mc_admin_config`;

CREATE TABLE `mc_admin_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `group` varchar(32) NOT NULL DEFAULT '' COMMENT '配置分组',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '类型',
  `value` text NOT NULL COMMENT '配置值',
  `options` text NOT NULL COMMENT '配置项',
  `tips` varchar(256) NOT NULL DEFAULT '' COMMENT '配置提示',
  `ajax_url` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框ajax地址',
  `next_items` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框的下级下拉框名，多个以逗号隔开',
  `param` varchar(32) NOT NULL DEFAULT '' COMMENT '联动下拉框请求参数名',
  `format` varchar(32) NOT NULL DEFAULT '' COMMENT '格式，用于格式文本',
  `table` varchar(32) NOT NULL DEFAULT '' COMMENT '表名，只用于快速联动类型',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '联动级别，只用于快速联动类型',
  `key` varchar(32) NOT NULL DEFAULT '' COMMENT '键字段，只用于快速联动类型',
  `option` varchar(32) NOT NULL DEFAULT '' COMMENT '值字段，只用于快速联动类型',
  `pid` varchar(32) NOT NULL DEFAULT '' COMMENT '父级id字段，只用于快速联动类型',
  `ak` varchar(32) NOT NULL DEFAULT '' COMMENT '百度地图appkey',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

/*Data for the table `mc_admin_config` */

insert  into `mc_admin_config`(`id`,`name`,`title`,`group`,`type`,`value`,`options`,`tips`,`ajax_url`,`next_items`,`param`,`format`,`table`,`level`,`key`,`option`,`pid`,`ak`,`create_time`,`update_time`,`sort`,`status`) values (1,'web_site_status','站点开关','base','switch','1','','站点关闭后将不能访问，后台可正常登录','','','','','',2,'','','','',1475240395,1477403914,1,1),(2,'web_site_title','站点标题','base','text','海豚PHP','','调用方式：<code>config(\'web_site_title\')</code>','','','','','',2,'','','','',1475240646,1477710341,2,1),(3,'web_site_slogan','站点标语','base','text','海豚PHP，极简、极速、极致','','站点口号，调用方式：<code>config(\'web_site_slogan\')</code>','','','','','',2,'','','','',1475240994,1477710357,3,1),(4,'web_site_logo','站点LOGO','base','image','','','','','','','','',2,'','','','',1475241067,1475241067,4,1),(5,'web_site_description','站点描述','base','textarea','','','网站描述，有利于搜索引擎抓取相关信息','','','','','',2,'','','','',1475241186,1475241186,6,1),(6,'web_site_keywords','站点关键词','base','text','海豚PHP、PHP开发框架、后台框架','','网站搜索引擎关键字','','','','','',2,'','','','',1475241328,1475241328,7,1),(7,'web_site_copyright','版权信息','base','text','Copyright © 2015-2016 DolphinPHP All rights reserved.','','调用方式：<code>config(\'web_site_copyright\')</code>','','','','','',2,'','','','',1475241416,1477710383,8,1),(8,'web_site_icp','备案信息','base','text','','','调用方式：<code>config(\'web_site_icp\')</code>','','','','','',2,'','','','',1475241441,1477710441,9,1),(9,'web_site_statistics','站点统计','base','textarea','','','网站统计代码，支持百度、Google、cnzz等，调用方式：<code>config(\'web_site_statistics\')</code>','','','','','',2,'','','','',1475241498,1477710455,10,1),(10,'config_group','配置分组','system','array','base:基本\r\nsystem:系统\r\nupload:上传\r\ndevelop:开发\r\ndatabase:数据库','','','','','','','',2,'','','','',1475241716,1477649446,100,1),(11,'form_item_type','配置类型','system','array','text:单行文本\r\ntextarea:多行文本\r\nstatic:静态文本\r\npassword:密码\r\ncheckbox:复选框\r\nradio:单选按钮\r\ntext:单行文本\r\ntextarea:多行文本\r\nstatic:静态文本\r\npassword:密码\r\ncheckbox:复选框\r\nradio:单选按钮\r\ndate:日期\r\ndatetime:日期+时间\r\nhidden:隐藏\r\nswitch:开关\r\narray:数组\r\nselect:下拉框\r\nlinkage:普通联动下拉框\r\nlinkages:快速联动下拉框\r\nimage:单张图片\r\nimages:多张图片\r\nfile:单个文件\r\nfiles:多个文件\r\nueditor:UEditor 编辑器\r\nwangeditor:wangEditor 编辑器\r\neditormd:markdown 编辑器\r\nicon:字体图标\r\ntags:标签\r\nnumber:数字\r\nbmap:百度地图\r\ncolorpicker:取色器\r\njcrop:图片裁剪\r\nmasked:格式文本\r\nrange:范围\r\ntime:时间','','','','','','','',2,'','','','',1475241835,1476032385,100,1),(12,'upload_file_size','文件上传大小限制','upload','text','0','','0为不限制大小，单位：kb','','','','','',2,'','','','',1475241897,1477663520,100,1),(13,'upload_file_ext','允许上传的文件后缀','upload','tags','doc,docx,xls,xlsx,ppt,pptx,pdf,wps,txt,rar,zip,gz,bz2,7z','','多个后缀用逗号隔开，不填写则不限制类型','','','','','',2,'','','','',1475241975,1477649489,100,1),(14,'upload_image_size','图片上传大小限制','upload','text','0','','0为不限制大小，单位：kb','','','','','',2,'','','','',1475242015,1477663529,100,1),(15,'upload_image_ext','允许上传的图片后缀','upload','tags','gif,jpg,jpeg,bmp,png','','多个后缀用逗号隔开，不填写则不限制类型','','','','','',2,'','','','',1475242056,1477649506,100,1),(16,'list_rows','分页数量','system','number','20','','每页的记录数','','','','','',2,'','','','',1475242066,1476074507,101,1),(17,'system_color','后台配色方案','system','radio','default','default:Default\r\namethyst:Amethyst\r\ncity:City\r\nflat:Flat\r\nmodern:Modern\r\nsmooth:Smooth','','','','','','',2,'','','','',1475250066,1477316689,102,1),(18,'develop_mode','开发模式','develop','radio','1','0:关闭\r\n1:开启','','','','','','',2,'','','','',1476864205,1476864231,100,1),(19,'app_trace','显示页面Trace','develop','radio','0','0:否\r\n1:是','','','','','','',2,'','','','',1476866355,1476866355,100,1),(21,'data_backup_path','数据库备份根路径','database','text','./data/','','路径必须以 / 结尾','','','','','',2,'','','','',1477017745,1477018467,100,1),(22,'data_backup_part_size','数据库备份卷大小','database','text','20971520','','该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M','','','','','',2,'','','','',1477017886,1477017886,100,1),(23,'data_backup_compress','数据库备份文件是否启用压缩','database','radio','1','0:否\r\n1:是','压缩备份文件需要PHP环境支持 <code>gzopen</code>, <code>gzwrite</code>函数','','','','','',2,'','','','',1477017978,1477018172,100,1),(24,'data_backup_compress_level','数据库备份文件压缩级别','database','radio','9','1:最低\r\n4:一般\r\n9:最高','数据库备份文件的压缩级别，该配置在开启压缩时生效','','','','','',2,'','','','',1477018083,1477018083,100,1),(25,'top_menu_max','顶部导航模块数量','system','text','10','','设置顶部导航默认显示的模块数量','','','','','',2,'','','','',1477579289,1477579289,103,1),(26,'web_site_logo_text','站点LOGO文字','base','image','','','','','','','','',2,'','','','',1477620643,1477620643,5,1),(27,'upload_image_thumb','缩略图尺寸','upload','text','','','不填写则不生成缩略图，如需生成 <code>300x300</code> 的缩略图，则填写 <code>300,300</code> ，请注意，逗号必须是英文逗号','','','','','',2,'','','','',1477644150,1477649513,100,1),(28,'upload_image_thumb_type','缩略图裁剪类型','upload','radio','1','1:等比例缩放\r\n2:缩放后填充\r\n3:居中裁剪\r\n4:左上角裁剪\r\n5:右下角裁剪\r\n6:固定尺寸缩放','该项配置只有在启用生成缩略图时才生效','','','','','',2,'','','','',1477646271,1477649521,100,1),(29,'upload_thumb_water','添加水印','upload','switch','0','','','','','','','',2,'','','','',1477649648,1477649648,100,1),(30,'upload_thumb_water_pic','水印图片','upload','image','','','只有开启水印功能才生效','','','','','',2,'','','','',1477656390,1477656390,100,1),(31,'upload_thumb_water_position','水印位置','upload','radio','9','1:左上角\r\n2:上居中\r\n3:右上角\r\n4:左居中\r\n5:居中\r\n6:右居中\r\n7:左下角\r\n8:下居中\r\n9:右下角','只有开启水印功能才生效','','','','','',2,'','','','',1477656528,1477656528,100,1),(32,'upload_thumb_water_alpha','水印透明度','upload','text','50','','请输入0~100之间的数字，数字越小，透明度越高','','','','','',2,'','','','',1477656714,1477661309,100,1),(33,'wipe_cache_type','清除缓存类型','system','checkbox','TEMP_PATH','TEMP_PATH:应用缓存\r\nLOG_PATH:应用日志\r\nCACHE_PATH:项目模板缓存','清除缓存时，要删除的缓存类型','','','','','',2,'','','','',1477727305,1477727305,100,1),(34,'captcha_signin','后台验证码开关','system','switch','0','','后台登录时是否需要验证码','','','','','',2,'','','','',1478771958,1478771958,99,1),(35,'home_default_module','前台默认模块','system','select','index','','前台默认访问的模块，该模块必须有Index控制器和index方法','','','','','',0,'','','','',1486714723,1486715620,104,1),(36,'minify_status','开启minify','system','switch','0','','开启minify会压缩合并js、css文件，可以减少资源请求次数，如果不支持minify，可关闭','','','','','',0,'','','','',1487035843,1487035843,99,1);

/*Table structure for table `mc_admin_hook` */

DROP TABLE IF EXISTS `mc_admin_hook`;

CREATE TABLE `mc_admin_hook` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `plugin` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子来自哪个插件',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子描述',
  `system` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统钩子',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='钩子表';

/*Data for the table `mc_admin_hook` */

insert  into `mc_admin_hook`(`id`,`name`,`plugin`,`description`,`system`,`create_time`,`update_time`,`status`) values (1,'admin_index','','后台首页',1,1468174214,1477757518,1),(2,'plugin_index_tab_list','','插件扩展tab钩子',1,1468174214,1468174214,1),(3,'module_index_tab_list','','模块扩展tab钩子',1,1468174214,1468174214,1),(4,'page_tips','','每个页面的提示',1,1468174214,1468174214,1),(5,'signin_footer','','登录页面底部钩子',1,1479269315,1479269315,1),(6,'signin_captcha','','登录页面验证码钩子',1,1479269315,1479269315,1),(7,'signin','','登录控制器钩子',1,1479386875,1479386875,1);

/*Table structure for table `mc_admin_hook_plugin` */

DROP TABLE IF EXISTS `mc_admin_hook_plugin`;

CREATE TABLE `mc_admin_hook_plugin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hook` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子id',
  `plugin` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标识',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='钩子-插件对应表';

/*Data for the table `mc_admin_hook_plugin` */

insert  into `mc_admin_hook_plugin`(`id`,`hook`,`plugin`,`create_time`,`update_time`,`sort`,`status`) values (1,'admin_index','SystemInfo',1477757503,1477757503,1,1),(2,'admin_index','DevTeam',1477755780,1477755780,2,1);

/*Table structure for table `mc_admin_log` */

DROP TABLE IF EXISTS `mc_admin_log`;

CREATE TABLE `mc_admin_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` longtext NOT NULL COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

/*Data for the table `mc_admin_log` */

insert  into `mc_admin_log`(`id`,`action_id`,`user_id`,`action_ip`,`model`,`record_id`,`remark`,`status`,`create_time`) values (1,35,1,2130706433,'admin_module',0,'超级管理员 安装了模块：门户',1,1493645574),(2,35,1,2130706433,'admin_module',0,'超级管理员 安装了模块：商家',1,1493647011),(3,35,1,2130706433,'admin_module',0,'超级管理员 安装了模块：会员',1,1493647137),(4,30,1,2130706433,'admin_menu',307,'超级管理员 添加了节点：所属模块(business),所属节点ID(0),节点标题(商家),节点链接(business/index/index)',1,1493652516),(5,30,1,2130706433,'admin_menu',308,'超级管理员 添加了节点：所属模块(member),所属节点ID(0),节点标题(会员),节点链接(member/index/index)',1,1493652583);

/*Table structure for table `mc_admin_menu` */

DROP TABLE IF EXISTS `mc_admin_menu`;

CREATE TABLE `mc_admin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单id',
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '模块名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `url_type` varchar(16) NOT NULL DEFAULT '' COMMENT '链接类型（link：外链，module：模块）',
  `url_value` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `url_target` varchar(16) NOT NULL DEFAULT '_self' COMMENT '链接打开方式：_blank,_self',
  `online_hide` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '网站上线后是否隐藏',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system_menu` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统菜单，系统菜单不可删除',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=309 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

/*Data for the table `mc_admin_menu` */

insert  into `mc_admin_menu`(`id`,`pid`,`module`,`title`,`icon`,`url_type`,`url_value`,`url_target`,`online_hide`,`create_time`,`update_time`,`sort`,`system_menu`,`status`) values (1,0,'admin','首页','fa fa-fw fa-home','module','admin/index/index','_self',0,1467617722,1493645605,1,1,1),(2,1,'admin','快捷操作','fa fa-fw fa-folder-open-o','module','','_self',0,1467618170,1477710695,1,1,1),(3,2,'admin','清空缓存','fa fa-fw fa-trash-o','module','admin/index/wipecache','_self',0,1467618273,1489049773,3,1,1),(4,0,'admin','系统','fa fa-fw fa-gear','module','admin/system/index','_self',0,1467618361,1493645605,4,1,1),(5,4,'admin','系统功能','si si-wrench','module','','_self',0,1467618441,1477710695,1,1,1),(6,5,'admin','系统设置','fa fa-fw fa-wrench','module','admin/system/index','_self',0,1467618490,1477710695,1,1,1),(7,5,'admin','配置管理','fa fa-fw fa-gears','module','admin/config/index','_self',0,1467618618,1477710695,2,1,1),(8,7,'admin','新增','','module','admin/config/add','_self',0,1467618648,1477710695,1,1,1),(9,7,'admin','编辑','','module','admin/config/edit','_self',0,1467619566,1477710695,2,1,1),(10,7,'admin','删除','','module','admin/config/delete','_self',0,1467619583,1477710695,3,1,1),(11,7,'admin','启用','','module','admin/config/enable','_self',0,1467619609,1477710695,4,1,1),(12,7,'admin','禁用','','module','admin/config/disable','_self',0,1467619637,1477710695,5,1,1),(13,5,'admin','节点管理','fa fa-fw fa-bars','module','admin/menu/index','_self',0,1467619882,1477710695,3,1,1),(14,13,'admin','新增','','module','admin/menu/add','_self',0,1467619902,1477710695,1,1,1),(15,13,'admin','编辑','','module','admin/menu/edit','_self',0,1467620331,1477710695,2,1,1),(16,13,'admin','删除','','module','admin/menu/delete','_self',0,1467620363,1477710695,3,1,1),(17,13,'admin','启用','','module','admin/menu/enable','_self',0,1467620386,1477710695,4,1,1),(18,13,'admin','禁用','','module','admin/menu/disable','_self',0,1467620404,1477710695,5,1,1),(19,68,'user','权限管理','fa fa-fw fa-key','module','','_self',0,1467688065,1477710702,1,1,1),(20,19,'user','用户管理','fa fa-fw fa-user','module','user/index/index','_self',0,1467688137,1477710702,1,1,1),(21,20,'user','新增','','module','user/index/add','_self',0,1467688177,1477710702,1,1,1),(22,20,'user','编辑','','module','user/index/edit','_self',0,1467688202,1477710702,2,1,1),(23,20,'user','删除','','module','user/index/delete','_self',0,1467688219,1477710702,3,1,1),(24,20,'user','启用','','module','user/index/enable','_self',0,1467688238,1477710702,4,1,1),(25,20,'user','禁用','','module','user/index/disable','_self',0,1467688256,1477710702,5,1,1),(211,64,'admin','日志详情','','module','admin/log/details','_self',0,1480299320,1480299320,100,0,1),(32,4,'admin','扩展中心','si si-social-dropbox','module','','_self',0,1467688853,1477710695,2,1,1),(33,32,'admin','模块管理','fa fa-fw fa-th-large','module','admin/module/index','_self',0,1467689008,1477710695,1,1,1),(34,33,'admin','导入','','module','admin/module/import','_self',0,1467689153,1477710695,1,1,1),(35,33,'admin','导出','','module','admin/module/export','_self',0,1467689173,1477710695,2,1,1),(36,33,'admin','安装','','module','admin/module/install','_self',0,1467689192,1477710695,3,1,1),(37,33,'admin','卸载','','module','admin/module/uninstall','_self',0,1467689241,1477710695,4,1,1),(38,33,'admin','启用','','module','admin/module/enable','_self',0,1467689294,1477710695,5,1,1),(39,33,'admin','禁用','','module','admin/module/disable','_self',0,1467689312,1477710695,6,1,1),(40,33,'admin','更新','','module','admin/module/update','_self',0,1467689341,1477710695,7,1,1),(41,32,'admin','插件管理','fa fa-fw fa-puzzle-piece','module','admin/plugin/index','_self',0,1467689527,1477710695,2,1,1),(42,41,'admin','导入','','module','admin/plugin/import','_self',0,1467689650,1477710695,1,1,1),(43,41,'admin','导出','','module','admin/plugin/export','_self',0,1467689665,1477710695,2,1,1),(44,41,'admin','安装','','module','admin/plugin/install','_self',0,1467689680,1477710695,3,1,1),(45,41,'admin','卸载','','module','admin/plugin/uninstall','_self',0,1467689700,1477710695,4,1,1),(46,41,'admin','启用','','module','admin/plugin/enable','_self',0,1467689730,1477710695,5,1,1),(47,41,'admin','禁用','','module','admin/plugin/disable','_self',0,1467689747,1477710695,6,1,1),(48,41,'admin','设置','','module','admin/plugin/config','_self',0,1467689789,1477710695,7,1,1),(49,41,'admin','管理','','module','admin/plugin/manage','_self',0,1467689846,1477710695,8,1,1),(50,5,'admin','附件管理','fa fa-fw fa-cloud-upload','module','admin/attachment/index','_self',0,1467690161,1477710695,4,1,1),(51,70,'admin','文件上传','','module','admin/attachment/upload','_self',0,1467690240,1489049773,1,1,1),(52,50,'admin','下载','','module','admin/attachment/download','_self',0,1467690334,1477710695,2,1,1),(53,50,'admin','启用','','module','admin/attachment/enable','_self',0,1467690352,1477710695,3,1,1),(54,50,'admin','禁用','','module','admin/attachment/disable','_self',0,1467690369,1477710695,4,1,1),(55,50,'admin','删除','','module','admin/attachment/delete','_self',0,1467690396,1477710695,5,1,1),(56,41,'admin','删除','','module','admin/plugin/delete','_self',0,1467858065,1477710695,11,1,1),(57,41,'admin','编辑','','module','admin/plugin/edit','_self',0,1467858092,1477710695,10,1,1),(60,41,'admin','新增','','module','admin/plugin/add','_self',0,1467858421,1477710695,9,1,1),(61,41,'admin','执行','','module','admin/plugin/execute','_self',0,1467879016,1477710695,14,1,1),(62,13,'admin','保存','','module','admin/menu/save','_self',0,1468073039,1477710695,6,1,1),(64,5,'admin','系统日志','fa fa-fw fa-book','module','admin/log/index','_self',0,1476111944,1477710695,6,0,1),(65,5,'admin','数据库管理','fa fa-fw fa-database','module','admin/database/index','_self',0,1476111992,1477710695,8,0,1),(66,32,'admin','数据包管理','fa fa-fw fa-database','module','admin/packet/index','_self',0,1476112326,1477710695,4,0,1),(67,19,'user','角色管理','fa fa-fw fa-users','module','user/role/index','_self',0,1476113025,1477710702,3,0,1),(68,0,'user','用户','fa fa-fw fa-user','module','user/index/index','_self',0,1476193348,1493645605,3,0,1),(69,32,'admin','钩子管理','fa fa-fw fa-anchor','module','admin/hook/index','_self',0,1476236193,1477710695,3,0,1),(70,2,'admin','后台首页','fa fa-fw fa-tachometer','module','admin/index/index','_self',0,1476237472,1489049773,1,0,1),(71,67,'user','新增','','module','user/role/add','_self',0,1476256935,1477710702,1,0,1),(72,67,'user','编辑','','module','user/role/edit','_self',0,1476256968,1477710702,2,0,1),(73,67,'user','删除','','module','user/role/delete','_self',0,1476256993,1477710702,3,0,1),(74,67,'user','启用','','module','user/role/enable','_self',0,1476257023,1477710702,4,0,1),(75,67,'user','禁用','','module','user/role/disable','_self',0,1476257046,1477710702,5,0,1),(76,20,'user','授权','','module','user/index/access','_self',0,1476375187,1477710702,6,0,1),(77,69,'admin','新增','','module','admin/hook/add','_self',0,1476668971,1477710695,1,0,1),(78,69,'admin','编辑','','module','admin/hook/edit','_self',0,1476669006,1477710695,2,0,1),(79,69,'admin','删除','','module','admin/hook/delete','_self',0,1476669375,1477710695,3,0,1),(80,69,'admin','启用','','module','admin/hook/enable','_self',0,1476669427,1477710695,4,0,1),(81,69,'admin','禁用','','module','admin/hook/disable','_self',0,1476669564,1477710695,5,0,1),(183,66,'admin','安装','','module','admin/packet/install','_self',0,1476851362,1477710695,1,0,1),(184,66,'admin','卸载','','module','admin/packet/uninstall','_self',0,1476851382,1477710695,2,0,1),(185,5,'admin','行为管理','fa fa-fw fa-bug','module','admin/action/index','_self',0,1476882441,1477710695,7,0,1),(186,185,'admin','新增','','module','admin/action/add','_self',0,1476884439,1477710695,1,0,1),(187,185,'admin','编辑','','module','admin/action/edit','_self',0,1476884464,1477710695,2,0,1),(188,185,'admin','启用','','module','admin/action/enable','_self',0,1476884493,1477710695,3,0,1),(189,185,'admin','禁用','','module','admin/action/disable','_self',0,1476884534,1477710695,4,0,1),(190,185,'admin','删除','','module','admin/action/delete','_self',0,1476884551,1477710695,5,0,1),(191,65,'admin','备份数据库','','module','admin/database/export','_self',0,1476972746,1477710695,1,0,1),(192,65,'admin','还原数据库','','module','admin/database/import','_self',0,1476972772,1477710695,2,0,1),(193,65,'admin','优化表','','module','admin/database/optimize','_self',0,1476972800,1477710695,3,0,1),(194,65,'admin','修复表','','module','admin/database/repair','_self',0,1476972825,1477710695,4,0,1),(195,65,'admin','删除备份','','module','admin/database/delete','_self',0,1476973457,1477710695,5,0,1),(210,41,'admin','快速编辑','','module','admin/plugin/quickedit','_self',0,1477713981,1477713981,100,0,1),(209,185,'admin','快速编辑','','module','admin/action/quickedit','_self',0,1477713939,1477713939,100,0,1),(208,7,'admin','快速编辑','','module','admin/config/quickedit','_self',0,1477713808,1477713808,100,0,1),(207,69,'admin','快速编辑','','module','admin/hook/quickedit','_self',0,1477713770,1477713770,100,0,1),(212,2,'admin','个人设置','fa fa-fw fa-user','module','admin/index/profile','_self',0,1489049767,1489049773,2,0,1),(213,70,'admin','检查版本更新','','module','admin/index/checkupdate','_self',0,1490588610,1490588610,100,0,1),(214,0,'cms','门户','fa fa-fw fa-newspaper-o','module','cms/index/index','_self',0,1493645576,1493645605,2,0,1),(215,214,'cms','常用操作','fa fa-fw fa-folder-open-o','module','','_self',0,1493645576,1493645576,100,0,1),(216,215,'cms','仪表盘','fa fa-fw fa-tachometer','module','cms/index/index','_self',0,1493645576,1493645576,100,0,1),(217,215,'cms','发布文档','fa fa-fw fa-plus','module','cms/document/add','_self',0,1493645576,1493645576,100,0,1),(218,215,'cms','文档列表','fa fa-fw fa-list','module','cms/document/index','_self',0,1493645576,1493645576,100,0,1),(219,218,'cms','编辑','','module','cms/document/edit','_self',0,1493645576,1493645576,100,0,1),(220,218,'cms','删除','','module','cms/document/delete','_self',0,1493645576,1493645576,100,0,1),(221,218,'cms','启用','','module','cms/document/enable','_self',0,1493645576,1493645576,100,0,1),(222,218,'cms','禁用','','module','cms/document/disable','_self',0,1493645576,1493645576,100,0,1),(223,218,'cms','快速编辑','','module','cms/document/quickedit','_self',0,1493645576,1493645576,100,0,1),(224,215,'cms','单页管理','fa fa-fw fa-file-word-o','module','cms/page/index','_self',0,1493645576,1493645576,100,0,1),(225,224,'cms','新增','','module','cms/page/add','_self',0,1493645576,1493645576,100,0,1),(226,224,'cms','编辑','','module','cms/page/edit','_self',0,1493645576,1493645576,100,0,1),(227,224,'cms','删除','','module','cms/page/delete','_self',0,1493645576,1493645576,100,0,1),(228,224,'cms','启用','','module','cms/page/enable','_self',0,1493645576,1493645576,100,0,1),(229,224,'cms','禁用','','module','cms/page/disable','_self',0,1493645576,1493645576,100,0,1),(230,224,'cms','快速编辑','','module','cms/page/quickedit','_self',0,1493645576,1493645576,100,0,1),(231,215,'cms','回收站','fa fa-fw fa-recycle','module','cms/recycle/index','_self',0,1493645576,1493645576,100,0,1),(232,231,'cms','删除','','module','cms/recycle/delete','_self',0,1493645576,1493645576,100,0,1),(233,231,'cms','还原','','module','cms/recycle/restore','_self',0,1493645576,1493645576,100,0,1),(234,214,'cms','内容管理','fa fa-fw fa-th-list','module','','_self',0,1493645576,1493645576,100,0,1),(235,214,'cms','营销管理','fa fa-fw fa-money','module','','_self',0,1493645576,1493645576,100,0,1),(236,235,'cms','广告管理','fa fa-fw fa-handshake-o','module','cms/advert/index','_self',0,1493645576,1493645576,100,0,1),(237,236,'cms','新增','','module','cms/advert/add','_self',0,1493645576,1493645576,100,0,1),(238,236,'cms','编辑','','module','cms/advert/edit','_self',0,1493645576,1493645576,100,0,1),(239,236,'cms','删除','','module','cms/advert/delete','_self',0,1493645576,1493645576,100,0,1),(240,236,'cms','启用','','module','cms/advert/enable','_self',0,1493645576,1493645576,100,0,1),(241,236,'cms','禁用','','module','cms/advert/disable','_self',0,1493645576,1493645576,100,0,1),(242,236,'cms','快速编辑','','module','cms/advert/quickedit','_self',0,1493645576,1493645576,100,0,1),(243,236,'cms','广告分类','','module','cms/advert_type/index','_self',0,1493645576,1493645576,100,0,1),(244,243,'cms','新增','','module','cms/advert_type/add','_self',0,1493645576,1493645576,100,0,1),(245,243,'cms','编辑','','module','cms/advert_type/edit','_self',0,1493645576,1493645576,100,0,1),(246,243,'cms','删除','','module','cms/advert_type/delete','_self',0,1493645576,1493645576,100,0,1),(247,243,'cms','启用','','module','cms/advert_type/enable','_self',0,1493645576,1493645576,100,0,1),(248,243,'cms','禁用','','module','cms/advert_type/disable','_self',0,1493645576,1493645576,100,0,1),(249,243,'cms','快速编辑','','module','cms/advert_type/quickedit','_self',0,1493645576,1493645576,100,0,1),(250,235,'cms','滚动图片','fa fa-fw fa-photo','module','cms/slider/index','_self',0,1493645576,1493645576,100,0,1),(251,250,'cms','新增','','module','cms/slider/add','_self',0,1493645576,1493645576,100,0,1),(252,250,'cms','编辑','','module','cms/slider/edit','_self',0,1493645576,1493645576,100,0,1),(253,250,'cms','删除','','module','cms/slider/delete','_self',0,1493645576,1493645576,100,0,1),(254,250,'cms','启用','','module','cms/slider/enable','_self',0,1493645576,1493645576,100,0,1),(255,250,'cms','禁用','','module','cms/slider/disable','_self',0,1493645576,1493645576,100,0,1),(256,250,'cms','快速编辑','','module','cms/slider/quickedit','_self',0,1493645576,1493645576,100,0,1),(257,235,'cms','友情链接','fa fa-fw fa-link','module','cms/link/index','_self',0,1493645576,1493645576,100,0,1),(258,257,'cms','新增','','module','cms/link/add','_self',0,1493645576,1493645576,100,0,1),(259,257,'cms','编辑','','module','cms/link/edit','_self',0,1493645576,1493645576,100,0,1),(260,257,'cms','删除','','module','cms/link/delete','_self',0,1493645576,1493645576,100,0,1),(261,257,'cms','启用','','module','cms/link/enable','_self',0,1493645576,1493645576,100,0,1),(262,257,'cms','禁用','','module','cms/link/disable','_self',0,1493645576,1493645576,100,0,1),(263,257,'cms','快速编辑','','module','cms/link/quickedit','_self',0,1493645576,1493645576,100,0,1),(264,235,'cms','客服管理','fa fa-fw fa-commenting','module','cms/support/index','_self',0,1493645576,1493645576,100,0,1),(265,264,'cms','新增','','module','cms/support/add','_self',0,1493645576,1493645576,100,0,1),(266,264,'cms','编辑','','module','cms/support/edit','_self',0,1493645576,1493645576,100,0,1),(267,264,'cms','删除','','module','cms/support/delete','_self',0,1493645576,1493645576,100,0,1),(268,264,'cms','启用','','module','cms/support/enable','_self',0,1493645576,1493645576,100,0,1),(269,264,'cms','禁用','','module','cms/support/disable','_self',0,1493645576,1493645576,100,0,1),(270,264,'cms','快速编辑','','module','cms/support/quickedit','_self',0,1493645576,1493645576,100,0,1),(271,214,'cms','门户设置','fa fa-fw fa-sliders','module','','_self',0,1493645576,1493645576,100,0,1),(272,271,'cms','栏目分类','fa fa-fw fa-sitemap','module','cms/column/index','_self',1,1493645576,1493645576,100,0,1),(273,272,'cms','新增','','module','cms/column/add','_self',0,1493645576,1493645576,100,0,1),(274,272,'cms','编辑','','module','cms/column/edit','_self',0,1493645576,1493645576,100,0,1),(275,272,'cms','删除','','module','cms/column/delete','_self',0,1493645576,1493645576,100,0,1),(276,272,'cms','启用','','module','cms/column/enable','_self',0,1493645576,1493645576,100,0,1),(277,272,'cms','禁用','','module','cms/column/disable','_self',0,1493645576,1493645576,100,0,1),(278,272,'cms','快速编辑','','module','cms/column/quickedit','_self',0,1493645576,1493645576,100,0,1),(279,271,'cms','内容模型','fa fa-fw fa-th-large','module','cms/model/index','_self',0,1493645576,1493645576,100,0,1),(280,279,'cms','新增','','module','cms/model/add','_self',0,1493645576,1493645576,100,0,1),(281,279,'cms','编辑','','module','cms/model/edit','_self',0,1493645576,1493645576,100,0,1),(282,279,'cms','删除','','module','cms/model/delete','_self',0,1493645576,1493645576,100,0,1),(283,279,'cms','启用','','module','cms/model/enable','_self',0,1493645576,1493645576,100,0,1),(284,279,'cms','禁用','','module','cms/model/disable','_self',0,1493645576,1493645576,100,0,1),(285,279,'cms','快速编辑','','module','cms/model/quickedit','_self',0,1493645576,1493645576,100,0,1),(286,279,'cms','字段管理','','module','cms/field/index','_self',0,1493645576,1493645576,100,0,1),(287,286,'cms','新增','','module','cms/field/add','_self',0,1493645576,1493645576,100,0,1),(288,286,'cms','编辑','','module','cms/field/edit','_self',0,1493645576,1493645576,100,0,1),(289,286,'cms','删除','','module','cms/field/delete','_self',0,1493645576,1493645576,100,0,1),(290,286,'cms','启用','','module','cms/field/enable','_self',0,1493645576,1493645576,100,0,1),(291,286,'cms','禁用','','module','cms/field/disable','_self',0,1493645576,1493645576,100,0,1),(292,286,'cms','快速编辑','','module','cms/field/quickedit','_self',0,1493645576,1493645576,100,0,1),(293,271,'cms','导航管理','fa fa-fw fa-map-signs','module','cms/nav/index','_self',0,1493645576,1493645576,100,0,1),(294,293,'cms','新增','','module','cms/nav/add','_self',0,1493645576,1493645576,100,0,1),(295,293,'cms','编辑','','module','cms/nav/edit','_self',0,1493645576,1493645576,100,0,1),(296,293,'cms','删除','','module','cms/nav/delete','_self',0,1493645576,1493645576,100,0,1),(297,293,'cms','启用','','module','cms/nav/enable','_self',0,1493645576,1493645576,100,0,1),(298,293,'cms','禁用','','module','cms/nav/disable','_self',0,1493645576,1493645576,100,0,1),(299,293,'cms','快速编辑','','module','cms/nav/quickedit','_self',0,1493645576,1493645576,100,0,1),(300,293,'cms','菜单管理','','module','cms/menu/index','_self',0,1493645576,1493645576,100,0,1),(301,300,'cms','新增','','module','cms/menu/add','_self',0,1493645576,1493645576,100,0,1),(302,300,'cms','编辑','','module','cms/menu/edit','_self',0,1493645576,1493645576,100,0,1),(303,300,'cms','删除','','module','cms/menu/delete','_self',0,1493645576,1493645576,100,0,1),(304,300,'cms','启用','','module','cms/menu/enable','_self',0,1493645576,1493645576,100,0,1),(305,300,'cms','禁用','','module','cms/menu/disable','_self',0,1493645576,1493645576,100,0,1),(306,300,'cms','快速编辑','','module','cms/menu/quickedit','_self',0,1493645576,1493645576,100,0,1),(307,0,'business','商家','fa fa-fw fa-users','module','business/index/index','_self',0,1493652517,1493652517,100,0,1),(308,0,'member','会员','fa fa-fw fa-user-circle-o','module','member/index/index','_self',0,1493652584,1493652584,100,0,1);

/*Table structure for table `mc_admin_module` */

DROP TABLE IF EXISTS `mc_admin_module`;

CREATE TABLE `mc_admin_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名称（标识）',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '模块标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text COMMENT '配置信息',
  `access` text COMMENT '授权配置',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '模块唯一标识符',
  `system_module` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统模块',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='模块表';

/*Data for the table `mc_admin_module` */

insert  into `mc_admin_module`(`id`,`name`,`title`,`icon`,`description`,`author`,`author_url`,`config`,`access`,`version`,`identifier`,`system_module`,`create_time`,`update_time`,`sort`,`status`) values (1,'admin','系统','fa fa-fw fa-gear','系统模块，DolphinPHP的核心模块','DolphinPHP','http://www.dolphinphp.com','','','1.0.0','admin.dolphinphp.module',1,1468204902,1468204902,100,1),(2,'user','用户','fa fa-fw fa-user','用户模块，DolphinPHP自带模块','DolphinPHP','http://www.dolphinphp.com','','','1.0.0','user.dolphinphp.module',1,1468204902,1468204902,100,1),(3,'cms','门户','fa fa-fw fa-newspaper-o','门户模块','CaiWeiMing','http://www.dolphinphp.com','{\"summary\":0,\"contact\":\"<div class=\\\"font-s13 push\\\"><strong>\\u6cb3\\u6e90\\u5e02\\u5353\\u9510\\u79d1\\u6280\\u6709\\u9650\\u516c\\u53f8<\\/strong><br \\/>\\n\\u5730\\u5740\\uff1a\\u6cb3\\u6e90\\u5e02\\u6c5f\\u4e1c\\u65b0\\u533a\\u4e1c\\u73af\\u8def\\u6c47\\u901a\\u82d1D3-H232<br \\/>\\n\\u7535\\u8bdd\\uff1a0762-8910006<br \\/>\\n\\u90ae\\u7bb1\\uff1aadmin@zrthink.com<\\/div>\",\"meta_head\":\"\",\"meta_foot\":\"\",\"support_status\":1,\"support_color\":\"rgba(0,158,232,1)\",\"support_wx\":\"\",\"support_extra\":\"\"}','{\"group\":{\"tab_title\":\"\\u680f\\u76ee\\u6388\\u6743\",\"table_name\":\"cms_column\",\"primary_key\":\"id\",\"parent_id\":\"pid\",\"node_name\":\"name\"}}','1.0.0','cms.ming.module',0,1493645576,1493645576,100,1),(4,'business','商家','','商家模块','TanJian','',NULL,NULL,'1.0.0','business.jian.module',0,1493647011,1493647011,100,1),(5,'member','会员','','会员模块','TanJian','',NULL,NULL,'1.0.0','member.jian.module',0,1493647137,1493647137,100,1);

/*Table structure for table `mc_admin_packet` */

DROP TABLE IF EXISTS `mc_admin_packet`;

CREATE TABLE `mc_admin_packet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '数据包名',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '数据包标题',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者url',
  `version` varchar(16) NOT NULL,
  `tables` text NOT NULL COMMENT '数据表名',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据包表';

/*Data for the table `mc_admin_packet` */

/*Table structure for table `mc_admin_plugin` */

DROP TABLE IF EXISTS `mc_admin_plugin`;

CREATE TABLE `mc_admin_plugin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '插件名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '插件描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text NOT NULL COMMENT '配置信息',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '插件唯一标识符',
  `admin` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台管理',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='插件表';

/*Data for the table `mc_admin_plugin` */

insert  into `mc_admin_plugin`(`id`,`name`,`title`,`icon`,`description`,`author`,`author_url`,`config`,`version`,`identifier`,`admin`,`create_time`,`update_time`,`sort`,`status`) values (1,'SystemInfo','系统环境信息','fa fa-fw fa-info-circle','在后台首页显示服务器信息','蔡伟明','http://www.caiweiming.com','{\"display\":\"1\",\"width\":\"6\"}','1.0.0','system_info.ming.plugin',0,1477757503,1477757503,100,1),(2,'DevTeam','开发团队成员信息','fa fa-fw fa-users','开发团队成员信息','蔡伟明','http://www.caiweiming.com','{\"display\":\"1\",\"width\":\"6\"}','1.0.0','dev_team.ming.plugin',0,1477755780,1477755780,100,1);

/*Table structure for table `mc_admin_role` */

DROP TABLE IF EXISTS `mc_admin_role`;

CREATE TABLE `mc_admin_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级角色',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `menu_auth` text NOT NULL COMMENT '菜单权限',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `access` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否可登录后台',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色表';

/*Data for the table `mc_admin_role` */

insert  into `mc_admin_role`(`id`,`pid`,`name`,`description`,`menu_auth`,`sort`,`create_time`,`update_time`,`status`,`access`) values (1,0,'超级管理员','系统默认创建的角色，拥有最高权限','',0,1476270000,1468117612,1,1);

/*Table structure for table `mc_admin_user` */

DROP TABLE IF EXISTS `mc_admin_user`;

CREATE TABLE `mc_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户名',
  `username` varchar(32) NOT NULL,
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(96) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `email_bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定邮箱地址',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `mobile_bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定手机号码',
  `avatar` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '头像',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `role` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `group` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '部门id',
  `signup_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '注册ip',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `last_login_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '登录ip',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

/*Data for the table `mc_admin_user` */

insert  into `mc_admin_user`(`id`,`username`,`nickname`,`password`,`email`,`email_bind`,`mobile`,`mobile_bind`,`avatar`,`money`,`score`,`role`,`group`,`signup_ip`,`create_time`,`update_time`,`last_login_time`,`last_login_ip`,`sort`,`status`) values (1,'admin','超级管理员','$2y$10$Brw6wmuSLIIx3Yabid8/Wu5l8VQ9M/H/CG3C9RqN9dUCwZW3ljGOK','',0,'',0,0,'0.00',0,1,0,0,1476065410,1477794539,1477794539,2130706433,100,1);

/*Table structure for table `mc_business` */

DROP TABLE IF EXISTS `mc_business`;

CREATE TABLE `mc_business` (
  `bid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商家id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商家名称',
  `tel` char(11) NOT NULL DEFAULT '' COMMENT '商家电话',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '商家缩略图',
  `banner` text NOT NULL COMMENT '商家banner图',
  `lng` decimal(10,7) NOT NULL COMMENT '商家位置经度',
  `lat` decimal(10,7) NOT NULL COMMENT '商家位置纬度',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '商家地址-省市县',
  `street` varchar(50) NOT NULL DEFAULT '' COMMENT '商家地址-街道',
  `score` decimal(4,1) NOT NULL COMMENT '商家评价平均分数',
  `times` varchar(255) NOT NULL DEFAULT '' COMMENT '商家上班时间段',
  `is_delete` smallint(1) NOT NULL DEFAULT '0' COMMENT '商家删除标志',
  `create_time` int(10) unsigned NOT NULL COMMENT '商家信息创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '商家信息更新时间',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家表';

/*Data for the table `mc_business` */

/*Table structure for table `mc_business_coupon` */

DROP TABLE IF EXISTS `mc_business_coupon`;

CREATE TABLE `mc_business_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(5,2) NOT NULL COMMENT '优惠金额',
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '1满减2直减',
  `unique` smallint(1) NOT NULL DEFAULT '0' COMMENT '唯一（一个用户只能用一次）',
  `begin_time` int(10) unsigned NOT NULL COMMENT '优惠开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '优惠结束时间',
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '优惠状态1有效2下架',
  `create_time` int(10) unsigned NOT NULL COMMENT '优惠创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家优惠表';

/*Data for the table `mc_business_coupon` */

/*Table structure for table `mc_business_doctor` */

DROP TABLE IF EXISTS `mc_business_doctor`;

CREATE TABLE `mc_business_doctor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL DEFAULT '' COMMENT '医生职位',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '医生简介',
  `major` varchar(200) NOT NULL DEFAULT '' COMMENT '医生主修',
  `score` decimal(2,1) NOT NULL COMMENT '医生评价平均分',
  `is_auth` smallint(1) NOT NULL DEFAULT '1' COMMENT '医生是否经过认证',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家医生表';

/*Data for the table `mc_business_doctor` */

/*Table structure for table `mc_business_score` */

DROP TABLE IF EXISTS `mc_business_score`;

CREATE TABLE `mc_business_score` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `did` int(11) unsigned NOT NULL COMMENT '医生id',
  `service_rate` smallint(1) unsigned NOT NULL COMMENT '商家服务评分',
  `service_evaluate` varchar(255) NOT NULL DEFAULT '' COMMENT '商家服务评价',
  `level_rate` smallint(1) unsigned NOT NULL COMMENT '商家医生水平评分',
  `level_evaluate` varchar(255) NOT NULL DEFAULT '' COMMENT '商家医生水平评价',
  `create_time` int(10) unsigned NOT NULL COMMENT '评价时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家评分表';

/*Data for the table `mc_business_score` */

/*Table structure for table `mc_business_service` */

DROP TABLE IF EXISTS `mc_business_service`;

CREATE TABLE `mc_business_service` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned NOT NULL COMMENT '商家id',
  `type` smallint(1) NOT NULL COMMENT '服务类型',
  `doctor` varchar(50) NOT NULL COMMENT '支持的医生id',
  `coupon` varchar(50) NOT NULL COMMENT '支持的优惠id',
  `time` varchar(50) NOT NULL COMMENT '支持的时间段id',
  `pet` varchar(50) NOT NULL COMMENT '支持的宠物类型',
  `breed` varchar(50) NOT NULL COMMENT '支持的宠物品种',
  `age` varchar(50) NOT NULL COMMENT '支持的宠物年龄',
  `price` decimal(10,2) NOT NULL COMMENT '默认服务价格',
  `is_coin` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否支持金币付款',
  `creat_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家服务表';

/*Data for the table `mc_business_service` */

/*Table structure for table `mc_business_time` */

DROP TABLE IF EXISTS `mc_business_time`;

CREATE TABLE `mc_business_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id，无则为商户添加',
  `bid` int(11) unsigned NOT NULL COMMENT '商家id',
  `tid` smallint(2) unsigned NOT NULL COMMENT '时间段id',
  `time` char(8) NOT NULL COMMENT '时间段日期（格式：Ymd）',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家预约时间段表';

/*Data for the table `mc_business_time` */

/*Table structure for table `mc_cms_advert` */

DROP TABLE IF EXISTS `mc_cms_advert`;

CREATE TABLE `mc_cms_advert` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `tagname` varchar(30) NOT NULL DEFAULT '' COMMENT '广告位标识',
  `ad_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '广告类型',
  `timeset` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '时间限制:0-永不过期,1-在设内时间内有效',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '广告位名称',
  `content` text NOT NULL COMMENT '广告内容',
  `expcontent` text NOT NULL COMMENT '过期显示内容',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表';

/*Data for the table `mc_cms_advert` */

/*Table structure for table `mc_cms_advert_type` */

DROP TABLE IF EXISTS `mc_cms_advert_type`;

CREATE TABLE `mc_cms_advert_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告分类表';

/*Data for the table `mc_cms_advert_type` */

/*Table structure for table `mc_cms_column` */

DROP TABLE IF EXISTS `mc_cms_column`;

CREATE TABLE `mc_cms_column` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `model` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文档模型id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `target` varchar(16) NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `content` text NOT NULL COMMENT '内容',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '字体图标',
  `index_template` varchar(32) NOT NULL DEFAULT '' COMMENT '封面模板',
  `list_template` varchar(32) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `detail_template` varchar(32) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `post_auth` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '投稿权限',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `hide` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `rank_auth` int(11) NOT NULL DEFAULT '0' COMMENT '浏览权限，-1待审核，0为开放浏览，大于0则为对应的用户角色id',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '栏目属性：0-最终列表栏目，1-外部链接，2-频道封面',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

/*Data for the table `mc_cms_column` */

/*Table structure for table `mc_cms_document` */

DROP TABLE IF EXISTS `mc_cms_document`;

CREATE TABLE `mc_cms_document` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `model` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文档模型ID',
  `title` varchar(256) NOT NULL DEFAULT '' COMMENT '标题',
  `shorttitle` varchar(32) NOT NULL DEFAULT '' COMMENT '简略标题',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `flag` set('j','p','b','s','a','f','c','h') DEFAULT NULL COMMENT '自定义属性',
  `view` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `comment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `good` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `bad` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '踩数',
  `mark` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `trash` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '回收站',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档基础表';

/*Data for the table `mc_cms_document` */

/*Table structure for table `mc_cms_field` */

DROP TABLE IF EXISTS `mc_cms_field`;

CREATE TABLE `mc_cms_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '字段名称',
  `name` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '字段标题',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '字段类型',
  `define` varchar(128) NOT NULL DEFAULT '' COMMENT '字段定义',
  `value` text COMMENT '默认值',
  `options` text COMMENT '额外选项',
  `tips` varchar(256) NOT NULL DEFAULT '' COMMENT '提示说明',
  `fixed` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否为固定字段',
  `show` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `model` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属文档模型id',
  `ajax_url` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框ajax地址',
  `next_items` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框的下级下拉框名，多个以逗号隔开',
  `param` varchar(32) NOT NULL DEFAULT '' COMMENT '联动下拉框请求参数名',
  `format` varchar(32) NOT NULL DEFAULT '' COMMENT '格式，用于格式文本',
  `table` varchar(32) NOT NULL DEFAULT '' COMMENT '表名，只用于快速联动类型',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '联动级别，只用于快速联动类型',
  `key` varchar(32) NOT NULL DEFAULT '' COMMENT '键字段，只用于快速联动类型',
  `option` varchar(32) NOT NULL DEFAULT '' COMMENT '值字段，只用于快速联动类型',
  `pid` varchar(32) NOT NULL DEFAULT '' COMMENT '父级id字段，只用于快速联动类型',
  `ak` varchar(32) NOT NULL DEFAULT '' COMMENT '百度地图appkey',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='文档字段表';

/*Data for the table `mc_cms_field` */

insert  into `mc_cms_field`(`id`,`name`,`title`,`type`,`define`,`value`,`options`,`tips`,`fixed`,`show`,`model`,`ajax_url`,`next_items`,`param`,`format`,`table`,`level`,`key`,`option`,`pid`,`ak`,`create_time`,`update_time`,`sort`,`status`) values (1,'id','ID','text','int(11) UNSIGNED NOT NULL','0','','ID',0,0,0,'','','','','',0,'','','','',1480562978,1480562978,100,1),(2,'cid','栏目','select','int(11) UNSIGNED NOT NULL','0','','请选择所属栏目',0,0,0,'','','','','',0,'','','','',1480562978,1480562978,100,1),(3,'uid','用户ID','text','int(11) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563110,1480563110,100,1),(4,'model','模型ID','text','int(11) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563110,1480563110,100,1),(5,'title','标题','text','varchar(128) NOT NULL','','','文档标题',0,1,0,'','','','','',0,'','','','',1480575844,1480576134,1,1),(6,'shorttitle','简略标题','text','varchar(32) NOT NULL','','','简略标题',0,1,0,'','','','','',0,'','','','',1480575844,1480576134,1,1),(7,'flag','自定义属性','checkbox','set(\'j\',\'p\',\'b\',\'s\',\'a\',\'f\',\'h\',\'c\') NULL DEFAULT NULL','','j:跳转\r\np:图片\r\nb:加粗\r\ns:滚动\r\na:特荐\r\nf:幻灯\r\nh:头条\r\nc:推荐','自定义属性',0,1,0,'','','','','',0,'','','','',1480671258,1480671258,100,1),(8,'view','阅读量','text','int(11) UNSIGNED NOT NULL','0','','',0,1,0,'','','','','',0,'','','','',1480563149,1480563149,100,1),(9,'comment','评论数','text','int(11) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563189,1480563189,100,1),(10,'good','点赞数','text','int(11) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563279,1480563279,100,1),(11,'bad','踩数','text','int(11) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563330,1480563330,100,1),(12,'mark','收藏数量','text','int(11) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563372,1480563372,100,1),(13,'create_time','创建时间','datetime','int(11) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563406,1480563406,100,1),(14,'update_time','更新时间','datetime','int(11) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563432,1480563432,100,1),(15,'sort','排序','text','int(11) NOT NULL','100','','',0,1,0,'','','','','',0,'','','','',1480563510,1480563510,100,1),(16,'status','状态','radio','tinyint(2) UNSIGNED NOT NULL','1','0:禁用\r\n1:启用','',0,1,0,'','','','','',0,'','','','',1480563576,1480563576,100,1),(17,'trash','回收站','text','tinyint(2) UNSIGNED NOT NULL','0','','',0,0,0,'','','','','',0,'','','','',1480563576,1480563576,100,1);

/*Table structure for table `mc_cms_link` */

DROP TABLE IF EXISTS `mc_cms_link`;

CREATE TABLE `mc_cms_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型：1-文字链接，2-图片链接',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '链接标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `logo` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '链接LOGO',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '联系方式',
  `sort` int(11) NOT NULL DEFAULT '100',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='有钱链接表';

/*Data for the table `mc_cms_link` */

/*Table structure for table `mc_cms_menu` */

DROP TABLE IF EXISTS `mc_cms_menu`;

CREATE TABLE `mc_cms_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '导航id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `column` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `page` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单页id',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '类型：0-栏目链接，1-单页链接，2-自定义链接',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `css` varchar(64) NOT NULL DEFAULT '' COMMENT 'css类',
  `rel` varchar(64) NOT NULL DEFAULT '' COMMENT '链接关系网',
  `target` varchar(16) NOT NULL DEFAULT '' COMMENT '打开方式',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='菜单表';

/*Data for the table `mc_cms_menu` */

insert  into `mc_cms_menu`(`id`,`nid`,`pid`,`column`,`page`,`type`,`title`,`url`,`css`,`rel`,`target`,`create_time`,`update_time`,`sort`,`status`) values (1,1,0,0,0,2,'首页','cms/index/index','','','_self',1492345605,1492345605,100,1),(2,2,0,0,0,2,'关于我们','http://www.dolphinphp.com','','','_self',1492346763,1492346763,100,1),(3,3,0,0,0,2,'开发文档','http://www.kancloud.cn/ming5112/dolphinphp','','','_self',1492346812,1492346812,100,1),(4,3,0,0,0,2,'开发者社区','http://bbs.dolphinphp.com/','','','_self',1492346832,1492346832,100,1),(5,1,0,0,0,2,'二级菜单','http://www.dolphinphp.com','','','_self',1492347372,1492347510,100,1),(6,1,5,0,0,2,'子菜单','http://www.dolphinphp.com','','','_self',1492347388,1492347520,100,1);

/*Table structure for table `mc_cms_model` */

DROP TABLE IF EXISTS `mc_cms_model`;

CREATE TABLE `mc_cms_model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '模型名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '模型标题',
  `table` varchar(64) NOT NULL DEFAULT '' COMMENT '附加表名称',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '模型类别：0-系统模型，1-普通模型，2-独立模型',
  `icon` varchar(64) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统模型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内容模型表';

/*Data for the table `mc_cms_model` */

/*Table structure for table `mc_cms_nav` */

DROP TABLE IF EXISTS `mc_cms_nav`;

CREATE TABLE `mc_cms_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(32) NOT NULL DEFAULT '' COMMENT '导航标识',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='导航表';

/*Data for the table `mc_cms_nav` */

insert  into `mc_cms_nav`(`id`,`tag`,`title`,`create_time`,`update_time`,`status`) values (1,'main_nav','顶部导航',1492345083,1492345083,1),(2,'about_nav','底部关于',1492346685,1492346685,1),(3,'support_nav','服务与支持',1492346715,1492346715,1);

/*Table structure for table `mc_cms_page` */

DROP TABLE IF EXISTS `mc_cms_page`;

CREATE TABLE `mc_cms_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '单页标题',
  `content` mediumtext NOT NULL COMMENT '单页内容',
  `keywords` varchar(32) NOT NULL DEFAULT '' COMMENT '关键词',
  `description` varchar(250) NOT NULL DEFAULT '' COMMENT '页面描述',
  `template` varchar(32) NOT NULL DEFAULT '' COMMENT '模板文件',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单页封面',
  `view` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页表';

/*Data for the table `mc_cms_page` */

/*Table structure for table `mc_cms_slider` */

DROP TABLE IF EXISTS `mc_cms_slider`;

CREATE TABLE `mc_cms_slider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '封面id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='滚动图片表';

/*Data for the table `mc_cms_slider` */

/*Table structure for table `mc_cms_support` */

DROP TABLE IF EXISTS `mc_cms_support`;

CREATE TABLE `mc_cms_support` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '客服名称',
  `qq` varchar(16) NOT NULL DEFAULT '' COMMENT 'QQ',
  `msn` varchar(100) NOT NULL DEFAULT '' COMMENT 'msn',
  `taobao` varchar(100) NOT NULL DEFAULT '' COMMENT 'taobao',
  `alibaba` varchar(100) NOT NULL DEFAULT '' COMMENT 'alibaba',
  `skype` varchar(100) NOT NULL DEFAULT '' COMMENT 'skype',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客服表';

/*Data for the table `mc_cms_support` */

/*Table structure for table `mc_member` */

DROP TABLE IF EXISTS `mc_member`;

CREATE TABLE `mc_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(96) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `email_bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定邮箱地址',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `mobile_bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定手机号码',
  `avatar` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '头像',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `signup_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '注册ip',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `last_login_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '登录ip',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';

/*Data for the table `mc_member` */

/*Table structure for table `mc_member_address` */

DROP TABLE IF EXISTS `mc_member_address`;

CREATE TABLE `mc_member_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL COMMENT '会员id',
  `name` varchar(50) NOT NULL COMMENT '收货人姓名',
  `mobile` char(11) NOT NULL COMMENT '手机号',
  `sex` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别1男2女',
  `is_main` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否主地址',
  `address` varchar(255) NOT NULL COMMENT '地址（省市县）',
  `street` varchar(255) NOT NULL COMMENT '街道',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员地址表';

/*Data for the table `mc_member_address` */

/*Table structure for table `mc_member_coin` */

DROP TABLE IF EXISTS `mc_member_coin`;

CREATE TABLE `mc_member_coin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL COMMENT '会员id',
  `type` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型1系统奖励2任务奖励',
  `task_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `amount` int(5) unsigned NOT NULL COMMENT '奖励总额',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员金币领取表';

/*Data for the table `mc_member_coin` */

/*Table structure for table `mc_member_collect` */

DROP TABLE IF EXISTS `mc_member_collect`;

CREATE TABLE `mc_member_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned NOT NULL COMMENT '商户id',
  `mid` int(11) unsigned NOT NULL COMMENT '会员id',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员收藏表（收藏商户）';

/*Data for the table `mc_member_collect` */

/*Table structure for table `mc_member_comment` */

DROP TABLE IF EXISTS `mc_member_comment`;

CREATE TABLE `mc_member_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_mid` int(11) unsigned NOT NULL COMMENT '发送评论会员id',
  `to_mid` int(11) unsigned NOT NULL COMMENT '被评论的会员id',
  `content` varchar(255) NOT NULL COMMENT '评论内容',
  `parent_id` int(11) unsigned NOT NULL COMMENT '父类id',
  `parent_type` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '父类类型1帖子2评论',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员评论表';

/*Data for the table `mc_member_comment` */

/*Table structure for table `mc_member_favorite` */

DROP TABLE IF EXISTS `mc_member_favorite`;

CREATE TABLE `mc_member_favorite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) unsigned NOT NULL COMMENT '帖子id',
  `mid` int(11) unsigned NOT NULL COMMENT '会员id',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员圈帖子喜欢表';

/*Data for the table `mc_member_favorite` */

/*Table structure for table `mc_member_follow` */

DROP TABLE IF EXISTS `mc_member_follow`;

CREATE TABLE `mc_member_follow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL COMMENT '会员id',
  `follow_mid` int(11) unsigned NOT NULL COMMENT '关注的会员id',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员关注关系表';

/*Data for the table `mc_member_follow` */

/*Table structure for table `mc_member_forum` */

DROP TABLE IF EXISTS `mc_member_forum`;

CREATE TABLE `mc_member_forum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL COMMENT '会员id',
  `content` text NOT NULL COMMENT '内容',
  `images` text NOT NULL COMMENT '图片',
  `lng` decimal(10,7) NOT NULL COMMENT '经度',
  `lat` decimal(10,7) NOT NULL COMMENT '纬度',
  `is_stick` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '置顶',
  `stick_time` int(10) unsigned NOT NULL COMMENT '置顶时间',
  `is_delete` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态',
  `delete_time` int(10) unsigned NOT NULL COMMENT '删除时间',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员圈帖子表';

/*Data for the table `mc_member_forum` */

/*Table structure for table `mc_member_message` */

DROP TABLE IF EXISTS `mc_member_message`;

CREATE TABLE `mc_member_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_mid` int(11) unsigned NOT NULL COMMENT '发送消息会员id，0为系统消息',
  `to_mid` int(11) NOT NULL COMMENT '接收消息会员id',
  `message` text NOT NULL COMMENT '发送的消息',
  `image` varchar(255) NOT NULL COMMENT '发送的图片',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员消息表';

/*Data for the table `mc_member_message` */

/*Table structure for table `mc_member_route` */

DROP TABLE IF EXISTS `mc_member_route`;

CREATE TABLE `mc_member_route` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父类路线id',
  `lng` decimal(10,7) NOT NULL COMMENT '经度',
  `lat` decimal(10,7) NOT NULL COMMENT '纬度',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员遛狗路线表';

/*Data for the table `mc_member_route` */

/*Table structure for table `mc_member_search_history` */

DROP TABLE IF EXISTS `mc_member_search_history`;

CREATE TABLE `mc_member_search_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL COMMENT '会员id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '搜索标题',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员搜索历史表';

/*Data for the table `mc_member_search_history` */

/*Table structure for table `mc_order` */

DROP TABLE IF EXISTS `mc_order`;

CREATE TABLE `mc_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL COMMENT '下单用户id',
  `uname` varchar(50) NOT NULL COMMENT '用户名称',
  `bid` int(11) unsigned NOT NULL COMMENT '商家id',
  `bname` varchar(50) NOT NULL COMMENT '商家名称',
  `did` int(11) unsigned NOT NULL COMMENT '医生id',
  `dname` varchar(50) NOT NULL COMMENT '医生名称',
  `order_no` varchar(50) NOT NULL COMMENT '订单号',
  `type` varchar(50) NOT NULL COMMENT '服务类型',
  `pet` varchar(50) NOT NULL COMMENT '宠物类型',
  `breed` varchar(50) NOT NULL COMMENT '宠物品种',
  `age` varchar(50) NOT NULL COMMENT '宠物年龄',
  `time` varchar(50) NOT NULL COMMENT '预约时间',
  `address_id` int(11) unsigned NOT NULL COMMENT '地址id',
  `pay_type` smallint(1) NOT NULL COMMENT '支付类型',
  `coupon_id` int(11) unsigned NOT NULL COMMENT '优惠id',
  `coin` int(5) NOT NULL COMMENT '使用金币',
  `amount` decimal(10,2) NOT NULL COMMENT '订单总价',
  `price` decimal(10,2) NOT NULL COMMENT '订单最终应付价',
  `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '订单状态0下单成功1订单完成2订单退款',
  `is_delete` smallint(1) NOT NULL DEFAULT '0' COMMENT '删除状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='预约服务订单表';

/*Data for the table `mc_order` */

/*Table structure for table `mc_service_config` */

DROP TABLE IF EXISTS `mc_service_config`;

CREATE TABLE `mc_service_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商家id，有则为商家申请添加',
  `name` varchar(50) NOT NULL COMMENT '配置名称',
  `icon` varchar(255) NOT NULL COMMENT '配置图标',
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '配置状态，商家申请添加默认为0',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='服务配置表';

/*Data for the table `mc_service_config` */

/*Table structure for table `mc_task` */

DROP TABLE IF EXISTS `mc_task`;

CREATE TABLE `mc_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '任务标题',
  `type` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '任务类型1唯一任务2每日任务',
  `intro` varchar(255) NOT NULL COMMENT '任务简介',
  `amount` int(5) unsigned NOT NULL COMMENT '任务奖励积分',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='任务表';

/*Data for the table `mc_task` */

/*Table structure for table `mc_task_do` */

DROP TABLE IF EXISTS `mc_task_do`;

CREATE TABLE `mc_task_do` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL COMMENT '会员id',
  `task_id` int(11) unsigned NOT NULL COMMENT '任务id',
  `status` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '任务完成进度',
  `amount` int(5) unsigned NOT NULL COMMENT '奖励积分数',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='任务完成表';

/*Data for the table `mc_task_do` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
