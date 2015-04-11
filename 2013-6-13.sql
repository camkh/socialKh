/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.24-log : Database - facekhdb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`facekhdb` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `facekhdb`;

/*Table structure for table `fac_label` */

DROP TABLE IF EXISTS `fac_label`;

CREATE TABLE `fac_label` (
  `label_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label_name` varchar(200) NOT NULL DEFAULT '',
  `label_slug` varchar(200) NOT NULL DEFAULT '',
  `label_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`label_id`),
  UNIQUE KEY `label_slug` (`label_slug`),
  KEY `label_name` (`label_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `fac_label` */

insert  into `fac_label`(`label_id`,`label_name`,`label_slug`,`label_group`) values (1,'Khmer Movies','khmer-movies',0),(2,'Khmer Movies Series','khmer-movies-series',0),(3,'China','china',0),(4,'China Serries','china-serries',0),(5,'Korea','korea',0),(6,'Korea Serries','korea-serries',0);

/*Table structure for table `fac_label_relationships` */

DROP TABLE IF EXISTS `fac_label_relationships`;

CREATE TABLE `fac_label_relationships` (
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `label_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rel_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`label_id`),
  KEY `label_id` (`label_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `fac_label_relationships` */

insert  into `fac_label_relationships`(`post_id`,`label_id`,`rel_order`) values (1,1,0);

/*Table structure for table `fac_label_taxonomy` */

DROP TABLE IF EXISTS `fac_label_taxonomy`;

CREATE TABLE `fac_label_taxonomy` (
  `label_tax_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `label_tax_taxonomy` varchar(32) NOT NULL DEFAULT '',
  `label_tax_description` longtext NOT NULL,
  `label_tax_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `label_tax_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`label_tax_id`),
  UNIQUE KEY `label_tax_id` (`label_id`,`label_tax_taxonomy`),
  KEY `taxonomy` (`label_tax_taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `fac_label_taxonomy` */

insert  into `fac_label_taxonomy`(`label_tax_id`,`label_id`,`label_tax_taxonomy`,`label_tax_description`,`label_tax_parent`,`label_tax_count`) values (1,2,'category','',1,0),(2,3,'category','',0,0),(3,4,'category','',3,0),(4,5,'category','',0,0),(5,6,'category','',5,0);

/*Table structure for table `post` */

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(20) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `post` */

insert  into `post`(`ID`,`post_author`,`post_date`,`post_date_gmt`,`post_content`,`post_title`,`post_excerpt`,`post_status`,`comment_status`,`ping_status`,`post_password`,`post_name`,`to_ping`,`pinged`,`post_modified`,`post_modified_gmt`,`post_content_filtered`,`post_parent`,`slug`,`menu_order`,`post_type`,`post_mime_type`,`comment_count`) values (1,0,'2013-09-06 02:41:31','0000-00-00 00:00:00','sdffds','sd','','publish','open','open','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,'sdffds',0,'post','',0);

/*Table structure for table `pparts` */

DROP TABLE IF EXISTS `pparts`;

CREATE TABLE `pparts` (
  `par_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `par_part` varchar(250) NOT NULL,
  `par_video_id` varchar(250) NOT NULL,
  `par_type` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`par_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `pparts` */

insert  into `pparts`(`par_id`,`post_id`,`par_part`,`par_video_id`,`par_type`) values (1,1,'','s','s');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
