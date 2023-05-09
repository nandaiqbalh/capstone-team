/*
SQLyog Ultimate v12.4.3 (64 bit)
MySQL - 10.4.20-MariaDB : Database - ports_hermina
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ports_hermina` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ports_hermina`;

/*Table structure for table `app_login` */

DROP TABLE IF EXISTS `app_login`;

CREATE TABLE `app_login` (
  `id` varchar(15) NOT NULL,
  `user_id` varchar(15) DEFAULT NULL,
  `access_token` text DEFAULT NULL,
  `status` enum('login','logout') DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `type` enum('api','web') DEFAULT 'web',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `app_login_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_login` */

insert  into `app_login`(`id`,`user_id`,`access_token`,`status`,`ip_address`,`date`,`type`) values 
('16600100267978','16096390033565',NULL,'logout','127.0.0.1','2022-08-09 08:53:46','api'),
('16613061399688','1660027205558',NULL,'login','127.0.0.1','2022-08-24 08:55:39','web');

/*Table structure for table `app_login_attempt` */

DROP TABLE IF EXISTS `app_login_attempt`;

CREATE TABLE `app_login_attempt` (
  `id` varchar(15) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_login_attempt` */

insert  into `app_login_attempt`(`id`,`nik`,`password`,`ip_address`,`created_date`) values 
('16600087276703','3326180101880001','Mlebukuyioo','127.0.0.1','2022-08-09 08:32:07'),
('16605282512337','3326180101880002','Mlebukuy1','127.0.0.1','2022-08-15 08:50:51');

/*Table structure for table `app_menu` */

DROP TABLE IF EXISTS `app_menu`;

CREATE TABLE `app_menu` (
  `menu_id` varchar(2) NOT NULL,
  `parent_menu_id` varchar(2) DEFAULT NULL,
  `menu_name` varchar(50) DEFAULT NULL,
  `menu_description` varchar(100) DEFAULT NULL,
  `menu_url` varchar(100) DEFAULT NULL,
  `menu_sort` int(11) unsigned DEFAULT NULL,
  `menu_group` enum('utama','system','lainnya') DEFAULT 'utama',
  `menu_icon` varchar(50) DEFAULT NULL COMMENT 'mdi icon class',
  `menu_active` enum('1','0') DEFAULT '1',
  `menu_display` enum('1','0') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_menu` */

insert  into `app_menu`(`menu_id`,`parent_menu_id`,`menu_name`,`menu_description`,`menu_url`,`menu_sort`,`menu_group`,`menu_icon`,`menu_active`,`menu_display`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
('01',NULL,'Pengaturan','Menu Pengaturan','#settings',10,'system','bx bx-category-alt','1','1','16096390033565','2021-01-05 13:12:06',NULL,NULL),
('02','01','Role','Menu Role','admin/settings/role',12,'system','fas fa-user-lock','1','1','16096390033565','2021-01-05 13:14:23','16096390033565','2021-08-10 16:43:40'),
('03','01','Menu','Menu Navigation','admin/settings/menu',13,'system','fas fa-bars','1','1','16096390033565','2021-01-05 13:15:28','16096390033565','2021-09-08 10:43:41'),
('04','01','Akun User','Menu Akun User','admin/settings/accounts',14,'system','fas fa-users','1','1','16096390033565','2021-01-06 09:05:15','16096390033565','2021-03-15 20:56:20'),
('05','01','Email SMPT','Menu Email SMPT','admin/settings/smtp',16,'system','fas fa-envelope','0','0','16096390033565','2021-01-06 09:32:17','16096390033565','2021-11-17 11:08:14'),
('06',NULL,'Beranda','Menu Beranda','admin/dashboard',20,'utama','bx bx-home-circle','1','1','16096390033565','2021-01-07 06:09:08','16096390033565','2022-03-25 17:30:06'),
('07','01','Profil Akun','Profil Akun','admin/settings/account',17,'system','fas fa-user','1','1','16096390033565','2021-03-15 11:56:13',NULL,NULL),
('08','01','Rest Api User','Rest Api User','admin/settings/rest-api',18,'system','fab fa-connectdevelop','0','0','16096390033565','2021-11-17 11:27:10',NULL,NULL),
('10',NULL,'Keluar','Keluar','logout',99,'system','bx bx-power-off','1','0','16096390033565','2022-03-18 10:13:03','16096390033565','2022-03-18 10:21:44'),
('11',NULL,'Master Data','Master Data','#master-data',21,'utama','bx bx-data','1','1','16096390033565','2022-08-09 09:25:03','16096390033565','2022-08-09 10:34:51'),
('12','11','Lokasi','Lokasi','admin/validator/master/lokasi',22,'utama',NULL,'1','1','16096390033565','2022-08-09 09:26:50',NULL,NULL),
('13','11','Area','Area','admin/validator/master/area',23,'utama',NULL,'1','1','16096390033565','2022-08-09 09:27:33','16096390033565','2022-08-09 09:27:48'),
('14','11','Sub Area','Sub Area','admin/validator/master/sub-area',24,'utama',NULL,'1','1','16096390033565','2022-08-09 09:28:25',NULL,NULL),
('15','11','Item Penilaian','Item Penilaian','admin/validator/master/item-penilaian',26,'utama',NULL,'1','1','16096390033565','2022-08-09 09:39:17',NULL,NULL),
('16','11','Komponen Penilaian','Komponen Penilaian','admin/validator/master/komponen-penilaian',27,'utama',NULL,'1','1','16096390033565','2022-08-09 09:40:41','16096390033565','2022-08-09 13:10:58'),
('17',NULL,'Rumah Sakit','Rumah Sakit','admin/validator/rumah-sakit',29,'utama','bx bx-plus-medical','1','1','16096390033565','2022-08-09 09:42:10','16096390033565','2022-08-10 13:24:30'),
('18',NULL,'Laporan','Laporan','#laporan',30,'utama','bx bx-file','1','1','16096390033565','2022-08-09 09:42:59','16096390033565','2022-08-11 16:06:57'),
('19','11','Regional','Regional','admin/validator/master/region',28,'utama',NULL,'1','1','16096390033565','2022-08-10 13:24:03',NULL,NULL),
('20','18','Ronde','Ronde','admin/validator/laporan/ronde',31,'utama',NULL,'1','1','16096390033565','2022-08-11 16:03:34',NULL,NULL),
('21','18','Rekapitulasi Nilai','Rekapitulasi Nilai','admin/validator/laporan/rekapitulasi-nilai',32,'utama',NULL,'1','1','16096390033565','2022-08-11 16:04:11','16096390033565','2022-08-15 10:13:14'),
('22','18','Rekapitulasi Terlambat Submit','Rekapitulasi Terlambat Submit','admin/validator/laporan/terlambat-submit',33,'utama',NULL,'1','1','16096390033565','2022-08-11 16:05:19','16096390033565','2022-08-22 08:03:11'),
('23','18','Rekapitulasi Parameter','Rekapitulasi Parameter','admin/validator/laporan/parameter-rumah-sakit',34,'utama',NULL,'1','1','16096390033565','2022-08-11 16:05:56','16096390033565','2022-08-22 08:03:30'),
('24','18','Rekapitulasi Hasil Pekerjaan','Rekapitulasi Hasil Pekerjaan','admin/validator/laporan/hasil-pekerjaan-rumah-sakit',35,'utama',NULL,'1','1','16096390033565','2022-08-11 16:06:42','16096390033565','2022-08-22 08:03:51'),
('25',NULL,'Register','Register Checker','#register',42,'utama','bx bx-list-plus','1','1','16096390033565','2022-08-10 09:10:54',NULL,NULL),
('26','25','Akun Rumah Sakit','Akun Rumah Sakit Checker','admin/checker/register/akun-rs',43,'utama',NULL,'1','1','16096390033565','2022-08-10 09:12:02',NULL,NULL),
('27','25','Item Penilaian','Item Penilaian Checker','admin/checker/register/item-penilaian',44,'utama',NULL,'1','1','16096390033565','2022-08-10 10:06:09','16096390033565','2022-08-10 10:06:29'),
('28',NULL,'Unduh QR-Code','Unduh QR-Code','admin/checker/qr-code',40,'utama','bx bx-qr-scan','1','1','16096390033565','2022-08-11 16:46:43','16096390033565','2022-08-18 16:50:48'),
('29',NULL,'Ronde','Ronde Checker','admin/checker/ronde',45,'utama','bx bx-bar-chart-alt','1','1','16096390033565','2022-08-12 08:10:07','16096390033565','2022-08-12 08:42:05'),
('30',NULL,'Item Penilaian','Verifikator 1','admin/verifikator/item-penilaian',51,'utama','bx bx-sitemap','1','1','16096390033565','2022-08-16 10:51:19',NULL,NULL),
('31',NULL,'Ronde','Ronde Verifikator','admin/verifikator/ronde',52,'utama','bx bx-bar-chart-alt','1','1','16096390033565','2022-08-16 11:21:28',NULL,NULL),
('32','18','Rekapitulasi Rata - Rata Nilai','Rekapitulasi Rata - Rata Nilai','admin/validator/laporan/rata-rata-nilai',33,'utama',NULL,'1','1','16096390033565','2022-08-22 07:58:39','16096390033565','2022-08-22 08:02:55');

/*Table structure for table `app_reset_password` */

DROP TABLE IF EXISTS `app_reset_password`;

CREATE TABLE `app_reset_password` (
  `id` varchar(15) NOT NULL,
  `user_id` varchar(15) DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `status` enum('0','1') DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `max_age` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `app_reset_password_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_reset_password` */

/*Table structure for table `app_role` */

DROP TABLE IF EXISTS `app_role`;

CREATE TABLE `app_role` (
  `role_id` varchar(2) NOT NULL,
  `role_name` varchar(100) DEFAULT NULL,
  `role_description` varchar(100) DEFAULT NULL,
  `role_permission` varchar(4) DEFAULT '1000',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_role` */

insert  into `app_role`(`role_id`,`role_name`,`role_description`,`role_permission`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
('01','Super Admin','Pengelola aplikasi, akun dan hak akses.','1111','16096390033565','2021-01-04 08:59:24',NULL,NULL),
('02','Checker','Checker','1111','16096390033565','2021-01-04 09:01:13','16096390033565','2022-03-18 11:25:01'),
('03','Verifikator 1','Verifikator 1','1111',NULL,NULL,'16096390033565','2022-08-09 09:11:20'),
('04','Verifikator 2','Verifikator 2','1111',NULL,NULL,'16096390033565','2022-08-09 09:11:40'),
('05','Validator','Validator','1111','16096390033565','2022-08-09 09:16:20',NULL,NULL),
('06','Holding','Holding','1111','16096390033565','2022-08-09 09:16:34',NULL,NULL);

/*Table structure for table `app_role_menu` */

DROP TABLE IF EXISTS `app_role_menu`;

CREATE TABLE `app_role_menu` (
  `role_id` varchar(2) NOT NULL,
  `menu_id` varchar(2) NOT NULL,
  PRIMARY KEY (`menu_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `app_role_menu_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `app_menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `app_role_menu_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_role_menu` */

insert  into `app_role_menu`(`role_id`,`menu_id`) values 
('01','01'),
('02','01'),
('03','01'),
('04','01'),
('05','01'),
('06','01'),
('01','02'),
('01','03'),
('01','04'),
('01','05'),
('01','06'),
('02','06'),
('03','06'),
('04','06'),
('05','06'),
('06','06'),
('01','07'),
('02','07'),
('03','07'),
('04','07'),
('05','07'),
('06','07'),
('01','08'),
('01','10'),
('02','10'),
('03','10'),
('04','10'),
('05','10'),
('06','10'),
('01','11'),
('05','11'),
('01','12'),
('05','12'),
('01','13'),
('05','13'),
('01','14'),
('05','14'),
('01','15'),
('05','15'),
('01','16'),
('05','16'),
('01','17'),
('05','17'),
('01','18'),
('05','18'),
('06','18'),
('01','19'),
('05','19'),
('01','20'),
('05','20'),
('06','20'),
('01','21'),
('05','21'),
('06','21'),
('01','22'),
('05','22'),
('06','22'),
('01','23'),
('05','23'),
('06','23'),
('01','24'),
('05','24'),
('06','24'),
('01','25'),
('02','25'),
('01','26'),
('02','26'),
('01','27'),
('02','27'),
('01','28'),
('02','28'),
('01','29'),
('02','29'),
('01','30'),
('01','31'),
('03','31'),
('04','31'),
('01','32'),
('05','32'),
('06','32');

/*Table structure for table `app_role_user` */

DROP TABLE IF EXISTS `app_role_user`;

CREATE TABLE `app_role_user` (
  `role_id` varchar(2) NOT NULL,
  `user_id` varchar(15) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `app_role_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `app_role_user_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_role_user` */

insert  into `app_role_user`(`role_id`,`user_id`) values 
('01','16096390033565'),
('02','16601890063153'),
('05','1660027205558');

/*Table structure for table `app_smtp_email` */

DROP TABLE IF EXISTS `app_smtp_email`;

CREATE TABLE `app_smtp_email` (
  `email_id` varchar(2) NOT NULL,
  `email_name` varchar(100) DEFAULT NULL,
  `email_address` varchar(50) DEFAULT NULL,
  `smtp_host` varchar(50) DEFAULT NULL,
  `smtp_port` varchar(5) DEFAULT NULL,
  `smtp_username` varchar(50) DEFAULT NULL,
  `smtp_password` varchar(50) DEFAULT NULL,
  `use_smtp` enum('1','0') DEFAULT '1',
  `use_authorization` enum('1','0') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_smtp_email` */

insert  into `app_smtp_email`(`email_id`,`email_name`,`email_address`,`smtp_host`,`smtp_port`,`smtp_username`,`smtp_password`,`use_smtp`,`use_authorization`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
('01','Noreply Hermina ABRT-RL','noreply@ports-abarobotics.com','mail.ports-abarobotics.com','465','noreply@ports-abarobotics.com','2Cgp&q3^Tc04','1','1',NULL,NULL,'16096390033565','2021-09-08 13:40:17');

/*Table structure for table `app_supports` */

DROP TABLE IF EXISTS `app_supports`;

CREATE TABLE `app_supports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `type` enum('base','specific') DEFAULT NULL,
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `app_supports` */

insert  into `app_supports`(`id`,`key`,`value`,`type`,`created_by`,`created_date`) values 
(1,'target_rata_rata_nilai','89',NULL,NULL,NULL),
(2,'app_footer','Copyright © 2022 <strong>Hermina Group</strong> , Dikembangkan oleh <a href=\"https://abarobotics.com\" target=\"_blank\">Abarobotics</a>','base',NULL,NULL),
(3,'app_version','Versi 2.1','base',NULL,NULL);

/*Table structure for table `app_user` */

DROP TABLE IF EXISTS `app_user`;

CREATE TABLE `app_user` (
  `user_id` varchar(15) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_active` enum('1','0') NOT NULL DEFAULT '1',
  `user_img_path` varchar(100) DEFAULT NULL,
  `user_img_name` varchar(200) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `branch_id` (`branch_id`),
  CONSTRAINT `app_user_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `master_branch` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_user` */

insert  into `app_user`(`user_id`,`user_name`,`user_email`,`user_password`,`user_active`,`user_img_path`,`user_img_name`,`nik`,`no_telp`,`branch_id`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
('16096390033565','Yudi Arif Kurniawan','yudis@abarobotics.com','$2y$10$SeWwSHuwIZ4u0XDVeoBcZeKQf/G0i07BXTYwprUs23oPdpC/QF2S6','1','/img/user/','yudi-arif-kurniawan-613871e29c9e1.png','332618001','082325320259',NULL,'2006000001','2020-12-13 15:03:58','16096390033565','2022-08-09 13:31:58'),
('1660027205558','Yudi Validator','validator@abarobotics.com','$2y$10$iB7d/xYI9cqH64mGDg7zDuDKqzEpwVkhyUjcqBonbCmZPT6OZydqy','1','/img/user/','default.png','332618002',NULL,NULL,'16096390033565','2022-08-09 13:40:05','16096390033565','2022-08-09 13:42:20'),
('16601890063153','Yudi Checker','yudi@abarobotics.com','$2y$10$xayEi/fEqguYcZxE4pDcdup5GhOaMTEp2dIRY4vrjCea5LPgU9j..','1','/img/user/','default.png','332618003','082325320259',NULL,'16096390033565','2022-08-11 10:36:46','1660027205558','2022-08-22 12:00:53');

/*Table structure for table `app_visitor` */

DROP TABLE IF EXISTS `app_visitor`;

CREATE TABLE `app_visitor` (
  `visitor_id` varchar(15) NOT NULL,
  `visitor_ip` varchar(100) DEFAULT NULL,
  `visitor_date` datetime DEFAULT NULL,
  `visitor_hits` varchar(10) DEFAULT NULL,
  `visitor_online` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`visitor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_visitor` */

/*Table structure for table `branch_assessment` */

DROP TABLE IF EXISTS `branch_assessment`;

CREATE TABLE `branch_assessment` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `round_id` int(11) DEFAULT NULL,
  `checker_name` varchar(255) DEFAULT NULL COMMENT 'manager jagum',
  `checker_approved_date` datetime DEFAULT NULL,
  `checker_approved_by_system` datetime DEFAULT NULL,
  `checker_ontime` tinyint(1) DEFAULT 1,
  `verifikator_1_name` varchar(255) DEFAULT NULL COMMENT 'wadir cabang',
  `verifikator_1_approved_date` datetime DEFAULT NULL,
  `verifikator_1_approved_by_system` datetime DEFAULT NULL,
  `verifikator_1_ontime` tinyint(1) DEFAULT 1,
  `verifikator_2_name` varchar(255) DEFAULT NULL COMMENT 'direktur cabang',
  `verifikator_2_approved_date` datetime DEFAULT NULL,
  `verifikator_2_approved_by_system` datetime DEFAULT NULL,
  `validator_name` varchar(255) DEFAULT NULL COMMENT 'kepala departemen penunjang umum',
  `validator_view_date` datetime DEFAULT NULL,
  `direg_name` varchar(255) DEFAULT NULL,
  `dirop_name` varchar(255) DEFAULT NULL,
  `status` enum('selesai','belum berjalan','proses penilaian','persetujuan verifikator 1','persetujuan verifikator 2') DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`),
  KEY `round_id` (`round_id`),
  CONSTRAINT `branch_assessment_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `master_branch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `branch_assessment_ibfk_2` FOREIGN KEY (`round_id`) REFERENCES `master_round` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `branch_assessment` */

insert  into `branch_assessment`(`id`,`branch_id`,`round_id`,`checker_name`,`checker_approved_date`,`checker_approved_by_system`,`checker_ontime`,`verifikator_1_name`,`verifikator_1_approved_date`,`verifikator_1_approved_by_system`,`verifikator_1_ontime`,`verifikator_2_name`,`verifikator_2_approved_date`,`verifikator_2_approved_by_system`,`validator_name`,`validator_view_date`,`direg_name`,`dirop_name`,`status`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(3,5,4,NULL,NULL,NULL,1,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'proses penilaian','1','1660027205558','2022-08-23 11:26:37',NULL,NULL);

/*Table structure for table `branch_assessment_detail` */

DROP TABLE IF EXISTS `branch_assessment_detail`;

CREATE TABLE `branch_assessment_detail` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `branch_assessment_id` bigint(8) DEFAULT NULL,
  `branch_items_id` bigint(8) DEFAULT NULL,
  `assessment_component_id` bigint(8) DEFAULT NULL,
  `score` enum('A','B','C') DEFAULT NULL,
  `parameter` varchar(100) DEFAULT NULL,
  `img_name` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `revision_status` enum('tidak ada revisi','ada revisi','revisi selesai') DEFAULT 'tidak ada revisi',
  `revision_by` varchar(100) DEFAULT NULL,
  `revision_date` datetime DEFAULT NULL,
  `revision_description` text DEFAULT NULL,
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_assessment_id` (`branch_assessment_id`),
  CONSTRAINT `branch_assessment_detail_ibfk_1` FOREIGN KEY (`branch_assessment_id`) REFERENCES `branch_assessment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `branch_assessment_detail` */

/*Table structure for table `branch_items` */

DROP TABLE IF EXISTS `branch_items`;

CREATE TABLE `branch_items` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `items_id` bigint(8) DEFAULT NULL,
  `sub_area_id` int(11) DEFAULT NULL,
  `zona` enum('JKN','Eksekutif','Tanpa Zona') DEFAULT NULL,
  `unique_id` int(11) DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`),
  KEY `sub_area_id` (`sub_area_id`),
  KEY `items_id` (`items_id`),
  CONSTRAINT `branch_items_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `master_branch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `branch_items_ibfk_3` FOREIGN KEY (`sub_area_id`) REFERENCES `master_sub_area` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `branch_items_ibfk_4` FOREIGN KEY (`items_id`) REFERENCES `master_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `branch_items` */

/*Table structure for table `master_area` */

DROP TABLE IF EXISTS `master_area`;

CREATE TABLE `master_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `round_id` int(11) DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lokasi_id` (`location_id`),
  KEY `round_id` (`round_id`),
  CONSTRAINT `master_area_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `master_location` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `master_area_ibfk_2` FOREIGN KEY (`round_id`) REFERENCES `master_round` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `master_area` */

insert  into `master_area`(`id`,`location_id`,`name`,`description`,`round_id`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(1,1,'Dapur & Pantry',NULL,4,'1',NULL,NULL,NULL,NULL),
(2,1,'Laundry',NULL,4,'1','1660027205558','2022-08-24 10:31:26',NULL,NULL),
(3,1,'Central Gas',NULL,4,'1','1660027205558','2022-08-24 10:31:26',NULL,NULL),
(4,1,'Musholla',NULL,4,'1','1660027205558','2022-08-24 10:31:26',NULL,NULL),
(5,1,'Toilet Umum',NULL,4,'1','1660027205558','2022-08-24 10:31:26',NULL,NULL),
(6,2,'Mobil Operasional',NULL,4,'1','1660027205558','2022-08-24 10:31:26',NULL,NULL),
(7,3,'Mobil Ambulance',NULL,4,'1','1660027205558','2022-08-24 10:31:26',NULL,NULL),
(8,4,'Motor Kurir',NULL,4,'1','1660027205558','2022-08-24 10:31:26',NULL,NULL);

/*Table structure for table `master_assessment_component` */

DROP TABLE IF EXISTS `master_assessment_component`;

CREATE TABLE `master_assessment_component` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `items_id` bigint(8) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `parameter_true` enum('Aman','Bersih','Rapih','Tampak Baru','Ramah Lingkungan') DEFAULT NULL,
  `parameter_false` enum('Tidak Aman','Tidak Bersih','Tidak Rapih','Tidak Tampak Baru','Tidak Ramah Lingkungan') DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `master_assessment_component_ibfk_2` (`parameter_true`),
  KEY `items_id` (`items_id`),
  CONSTRAINT `master_assessment_component_ibfk_1` FOREIGN KEY (`items_id`) REFERENCES `master_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

/*Data for the table `master_assessment_component` */

insert  into `master_assessment_component`(`id`,`items_id`,`name`,`parameter_true`,`parameter_false`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(1,1,'Suhu dapur 22-30°C ','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(2,2,'Bersih, tidak ada noda','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(3,2,'Engsel pintu berfungsi baik, sehingga tidak ada gores pada lantai','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(4,2,'Handle pintu kokoh, tidak goyang','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(5,2,'Handle bersih, tidak berminyak, dan berjamur','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(6,2,'Terdapat label (sign) nama ruangan sesuai standar','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(7,3,'Lantai bersih tidak ada noda','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(8,3,'Lantai tidak pecah','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(9,3,'Lantai tidak licin','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(10,3,'Nat tidak hitam','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(11,4,'Dinding kramik','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(12,4,'Kramik tidak ada yang pecah','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(13,4,'Dinding bersih, tidak bernoda','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(14,4,'Nat bersih, tidak hitam','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(15,5,'Plafond bersih, tidak ada noda','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(16,5,'Plafond terpasang rapih, rapat, tidak ada yg bolong','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(17,6,'Tidak ada lampu yang mati','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(18,6,'Penerangan 100-200 lux','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(19,6,'Lampu tidak terlepas dari plafond','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(20,7,'Bagian dalam dilapisi plastik hitam','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(21,7,'Pijakan pembuka berfungsi baik','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(22,7,'Tidak ada lalat','Ramah Lingkungan','Tidak Ramah Lingkungan','1',NULL,NULL,NULL,NULL),
(23,7,'Tempat sampah tidak terisi lebih dari 3/4 volume','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(24,7,'Terdapat sticker: Infeksius / Non Infeksius','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(25,8,'Chiller berfungsi normal','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(26,8,'Bagian dalam dan luar bersih','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(27,9,'Alur bahan makanan tidak bertemu atau tidak jadi satu dengan alur makanan jadi.','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(28,9,'Tersedia ruang penerimaan bahan makanan tersendiri','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(29,9,'Alur pengolahan makanan terbagi menjadi penerimaan makanan, pengolahan dan penyajian','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(30,9,'Antara ruang pengolahan dan penyajian, terdapat ruang pemisah','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(31,9,'Pencucian alat makan kotor dari pasien, terletak diluar dapur','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(32,10,'Sink tidak berkarat','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(33,10,'Kran sink berfungsi normal','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(34,10,'Kran sink tidak goyang','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(35,10,'Tidak bocor pada kran dan pipa','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(36,10,'Air tidak tersumbat','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(37,11,'Tersedia dan digunakannya APD antara lain: Topi, Masker, Apron, Sepatu karet, Sarung Tangan','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(38,11,'Tersedia tempat APD kotor dan APD bersih','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(39,12,'Terpasang sesuai standar ketinggian: 125 cm','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(40,12,' Jenis CO2','Ramah Lingkungan','Tidak Ramah Lingkungan','1',NULL,NULL,NULL,NULL),
(41,12,'Terdapat rambu titik APAR','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(42,12,'Terdapat checklist pemeliharaan yang diisi rutin','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(43,13,'Makanan dikelompokkan sesuai dengan peruntukannya','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(44,13,'Bahan makanan berprotein tinggi disimpan di freezer','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(45,13,'Bahan makanan diberikan label tanggal masuk dan tanggal kadaluarsa','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(46,13,'Makanan yang berbau tajam disimpan dalam wadah tertutup','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(47,14,'Bahan makanan tersusun rapi berdasarkan jenisnya','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(48,14,'Jarak rak penyimpanan dengan lantai: 15cm','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(49,14,'Jarak rak penyimpanan dengan dinding: 5cm','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(50,14,'Jarak rak penyimpanan dengan langit-langit: 60cm','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(51,14,'Kelembaban ruang penyimpanan 80-90%','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(52,14,'Bahan makanan diberi label tanggal masuk dan kadaluarsanya','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(53,15,'Exhaus fan berfungsi baik','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(54,15,'Tidak mengeluarkan suara diluar kewajaran','Ramah Lingkungan','Tidak Ramah Lingkungan','1',NULL,NULL,NULL,NULL),
(55,16,'Bersih, tidak berminyak berlebih','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(56,16,'Kompor berfungsi normal','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(57,17,'Bersih, tidak berminyak diluar kewajaran','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(58,17,'Tidak berkarat','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(59,18,'Tidak ada kebocoran pipa gas, tidak ada bau gas','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(60,19,'Bersih, tidak ada sampah bahan makanan, tidak berdebu, tidak lengket','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(61,19,'Bahan: stainless','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(62,19,'Baik secara fisik, tidak goyang, miring','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(63,20,'Bahan: stainless','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(64,20,'Bersih, Tertata rapi. Dikelompokkan sesuai jenis alat.','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(65,20,'Pintu berfungsi baik','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(66,21,'Bahan: stainless','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(67,21,'Bersih, Tertata rapi. Dikelompokkan sesuai jenis alat.','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(68,21,'Pintu berfungsi baik','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(69,22,'Bahan: stainless','Ramah Lingkungan','Tidak Ramah Lingkungan','1',NULL,NULL,NULL,NULL),
(70,23,'Berfungsi baik sesuai spesifikasi','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(71,23,'Suhu pada saat disimpan makanan 70°C','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(72,23,'Keadaan bersih, tidak ada bekas makanan, tidak berminyak','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(73,23,'Roda lancar, tidak menimbulkan bunyi','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(74,23,'Secara fisik baik, tidak penyok','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(75,23,'Pintu berfungsi baik','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(76,24,'Keadaan bersih, tidak ada bekas makanan, tidak berminyak,dll','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(77,24,'Tidak Berkarat','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(78,24,'Roda lancar, tidak menimbulkan bunyi','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(79,24,'Secara fisik baik, tidak penyok','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(80,24,'Pintu berfungsi baik','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(81,25,'Keadaan bersih, tidak ada bekas makanan, tidak berminyak,dll','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(82,25,'Tidak Berkarat','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(83,25,'Roda lancar, tidak menimbulkan bunyi','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(84,25,'Secara fisik baik, tidak penyok','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(85,25,'Pintu berfungsi baik','Tampak Baru','Tidak Tampak Baru','1',NULL,NULL,NULL,NULL),
(86,26,'Bersih','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(87,26,'Tidak bergoyang','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(88,26,'Tersusun rapih','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(89,27,'Bersih','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(90,27,'Tidak bergoyang','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(91,27,'Tersusun rapih','Rapih','Tidak Rapih','1',NULL,NULL,NULL,NULL),
(92,28,'Wastafel terpasang kokoh, tidak goyang','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(93,28,'Bersih, tidak bernoda dan berjamur','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(94,28,'Kran wastafel berfungsi normal','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(95,28,'Kran wastafel tidak goyang','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(96,28,'Tidak bocor pada kran dan pipa','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(97,28,'Saluran air masuk dan keluar tidak tersumbat','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL),
(98,29,'Bersih, tidak bernoda dan berjamur','Bersih','Tidak Bersih','1',NULL,NULL,NULL,NULL),
(99,29,'Kaca tidak pecah','Aman','Tidak Aman','1',NULL,NULL,NULL,NULL);

/*Table structure for table `master_branch` */

DROP TABLE IF EXISTS `master_branch`;

CREATE TABLE `master_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `no_telp` varchar(200) DEFAULT NULL,
  `region_name` varchar(200) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `class` enum('A','B','C','D') DEFAULT NULL,
  `id_branch` varchar(100) DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1' COMMENT 'status data aktif atau arsip',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_name`),
  KEY `province_id` (`province_id`),
  KEY `city_id` (`city_id`),
  CONSTRAINT `master_branch_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `master_province` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `master_branch_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `master_city` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `master_branch` */

insert  into `master_branch`(`id`,`name`,`address`,`no_telp`,`region_name`,`province_id`,`city_id`,`class`,`id_branch`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(5,'Hermina Test','fsfwefwe','435345345','Regional Wilayah I',27,797,'A','54235435','1','1660027205558','2022-08-23 11:26:37',NULL,NULL);

/*Table structure for table `master_city` */

DROP TABLE IF EXISTS `master_city`;

CREATE TABLE `master_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_code` int(11) DEFAULT NULL,
  `code` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `provinsi_id` (`province_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1037 DEFAULT CHARSET=latin1;

/*Data for the table `master_city` */

insert  into `master_city`(`id`,`province_code`,`code`,`name`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(10,11,1101,'Kab. Aceh Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(11,11,1102,'Kab. Aceh Tenggara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(12,11,1103,'Kab. Aceh Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(13,11,1104,'Kab. Aceh Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(14,11,1105,'Kab. Aceh Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(15,11,1106,'Kab. Aceh Besar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(16,11,1107,'Kab. Pidie',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(17,11,1108,'Kab. Aceh Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(18,11,1109,'Kab. Simeulue',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(19,11,1110,'Kab. Aceh Singkil',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(20,11,1111,'Kab. Bireuen',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(21,11,1112,'Kab. Aceh Barat Daya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(22,11,1113,'Kab. Gayo Lues',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(23,11,1114,'Kab. Aceh Jaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(24,11,1115,'Kab. Nagan Raya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(25,11,1116,'Kab. Aceh Tamiang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(26,11,1117,'Kab. Bener Meriah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(27,11,1118,'Kab. Pidie Jaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(28,11,1171,'Kota Banda Aceh',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(29,11,1172,'Kota Sabang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(30,11,1173,'Kota Lhokseumawe',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(31,11,1174,'Kota Langsa',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(32,11,1175,'Kota Subulussalam',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(33,12,1201,'Kab. Tapanuli Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(34,12,1202,'Kab. Tapanuli Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(35,12,1203,'Kab. Tapanuli Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(36,12,1204,'Kab. Nias',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(37,12,1205,'Kab. Langkat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(38,12,1206,'Kab. Karo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(39,12,1207,'Kab. Deli Serdang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(40,12,1208,'Kab. Simalungun',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(41,12,1209,'Kab. Asahan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(42,12,1210,'Kab. Labuhanbatu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(43,12,1211,'Kab. Dairi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(44,12,1212,'Kab. Toba Samosir',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(45,12,1213,'Kab. Mandailing Natal',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(46,12,1214,'Kab. Nias Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(47,12,1215,'Kab. Pakpak Bharat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(48,12,1216,'Kab. Humbang Hasundutan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(49,12,1217,'Kab. Samosir',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(50,12,1218,'Kab. Serdang Bedagai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(51,12,1219,'Kab. Batu Bara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(52,12,1220,'Kab. Padang Lawas Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(53,12,1221,'Kab. Padang Lawas',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(54,12,1222,'Kab. Labuhanbatu Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(55,12,1223,'Kab. Labuhanbatu Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(56,12,1224,'Kab. Nias Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(57,12,1225,'Kab. Nias Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(58,12,1271,'Kota Medan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(59,12,1272,'Kota Pematangsiantar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(60,12,1273,'Kota Sibolga',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(61,12,1274,'Kota Tanjung Balai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(62,12,1275,'Kota Binjai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(63,12,1276,'Kota Tebing Tinggi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(64,12,1277,'Kota Padang Sidempuan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(65,12,1278,'Kota Gunung Sitoli',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(66,13,1301,'Kab. Pesisir Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(67,13,1302,'Kab. Solok',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(68,13,1303,'Kab. Sijunjung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(69,13,1304,'Kab. Tanah Datar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(70,13,1305,'Kab. Padang Pariaman',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(71,13,1306,'Kab. Agam',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(72,13,1307,'Kab. Lima Puluh Kota',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(73,13,1308,'Kab. Pasaman',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(74,13,1309,'Kab. Kepulauan Mentawai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(75,13,1310,'Kab. Dharmasraya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(76,13,1311,'Kab. Solok Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(77,13,1312,'Kab. Pasaman Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(78,13,1371,'Kota Padang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(79,13,1372,'Kota Solok',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(80,13,1373,'Kota Sawahlunto',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(81,13,1374,'Kota Padangpanjang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(82,13,1375,'Kota Bukittinggi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(83,13,1376,'Kota Payakumbuh',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(84,13,1377,'Kota Pariaman',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(85,14,1401,'Kab. Kampar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(86,14,1402,'Kab. Indragiri Hulu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(87,14,1403,'Kab. Bengkalis',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(88,14,1404,'Kab. Indragiri Hilir',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(89,14,1405,'Kab. Pelalawan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(90,14,1406,'Kab. Rokan Hulu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(91,14,1407,'Kab. Rokan Hilir',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(92,14,1408,'Kab. Siak',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(93,14,1409,'Kab. Kuantan Singingi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(94,14,1410,'Kab. Kepulauan Meranti',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(95,14,1471,'Kota Pekanbaru',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(96,14,1472,'Kota Dumai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(97,15,1501,'Kab. Kerinci',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(98,15,1502,'Kab. Merangin',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(99,15,1503,'Kab. Sarolangun',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(100,15,1504,'Kab. Batang Hari',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(101,15,1505,'Kab. Muaro Jambi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(102,15,1506,'Kab. Tanjung Jabung Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(103,15,1507,'Kab. Tanjung Jabung Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(104,15,1508,'Kab. Bungo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(105,15,1509,'Kab. Tebo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(106,15,1571,'Kota Jambi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(107,15,1572,'Kota Sungai Penuh',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(108,16,1601,'Kab. Ogan Komering Ulu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(109,16,1602,'Kab. Ogan Komering Ilir',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(110,16,1603,'Kab. Muara Enim',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(111,16,1604,'Kab. Lahat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(112,16,1605,'Kab. Musi Rawas',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(113,16,1606,'Kab. Musi Banyuasin',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(114,16,1607,'Kab. Banyuasin',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(115,16,1608,'Kab. Ogan Komering Ulu Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(116,16,1609,'Kab. Ogan Komering Ulu Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(117,16,1610,'Kab. Ogan Ilir',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(118,16,1611,'Kab. Empat Lawang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(119,16,1612,'Kab. Penukal Arab Lematang Ilir',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(120,16,1613,'Kab. Musi Rawas Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(121,16,1671,'Kota Palembang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(122,16,1672,'Kota Pagar Alam',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(123,16,1673,'Kota Lubuklinggau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(124,16,1674,'Kota Prabumulih',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(125,17,1701,'Kab. Bengkulu Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(126,17,1702,'Kab. Rejang Lebong',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(127,17,1703,'Kab. Bengkulu Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(128,17,1704,'Kab. Kaur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(129,17,1705,'Kab. Seluma',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(130,17,1706,'Kab. Mukomuko',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(131,17,1707,'Kab. Lebong',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(132,17,1708,'Kab. Kepahiang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(133,17,1709,'Kab. Bengkulu Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(134,17,1771,'Kota Bengkulu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(135,18,1801,'Kab. Lampung Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(136,18,1802,'Kab. Lampung Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(137,18,1803,'Kab. Lampung Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(138,18,1804,'Kab. Lampung Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(139,18,1805,'Kab. Tulang Bawang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(140,18,1806,'Kab. Tanggamus',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(141,18,1807,'Kab. Lampung Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(142,18,1808,'Kab. Way Kanan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(143,18,1809,'Kab. Pesawaran',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(144,18,1810,'Kab. Pringsewu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(145,18,1811,'Kab. Mesuji',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(146,18,1812,'Kab. Tulang Bawang Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(147,18,1813,'Kab. Pesisir Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(148,18,1871,'Kota Bandar Lampung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(149,18,1872,'Kota Metro',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(150,19,1901,'Kab. Bangka',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(151,19,1902,'Kab. Belitung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(152,19,1903,'Kab. Bangka Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(153,19,1904,'Kab. Bangka Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(154,19,1905,'Kab. Bangka Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(155,19,1906,'Kab. Belitung Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(156,19,1971,'Kota Pangkal Pinang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(157,21,2101,'Kab. Bintan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(158,21,2102,'Kab. Karimun',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(159,21,2103,'Kab. Natuna',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(160,21,2104,'Kab. Lingga',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(161,21,2105,'Kab. Kepulauan Anambas',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(162,21,2171,'Kota Batam',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(163,21,2172,'Kota Tanjung Pinang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(164,31,3101,'Administrasi Kepulauan Seribu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(165,31,3171,'Kota Administrasi Jakarta Pusat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(166,31,3172,'Kota Administrasi Jakarta Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(167,31,3173,'Kota Administrasi Jakarta Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(168,31,3174,'Kota Administrasi Jakarta Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(169,31,3175,'Kota Administrasi Jakarta Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(170,32,3201,'Kab. Bogor',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(171,32,3202,'Kab. Sukabumi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(172,32,3203,'Kab. Cianjur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(173,32,3204,'Kab. Bandung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(174,32,3205,'Kab. Garut',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(175,32,3206,'Kab. Tasikmalaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(176,32,3207,'Kab. Ciamis',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(177,32,3208,'Kab. Kuningan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(178,32,3209,'Kab. Cirebon',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(179,32,3210,'Kab. Majalengka',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(180,32,3211,'Kab. Sumedang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(181,32,3212,'Kab. Indramayu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(182,32,3213,'Kab. Subang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(183,32,3214,'Kab. Purwakarta',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(184,32,3215,'Kab. Karawang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(185,32,3216,'Kab. Bekasi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(186,32,3217,'Kab. Bandung Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(187,32,3271,'Kota Bogor',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(188,32,3272,'Kota Sukabumi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(189,32,3273,'Kota Bandung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(190,32,3274,'Kota Cirebon',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(191,32,3275,'Kota Bekasi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(192,32,3276,'Kota Depok',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(193,32,3277,'Kota Cimahi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(194,32,3278,'Kota Tasikmalaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(195,32,3279,'Kota Banjar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(196,33,3301,'Kab. Cilacap',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(197,33,3302,'Kab. Banyumas',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(198,33,3303,'Kab. Purbalingga',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(199,33,3304,'Kab. Banjarnegara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(200,33,3305,'Kab. Kebumen',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(201,33,3306,'Kab. Purworejo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(202,33,3307,'Kab. Wonosobo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(203,33,3308,'Kab. Magelang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(204,33,3309,'Kab. Boyolali',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(205,33,3310,'Kab. Klaten',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(206,33,3311,'Kab. Sukoharjo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(207,33,3312,'Kab. Wonogiri',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(208,33,3313,'Kab. Karanganyar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(209,33,3314,'Kab. Sragen',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(210,33,3315,'Kab. Grobogan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(211,33,3316,'Kab. Blora',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(212,33,3317,'Kab. Rembang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(213,33,3318,'Kab. Pati',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(214,33,3319,'Kab. Kudus',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(215,33,3320,'Kab. Jepara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(216,33,3321,'Kab. Demak',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(217,33,3322,'Kab. Semarang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(218,33,3323,'Kab. Temanggung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(219,33,3324,'Kab. Kendal',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(220,33,3325,'Kab. Batang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(221,33,3326,'Kab. Pekalongan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(222,33,3327,'Kab. Pemalang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(223,33,3328,'Kab. Tegal',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(224,33,3329,'Kab. Brebes',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(225,33,3371,'Kota Magelang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(226,33,3372,'Kota Surakarta',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(227,33,3373,'Kota Salatiga',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(228,33,3374,'Kota Semarang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(229,33,3375,'Kota Pekalongan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(230,33,3376,'Kota Tegal',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(231,34,3401,'Kab. Kulon Progo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(232,34,3402,'Kab. Bantul',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(233,34,3403,'Kab. Gunung Kidul',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(234,34,3404,'Kab. Sleman',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(235,34,3471,'',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(236,35,3501,'Kab. Pacitan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(237,35,3502,'Kab. Ponorogo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(238,35,3503,'Kab. Trenggalek',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(239,35,3504,'Kab. Tulungagung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(240,35,3505,'Kab. Blitar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(241,35,3506,'Kab. Kediri',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(242,35,3507,'Kab. Malang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(243,35,3508,'Kab. Lumajang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(244,35,3509,'Kab. Jember',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(245,35,3510,'Kab. Banyuwangi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(246,35,3511,'Kab. Bondowoso',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(247,35,3512,'Kab. Situbondo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(248,35,3513,'Kab. Probolinggo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(249,35,3514,'Kab. Pasuruan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(250,35,3515,'Kab. Sidoarjo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(251,35,3516,'Kab. Mojokerto',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(252,35,3517,'Kab. Jombang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(253,35,3518,'Kab. Nganjuk',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(254,35,3519,'Kab. Madiun',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(255,35,3520,'Kab. Magetan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(256,35,3521,'Kab. Ngawi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(257,35,3522,'Kab. Bojonegoro',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(258,35,3523,'Kab. Tuban',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(259,35,3524,'Kab. Lamongan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(773,35,3525,'Kab. Gresik',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(774,35,3526,'Kab. Bangkalan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(775,35,3527,'Kab. Sampang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(776,35,3528,'Kab. Pamekasan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(777,35,3529,'Kab. Sumenep',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(778,35,3571,'Kota Kediri',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(779,35,3572,'Kota Blitar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(780,35,3573,'Kota Malang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(781,35,3574,'Kota Probolinggo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(782,35,3575,'Kota Pasuruan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(783,35,3576,'Kota Mojokerto',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(784,35,3577,'Kota Madiun',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(785,35,3578,'Kota Surabaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(786,35,3579,'Kota Batu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(787,36,3601,'Kab. Pandeglang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(788,36,3602,'Kab. Lebak',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(789,36,3603,'Kab. Tangerang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(790,36,3604,'Kab. Serang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(791,36,3671,'Kota Tangerang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(792,36,3672,'Kota Cilegon',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(793,36,3673,'Kota Serang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(794,36,3674,'Kota Tangerang Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(795,51,5101,'Kab. Jembrana',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(796,51,5102,'Kab. Tabanan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(797,51,5103,'Kab. Badung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(798,51,5104,'Kab. Gianyar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(799,51,5105,'Kab. Klungkung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(800,51,5106,'Kab. Bangli',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(801,51,5107,'Kab. Karangasem',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(802,51,5108,'Kab. Buleleng',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(803,51,5171,'Kota Denpasar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(804,52,5201,'Kab. Lombok Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(805,52,5202,'Kab. Lombok Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(806,52,5203,'Kab. Lombok Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(807,52,5204,'Kab. Sumbawa',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(808,52,5205,'Kab. Dompu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(809,52,5206,'Kab. Bima',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(810,52,5207,'Kab. Sumbawa Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(811,52,5208,'Kab. Lombok Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(812,52,5271,'Kab. Kota Mataram',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(813,52,5272,'Kota Bima',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(814,53,5301,'Kab. Kupang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(815,53,5302,'Kab. Timor Tengah Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(816,53,5303,'Kab. Timor Tengah Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(817,53,5304,'Kab. Belu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(818,53,5305,'Kab. Alor',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(819,53,5306,'Kab. Flores Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(820,53,5307,'Kab. Sikka',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(821,53,5308,'Kab. Ende',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(822,53,5309,'Kab. Ngada',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(823,53,5310,'Kab. Manggarai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(824,53,5311,'Kab. Sumba Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(825,53,5312,'Kab. Sumba Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(826,53,5313,'Kab. Lembata',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(827,53,5314,'Kab. Rote Ndao',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(828,53,5315,'Kab. Manggarai Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(829,53,5316,'Kab. Nagekeo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(830,53,5317,'Kab. Sumba Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(831,53,5318,'Kab. Sumba Barat Daya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(832,53,5319,'Kab. Manggarai Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(833,53,5320,'Kab. Sabu Raijua',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(834,53,5371,'Kota Kupang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(835,61,6101,'Kab. Sambas',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(836,61,6102,'Kab. Mempawah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(837,61,6103,'Kab. Sanggau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(838,61,6104,'Kab. Ketapang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(839,61,6105,'Kab. Sintang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(840,61,6106,'Kab. Kapuas Hulu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(841,61,6107,'Kab. Bengkayang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(842,61,6108,'Kab. Landak',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(843,61,6109,'Kab. Sekadau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(844,61,6110,'Kab. Melawi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(845,61,6111,'Kab. Kayong Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(846,61,6112,'Kab. Kubu Raya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(847,61,6171,'Kota Pontianak',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(848,61,6172,'Kota Singkawang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(849,62,6201,'Kab. Kotawaringin Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(850,62,6202,'Kab. Kotawaringin Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(851,62,6203,'Kab. Kapuas',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(852,62,6204,'Kab. Barito Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(853,62,6205,'Kab. Barito Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(854,62,6206,'Kab. Katingan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(855,62,6207,'Kab. Seruyan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(856,62,6208,'Kab. Sukamara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(857,62,6209,'Kab. Lamandau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(858,62,6210,'Kab. Gunung Mas',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(859,62,6211,'Kab. Pulang Pisau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(860,62,6212,'Kab. Murung Raya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(861,62,6213,'Kab. Barito Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(862,62,6271,'Kota Palangka Raya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(863,63,6301,'Kab. Tanah Laut',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(864,63,6302,'Kab. Kotabaru',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(865,63,6303,'Kab. Banjar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(866,63,6304,'Kab. Barito Kuala',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(867,63,6305,'Kab. Tapin',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(868,63,6306,'Kab. Hulu Sungai Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(869,63,6307,'Kab. Hulu Sungai Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(870,63,6308,'Kab. Hulu Sungai Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(871,63,6309,'Kab. Tabalong',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(872,63,6310,'Kab. Tanah Bumbu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(873,63,6311,'Kab. Balangan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(874,63,6371,'Kota Banjarmasin',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(875,63,6372,'Kota Banjarbaru',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(876,64,6401,'Kab. Paser',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(877,64,6402,'Kab. Kutai Kartanegara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(878,64,6403,'Kab. Berau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(879,64,6407,'Kab. Kutai Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(880,64,6408,'Kab. Kutai Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(881,64,6409,'Kab. Penajam Paser Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(882,64,6411,'Kab. Mahakam Ulu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(883,64,6471,'Kota Balikpapan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(884,64,6472,'Kota Samarinda',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(885,64,6474,'Kota Bontang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(886,65,6501,'Kab. Bulungan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(887,65,6502,'Kab. Malinau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(888,65,6503,'Kab. Nunukan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(889,65,6504,'Kab. Tana Tidung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(890,65,6571,'Kota Tarakan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(891,71,7101,'Kab. Bolaang Mongondow',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(892,71,7102,'Kab. Minahasa',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(893,71,7103,'Kab. Kepulauan Sangihe',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(894,71,7104,'Kab. Kepulauan Talaud',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(895,71,7105,'Kab. Minahasa Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(896,71,7106,'Kab. Minahasa Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(897,71,7107,'Kab. Minahasa Tenggara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(898,71,7108,'Kab. Bolaang Mongondow Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(899,71,7109,'Kab. Kepulauan Siau Tagulandang Biaro',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(900,71,7110,'Kab. Bolaang Mongondow Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(901,71,7111,'Kab. Bolaang Mongondow Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(902,71,7171,'Kota Manado',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(903,71,7172,'Kota Bitung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(904,71,7173,'Kota Tomohon',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(905,71,7174,'Kota Kotamobagu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(906,72,7201,'Kab. Banggai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(907,72,7202,'Kab. Poso',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(908,72,7203,'Kab. Donggala',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(909,72,7204,'Kab. Toli-Toli',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(910,72,7205,'Kab. Buol',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(911,72,7206,'Kab. Morowali',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(912,72,7207,'Kab. Banggai Kepulauan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(913,72,7208,'Kab. Parigi Moutong',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(914,72,7209,'Kab. Tojo Una-Una',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(915,72,7210,'Kab. Sigi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(916,72,7211,'Kab. Banggai Laut',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(917,72,7212,'Kab. Morowali Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(918,72,7271,'Kota Palu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(919,73,7301,'Kab. Kepulauan Selayar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(920,73,7302,'Kab. Bulukumba',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(921,73,7303,'Kab. Bantaeng',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(922,73,7304,'Kab. Jeneponto',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(923,73,7305,'Kab. Takalar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(924,73,7306,'Kab. Gowa',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(925,73,7307,'Kab. Sinjai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(926,73,7308,'Kab. Bone',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(927,73,7309,'Kab. Maros',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(928,73,7310,'Kab. Pangkajene dan Kepulauan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(929,73,7311,'Kab. Barru',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(930,73,7312,'Kab. Soppeng',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(931,73,7313,'Kab. Wajo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(932,73,7314,'Kab. Sidenreng Rappang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(933,73,7315,'Kab. Pinrang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(934,73,7316,'Kab. Enrekang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(935,73,7317,'Kab. Luwu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(936,73,7318,'Kab. Tana Toraja',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(937,73,7322,'Kab. Luwu Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(938,73,7324,'Kab. Luwu Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(939,73,7326,'Kab. Toraja Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(940,73,7371,'Kota Makassar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(941,73,7372,'Kota Parepare',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(942,73,7373,'Kota Palopo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(943,74,7401,'Kab. Kolaka',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(944,74,7402,'Kab. Konawe',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(945,74,7403,'Kab. Muna',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(946,74,7404,'Kab. Buton',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(947,74,7405,'Kab. Konawe Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(948,74,7406,'Kab. Bombana',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(949,74,7407,'Kab. Wakatobi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(950,74,7408,'Kab. Kolaka Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(951,74,7409,'Kab. Konawe Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(952,74,7410,'Kab. Buton Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(953,74,7411,'Kab. Kolaka Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(954,74,7412,'Kab. Konawe Kepulauan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(955,74,7413,'Kab. Muna Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(956,74,7414,'Kab. Buton Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(957,74,7415,'Kab. Buton Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(958,74,7471,'Kota Kendari',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(959,74,7472,'Kota Bau-Bau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(960,75,7501,'Kab. Gorontalo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(961,75,7502,'Kab. Boalemo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(962,75,7503,'Kab. Bone Bolango',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(963,75,7504,'Kab. Pohuwato',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(964,75,7505,'Kab. Gorontalo Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(965,75,7571,'Kota Gorontalo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(966,76,7601,'Kab. Mamuju Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(967,76,7602,'Kab. Mamuju',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(968,76,7603,'Kab. Mamasa',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(969,76,7604,'Kab. Polewali Mandar',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(970,76,7605,'Kab. Majene',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(971,76,7606,'Kab. Mamuju Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(972,81,8101,'Kab. Maluku Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(973,81,8102,'Kab. Maluku Tenggara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(974,81,8103,'Kab. Maluku Tenggara Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(975,81,8104,'Kab. Buru',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(976,81,8105,'Kab. Seram Bagian Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(977,81,8106,'Kab. Seram Bagian Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(978,81,8107,'Kab. Kepulauan Aru',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(979,81,8108,'Kab. Maluku Barat Daya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(980,81,8109,'Kab. Buru Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(981,81,8171,'Kota Ambon',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(982,81,8172,'Kota Tual',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(983,82,8201,'Kab. Halmahera Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(984,82,8202,'Kab. Halmahera Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(985,82,8203,'Kab. Halmahera Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(986,82,8204,'Kab. Halmahera Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(987,82,8205,'Kab. Kepulauan Sula',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(988,82,8206,'Kab. Halmahera Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(989,82,8207,'Kab. Pulau Morotai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(990,82,8208,'Kab. Pulau Taliabu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(991,82,8271,'Kota Ternate',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(992,82,8272,'Kota Tidore Kepulauan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(993,91,9101,'Kab. Merauke',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(994,91,9102,'Kab. Jayawijaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(995,91,9103,'Kab. Jayapura',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(996,91,9104,'Kab. Nabire',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(997,91,9105,'Kab. Kepulauan Yapen',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(998,91,9106,'Kab. Biak Numfor',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(999,91,9107,'Kab. Puncak Jaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1000,91,9108,'Kab. Paniai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1001,91,9109,'Kab. Mimika',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1002,91,9110,'Kab. Sarmi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1003,91,9111,'Kab. Keerom',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1004,91,9112,'Kab. Pegunungan Bintang',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1005,91,9113,'Kab. Yahukimo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1006,91,9114,'Kab. Tolikara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1007,91,9115,'Kab. Waropen',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1008,91,9116,'Kab. Boven Digoel',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1009,91,9117,'Kab. Mappi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1010,91,9118,'Kab. Asmat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1011,91,9119,'Kab. Supiori',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1012,91,9120,'Kab. Mamberamo Raya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1013,91,9121,'Kab. Mamberamo Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1014,91,9122,'Kab. Yalimo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1015,91,9123,'Kab. Lanny Jaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1016,91,9124,'Kab. Nduga',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1017,91,9125,'Kab. Puncak',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1018,91,9126,'Kab. Dogiyai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1019,91,9127,'Kab. Intan Jaya',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1020,91,9128,'Kab. Deiyai',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1021,91,9171,'Kota Jayapura',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1022,92,9201,'Kab. Sorong',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1023,92,9202,'Kab. Manokwari',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1024,92,9203,'Kab. Fakfak',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1025,92,9204,'Kab. Sorong Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1026,92,9205,'Kab. Raja Ampat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1027,92,9206,'Kab. Teluk Bintuni',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1028,92,9207,'Kab. Teluk Wondama',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1029,92,9208,'Kab. Kaimana',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1030,92,9209,'Kab. Tambrauw',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1031,92,9210,'Kab. Maybrat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1032,92,9211,'Kab. Manokwari Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1033,92,9212,'Kab. Pegunungan Arfak',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1034,92,9271,'Kota Sorong',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1035,32,3218,'Kab. Pangandaran',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(1036,53,5321,'Kab. Malaka',NULL,NULL,NULL,'0000-00-00 00:00:00');

/*Table structure for table `master_items` */

DROP TABLE IF EXISTS `master_items`;

CREATE TABLE `master_items` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `master_items` */

insert  into `master_items`(`id`,`name`,`description`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(1,'Suhu udara',NULL,'1',NULL,NULL,NULL,NULL),
(2,'Pintu',NULL,'1',NULL,NULL,NULL,NULL),
(3,'Lantai',NULL,'1',NULL,NULL,NULL,NULL),
(4,'Dinding',NULL,'1',NULL,NULL,NULL,NULL),
(5,'Plafond',NULL,'1',NULL,NULL,NULL,NULL),
(6,'Lampu',NULL,'1',NULL,NULL,NULL,NULL),
(7,'Tempat sampah',NULL,'1',NULL,NULL,NULL,NULL),
(8,'Chiller',NULL,'1',NULL,NULL,NULL,NULL),
(9,'Alur',NULL,'1',NULL,NULL,NULL,NULL),
(10,'Sink',NULL,'1',NULL,NULL,NULL,NULL),
(11,'APD',NULL,'1',NULL,NULL,NULL,NULL),
(12,'APAR',NULL,'1',NULL,NULL,NULL,NULL),
(13,'Gudang Basah',NULL,'1',NULL,NULL,NULL,NULL),
(14,'Gudang Kering',NULL,'1',NULL,NULL,NULL,NULL),
(15,'Exhaust fan',NULL,'1',NULL,NULL,NULL,NULL),
(16,'Meja Kompor',NULL,'1',NULL,NULL,NULL,NULL),
(17,'Kompor',NULL,'1',NULL,NULL,NULL,NULL),
(18,'Instalasi Gas',NULL,'1',NULL,NULL,NULL,NULL),
(19,'Meja Racik',NULL,'1',NULL,NULL,NULL,NULL),
(20,'Lemari Alat Makan',NULL,'1',NULL,NULL,NULL,NULL),
(21,'Lemari Alat Masak',NULL,'1',NULL,NULL,NULL,NULL),
(22,'Rak Stainless',NULL,'1',NULL,NULL,NULL,NULL),
(23,'Trolley Pemanas',NULL,'1',NULL,NULL,NULL,NULL),
(24,'Trolley Piring kotor',NULL,'1',NULL,NULL,NULL,NULL),
(25,'Trolley Piring bersih',NULL,'1',NULL,NULL,NULL,NULL),
(26,'Kursi makan',NULL,'1',NULL,NULL,NULL,NULL),
(27,'Meja makan',NULL,'1',NULL,NULL,NULL,NULL),
(28,'Wastafel',NULL,'1',NULL,NULL,NULL,NULL),
(29,'Cermin wastafel',NULL,'1',NULL,NULL,NULL,NULL);

/*Table structure for table `master_location` */

DROP TABLE IF EXISTS `master_location`;

CREATE TABLE `master_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `master_location` */

insert  into `master_location`(`id`,`name`,`description`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(1,'Bagian Dalam Bangunan',NULL,'1','1660027205558','2022-08-23 08:43:45',NULL,NULL),
(2,'Mobil Operasional',NULL,'1','1660027205558','2022-08-24 09:47:40',NULL,NULL),
(3,'Mobil Ambulance',NULL,'1','1660027205558','2022-08-24 09:47:40',NULL,NULL),
(4,'Motor Kurir',NULL,'1','1660027205558','2022-08-24 09:47:40',NULL,NULL);

/*Table structure for table `master_parameter` */

DROP TABLE IF EXISTS `master_parameter`;

CREATE TABLE `master_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `true_option` varchar(100) DEFAULT NULL,
  `false_option` varchar(100) DEFAULT NULL,
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `master_parameter` */

insert  into `master_parameter`(`id`,`true_option`,`false_option`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(1,'Aman','Tidak Aman',NULL,NULL,NULL,NULL),
(2,'Bersih','Tidak Bersih',NULL,NULL,NULL,NULL),
(3,'Rapih','Tidak Rapih',NULL,NULL,NULL,NULL),
(4,'Tampak Baru','Tidak Tampak Baru',NULL,NULL,NULL,NULL),
(5,'Ramah Lingkungan','Tidak Ramah Lingkungan',NULL,NULL,NULL,NULL);

/*Table structure for table `master_province` */

DROP TABLE IF EXISTS `master_province`;

CREATE TABLE `master_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

/*Data for the table `master_province` */

insert  into `master_province`(`id`,`code`,`name`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(11,11,'Aceh',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(12,12,'Sumatra Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(13,13,'Sumatra Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(14,14,'Riau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(15,15,'Jambi',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(16,16,'Sumatra Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(17,17,'Bengkulu',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(18,18,'Lampung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(19,19,'Kepulauan Bangka Belitung',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(20,21,'Kepulauan Riau',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(21,31,'Daerah Khusus Ibukota Jakarta',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(22,32,'Jawa Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(23,33,'Jawa Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(24,34,'Daerah Istimewa Yogyakarta',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(25,35,'Jawa Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(26,36,'Banten',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(27,51,'Bali',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(28,52,'Nusa Tenggara Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(29,53,'Nusa Tenggara Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(30,61,'Kalimantan Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(31,62,'Kalimantan Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(32,63,'Kalimantan Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(33,64,'Kalimantan Timur',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(34,65,'Kalimantan Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(35,71,'Sulawesi Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(36,72,'Sulawesi Tengah',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(37,73,'Sulawesi Selatan',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(38,74,'Sulawesi Tenggara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(39,75,'Gorontalo',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(40,76,'Sulawesi Barat',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(41,81,'Maluku',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(42,82,'Maluku Utara',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(43,91,'Papua',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(44,92,'Papua Barat',NULL,NULL,NULL,'0000-00-00 00:00:00');

/*Table structure for table `master_region` */

DROP TABLE IF EXISTS `master_region`;

CREATE TABLE `master_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `master_region` */

insert  into `master_region`(`id`,`name`,`description`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(1,'Regional Wilayah I',NULL,'1','1660027205558','2022-08-22 16:56:11',NULL,NULL),
(3,'Regional Wilayah II',NULL,'1','1660027205558','2022-08-22 16:56:19',NULL,NULL);

/*Table structure for table `master_round` */

DROP TABLE IF EXISTS `master_round`;

CREATE TABLE `master_round` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `start_day` varchar(10) DEFAULT NULL,
  `end_day` varchar(10) DEFAULT NULL,
  `deadline_checker_day` varchar(10) DEFAULT NULL,
  `deadline_verifikator_1_day` varchar(10) DEFAULT NULL,
  `deadline_verifikator_2_day` varchar(10) DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `master_round` */

insert  into `master_round`(`id`,`name`,`start_day`,`end_day`,`deadline_checker_day`,`deadline_verifikator_1_day`,`deadline_verifikator_2_day`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(1,'Ronde 1','01','07','03','05','07','1',NULL,NULL,NULL,NULL),
(2,'Ronde 2','08','14','10','12','14','1',NULL,NULL,NULL,NULL),
(3,'Ronde 3','15','21','17','19','21','1',NULL,NULL,NULL,NULL),
(4,'Ronde 4','22','31','24','26','28','1',NULL,NULL,NULL,NULL);

/*Table structure for table `master_sub_area` */

DROP TABLE IF EXISTS `master_sub_area`;

CREATE TABLE `master_sub_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `data_status` enum('0','1') DEFAULT '1',
  `created_by` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(15) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `area_id` (`area_id`),
  CONSTRAINT `master_sub_area_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `master_area` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

/*Data for the table `master_sub_area` */

insert  into `master_sub_area`(`id`,`area_id`,`name`,`description`,`data_status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values 
(1,1,'Kondisi Keseluruhan Dapur & Pantry',NULL,'1','1660027205558','2022-08-23 09:09:04',NULL,NULL),
(2,1,'Gudang Makanan',NULL,'1','1660027205558','2022-08-23 09:09:15',NULL,NULL),
(3,1,'Dapur (area masak)',NULL,'1','1660027205558','2022-08-23 09:09:24',NULL,NULL),
(4,1,'Penyimpanan Alat-alat',NULL,'1','1660027205558','2022-08-23 09:09:34',NULL,NULL),
(5,1,'Area Distribusi',NULL,'1','1660027205558','2022-08-23 09:09:43',NULL,NULL),
(6,1,'Ruang Makan',NULL,'1','1660027205558','2022-08-23 09:09:52',NULL,NULL),
(7,2,'Kondisi Keseluruhan Laundry',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(8,2,'Toilet (desinfeksi)',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(9,2,'Area Pengeringan',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(10,2,'Area Strika',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(11,2,'Area linen bersih',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(12,3,'Central Gas Medis',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(13,3,'Central Gas LPG',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(14,4,'Area Wudhu',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(15,4,'Area Sholat',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(16,5,'Toilet Umum',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(17,5,'Toilet Disabel',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(18,6,'Mesin',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(19,6,'Filter',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(20,6,'Accu',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(21,6,'Kopling',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(22,6,'Rem',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(23,6,'Roda',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(24,6,'Body Mobil',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(25,6,'Pintu dan jendela',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(26,6,'Kaca',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(27,6,'Wiper',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(28,6,'Spion',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(29,6,'Lampu',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(30,6,'Interior',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(31,6,'Pemanasan',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(32,6,'Surat-surat kendaraan',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(33,7,'Mesin',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(34,7,'Filter',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(35,7,'Accu',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(36,7,'Kopling',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(37,7,'Rem',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(38,7,'Roda',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(39,7,'Body Mobil',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(40,7,'Pintu dan jendela',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(41,7,'Kaca',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(42,7,'Wiper',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(43,7,'Spion',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(44,7,'Lampu',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(45,7,'Interior Pengemudi',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(46,7,'Karoseri Ambulance',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(47,7,'Pemanasan',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(48,7,'Surat-surat kendaraan',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(49,8,'Mesin',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(50,8,'Rem',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(51,8,'Roda',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(52,8,'Body Motor',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(53,8,'Lampu',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(54,8,'Pemanasan',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL),
(55,8,'Surat-surat kendaraan',NULL,'1','1660027205558','2022-08-24 10:57:26',NULL,NULL);

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fcm_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

insert  into `personal_access_tokens`(`id`,`tokenable_type`,`tokenable_id`,`name`,`token`,`fcm_token`,`abilities`,`last_used_at`,`created_at`,`updated_at`) values 
(6,'App\\Models\\User',16096390033565,'3326180101880001','ffe53c55431ca03fa8e29eb6645df5bb8ea5a4b84abeb3b79dbb41be0cc83495',NULL,'[\"*\"]',NULL,'2022-08-09 08:53:53','2022-08-09 08:53:53');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
