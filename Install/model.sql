/*
SQLyog Ultimate v12.4.1 (64 bit)
MySQL - 10.1.31-MariaDB : Database - sandboxs
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
  `js_type` varchar(100) DEFAULT 'text/javascript',
  `css_base` varchar(100) DEFAULT 'none',
  `layout` enum('left','right','top','bottom') DEFAULT 'right',
  PRIMARY KEY (`id`),
  KEY `i_code_user` (`user`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

/*Data for the table `code` */


/*Table structure for table `temp_files` */

DROP TABLE IF EXISTS `temp_files`;

CREATE TABLE `temp_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=487 DEFAULT CHARSET=utf8;

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
  `is_top` tinyint(1) DEFAULT '0',
  `js_type` varchar(100) DEFAULT 'text/javascript',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ui_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `templates` */

insert  into `templates`(`id`,`icon`,`name`,`title`,`css`,`html`,`js`,`head`,`css_links`,`js_links`,`template_order`,`is_top`,`js_type`) values 
(1,'m4.png','metro4','Metro 4',NULL,NULL,NULL,NULL,'https://cdn.metroui.org.ua/v4/css/metro-all.min.css','https://code.jquery.com/jquery-3.3.1.min.js\r\nhttps://cdn.metroui.org.ua/v4/js/metro.min.js',1,1,'text/javascript'),
(2,'m4-vue.png','metro4_vue','Metro 4 + VueJS',NULL,'&lt;div id=\"app\"&gt;\r\n{{ message }}\r\n&lt;/div&gt;','var app = new Vue({\r\n    el: \'#app\',\r\n    data: {\r\n        message: \'Hello Metro 4 + Vue!\'\r\n    },\r\n    mounted: function () {\r\n        Metro.init();\r\n    }\r\n});','<meta name=\"metro4:init\" content=\"false\">','https://cdn.metroui.org.ua/v4/css/metro-all.min.css','https://cdn.jsdelivr.net/npm/vue\r\nhttps://code.jquery.com/jquery-3.3.1.min.js\r\nhttps://cdn.metroui.org.ua/v4/js/metro.min.js',2,1,'text/javascript'),
(3,'HTML5.png','html','HTML5',NULL,NULL,NULL,NULL,NULL,NULL,5,0,'text/javascript'),
(4,'vue.png','vue','VueJS',NULL,'&lt;div id=\"app\"&gt;\r\n{{ message }}\r\n&lt;/div&gt;','var app = new Vue({\r\n  el: \'#app\',\r\n  data: {\r\n    message: \'Hello Vue!\'\r\n  }\r\n})',NULL,NULL,'https://cdn.jsdelivr.net/npm/vue',3,0,'text/javascript'),
(5,'jquery.png','jquery','jQuery',NULL,NULL,NULL,NULL,NULL,'https://code.jquery.com/jquery-3.3.1.min.js',4,0,'text/javascript'),
(6,'bootstrap.png','bootstrap','Bootstrap',NULL,NULL,NULL,NULL,'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css','https://code.jquery.com/jquery-3.3.1.slim.min.js\r\nhttps://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js',6,0,'text/javascript'),
(7,'react.png','react','React',NULL,'&lt;div id=\"root\"&gt;&lt;/div&gt;','ReactDOM.render(\r\n    <h1>Hello, world!</h1>,\r\n    document.getElementById(\'root\')\r\n);',NULL,NULL,'https://unpkg.com/react@16/umd/react.development.js\r\nhttps://unpkg.com/react-dom@16/umd/react-dom.development.js\r\nhttps://unpkg.com/babel-standalone@6.15.0/babel.min.js',7,0,'text/babel');

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
  `layout` enum('left','right','top','bottom') DEFAULT 'right',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;

/*Data for the table `user` */


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
