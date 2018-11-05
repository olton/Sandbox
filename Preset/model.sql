/*
SQLyog Ultimate v12.4.1 (64 bit)
MySQL - 10.1.31-MariaDB : Database - metro_sandbox
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `code` */

DROP TABLE IF EXISTS `code`;

CREATE TABLE `code` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `user` bigint(20) unsigned NOT NULL,
  `html` text,
  `css` text,
  `js` text,
  `created` datetime DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `template` bigint(20) unsigned DEFAULT NULL,
  `hash` varchar(20) DEFAULT NULL,
  `html_head` text,
  `html_processor` enum('none','halm','markdown','slim','pug') DEFAULT 'none',
  `html_classes` varchar(255) DEFAULT NULL,
  `css_processor` enum('none','less','scss','sass','stylus') DEFAULT 'none',
  `desc` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `code_type` enum('code','template') DEFAULT 'code',
  `js_processor` enum('none','Babel','TypeScript','CoffeeScript','LiveScript') DEFAULT 'none',
  `css_external` text,
  `js_external` text,
  `body_classes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_code_user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `code` */

/*Table structure for table `temp_files` */

DROP TABLE IF EXISTS `temp_files`;

CREATE TABLE `temp_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `temp_files` */

/*Table structure for table `templates` */

DROP TABLE IF EXISTS `templates`;

CREATE TABLE `templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `css` text,
  `html` text,
  `js` text,
  `head` text,
  `css_links` text,
  `js_links` text,
  `template_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ui_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `templates` */

insert  into `templates`(`id`,`icon`,`name`,`title`,`css`,`html`,`js`,`head`,`css_links`,`js_links`,`template_order`) values 
(1,'m4.svg','metro4','Metro 4',NULL,NULL,NULL,NULL,'https://cdn.metroui.org.ua/v4/css/metro-all.min.css','https://code.jquery.com/jquery-3.3.1.min.js\r\nhttps://cdn.metroui.org.ua/v4/js/metro.min.js',1),
(2,'m4-vue.png','metro4_vue','Metro 4 + VueJS',NULL,'&lt;div id=\"app\"&gt;\r\n{{ message }}\r\n&lt;/div&gt;','var app = new Vue({\r\n    el: \'#app\',\r\n    data: {\r\n        message: \'Hello Metro 4 + Vue!\'\r\n    },\r\n    mounted: function () {\r\n        Metro.init();\r\n    }\r\n});','<meta name=\"metro4:init\" content=\"false\">','https://cdn.metroui.org.ua/v4/css/metro-all.min.css','https://cdn.jsdelivr.net/npm/vue\r\nhttps://code.jquery.com/jquery-3.3.1.min.js\r\nhttps://cdn.metroui.org.ua/v4/js/metro.min.js',2),
(3,'html.png','html','HTML5',NULL,NULL,NULL,NULL,NULL,NULL,5),
(4,'vue.svg','vue','VueJS',NULL,'&lt;div id=\"app\"&gt;\r\n{{ message }}\r\n&lt;/div&gt;','var app = new Vue({\r\n  el: \'#app\',\r\n  data: {\r\n    message: \'Hello Vue!\'\r\n  }\r\n})',NULL,NULL,'https://cdn.jsdelivr.net/npm/vue',4),
(5,'jquery.png','jquery','jQuery',NULL,NULL,NULL,NULL,NULL,'https://code.jquery.com/jquery-3.3.1.min.js',3);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_logged` datetime DEFAULT NULL,
  `oauth` enum('none','github','facebook','twitter') NOT NULL DEFAULT 'none',
  `access_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
