# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 8.0.21)
# Database: ipv6ddns
# Generation Time: 2020-10-15 13:53:33 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table acl
# ------------------------------------------------------------

DROP TABLE IF EXISTS `acl`;

CREATE TABLE `acl` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `zone` varchar(192) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client` varchar(192) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client` (`client`),
  KEY `zone` (`zone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table apikey
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apikey`;

CREATE TABLE `apikey` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `apikey` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table db
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db`;

CREATE TABLE `db` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table dns_records
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dns_records`;

CREATE TABLE `dns_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `zone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('MX','CNAME','NS','SOA','A','PTR','AAAA','TXT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ttl` int DEFAULT '600',
  `mx_priority` int DEFAULT NULL,
  `refresh` int DEFAULT '600',
  `retry` int DEFAULT NULL,
  `expire` int DEFAULT '86400',
  `minimum` int DEFAULT '3600',
  `serial` bigint DEFAULT '2020091601',
  `resp_person` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `primary_ns` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `dynaload` tinyint(1) DEFAULT '0',
  `datestamp` datetime DEFAULT NULL,
  `regnumber` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0000000',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `host_index` (`host`),
  KEY `zone_index` (`zone`),
  KEY `type_index` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `dns_records` WRITE;
/*!40000 ALTER TABLE `dns_records` DISABLE KEYS */;

INSERT INTO `dns_records` (`id`, `zone`, `host`, `type`, `data`, `ttl`, `mx_priority`, `refresh`, `retry`, `expire`, `minimum`, `serial`, `resp_person`, `primary_ns`, `dynaload`, `datestamp`, `regnumber`, `created_at`, `updated_at`)
VALUES
	(1,'ddns.idv.tw','www','A','18.219.170.145',600,NULL,600,NULL,86400,3600,2020091601,'','',0,NULL,'0000000','2020-10-13 13:38:56',NULL),
	(2,'ddns.idv.tw','mail','CNAME','www',600,NULL,600,NULL,86400,3600,2020091601,'','',0,NULL,'0000000','2020-10-13 13:38:57',NULL),
	(3,'ddns.idv.tw','@','NS','ns1',60,NULL,600,NULL,86400,3600,2020091601,'','',0,NULL,'0000000','2020-10-13 13:38:57',NULL),
	(4,'ddns.idv.tw','ns1','A','18.219.170.145',600,NULL,600,NULL,86400,3600,2020091601,'','',0,NULL,'0000000','2020-10-13 13:38:57',NULL),
	(5,'ddns.idv.tw','@','NS','ns2',60,NULL,600,NULL,86400,3600,2020091601,'','',0,NULL,'0000000','2020-10-13 13:38:57',NULL),
	(6,'ddns.idv.tw','ns2','A','18.219.170.145',600,NULL,600,NULL,86400,3600,2020091601,'','',0,NULL,'0000000','2020-10-13 13:38:57',NULL);

/*!40000 ALTER TABLE `dns_records` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table email
# ------------------------------------------------------------

DROP TABLE IF EXISTS `email`;

CREATE TABLE `email` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table inactive_zones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inactive_zones`;

CREATE TABLE `inactive_zones` (
  `zone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table ipdata
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ipdata`;

CREATE TABLE `ipdata` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longtiude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_eu` int DEFAULT NULL,
  `region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regioncode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_zone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `country_code` (`country_code`),
  KEY `locale` (`locale`),
  KEY `currency` (`currency`),
  KEY `updated_at` (`updated_at`),
  KEY `latitude` (`latitude`),
  KEY `longtiude` (`longtiude`),
  KEY `currency_symbol` (`currency_symbol`),
  KEY `in_eu` (`in_eu`),
  KEY `region` (`region`),
  KEY `regioncode` (`regioncode`),
  KEY `city` (`city`),
  KEY `time_zone` (`time_zone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table ipfail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ipfail`;

CREATE TABLE `ipfail` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table login
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `email` (`email`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table loginfail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `loginfail`;

CREATE TABLE `loginfail` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `email` (`email`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1783 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table meta_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meta_data`;

CREATE TABLE `meta_data` (
  `next_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table other
# ------------------------------------------------------------

DROP TABLE IF EXISTS `other`;

CREATE TABLE `other` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table replication_heartbeat
# ------------------------------------------------------------

DROP TABLE IF EXISTS `replication_heartbeat`;

CREATE TABLE `replication_heartbeat` (
  `timestamp` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pw` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avator` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `temp_pw` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temp_pw_created` timestamp NULL DEFAULT NULL,
  `temp_pw_expired` timestamp NULL DEFAULT NULL,
  `verify_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `is_deleted` tinyint unsigned DEFAULT '0',
  `is_admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `is_verified` (`is_verified`),
  KEY `verify_code` (`verify_code`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `pw`, `avator`, `created_at`, `temp_pw`, `temp_pw_created`, `temp_pw_expired`, `verify_code`, `is_verified`, `is_deleted`, `is_admin`)
VALUES
	(1,'admin',NULL,NULL,'$2y$10$bfwVcxjq1M4O0Sqqz.6H2udU8.p/bGDRe.wMzpCumUHa/7x9OrY2e',NULL,'2020-08-13 11:13:09',NULL,NULL,NULL,NULL,1,0,1),
	(2,'test1',NULL,NULL,'$2y$10$ky3dWbl3ry/zAXjWLzLFze3zys/app5wXXAgWTLfmevH4jpEHJDMO',NULL,'2020-10-05 18:44:49',NULL,NULL,NULL,NULL,1,0,0),
	(4,'test2',NULL,NULL,'$2y$10$Ou6HeIi8vIK9PKPd4TYz8..gE0dGEVt7yhJLPOo.2AyK8QpxpNAFm',NULL,'2020-10-05 19:17:55',NULL,NULL,NULL,NULL,1,0,1),
	(5,'test3',NULL,NULL,'$2y$10$SA82gnB.jBPN6TfDF8uVg.eWjvIMkhcNsCyFGpd9T64V.s9shcAfa',NULL,'2020-10-13 07:12:20',NULL,NULL,NULL,NULL,1,0,0);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table xfr_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `xfr_table`;

CREATE TABLE `xfr_table` (
  `zone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `client` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `zone_client_index` (`zone`,`client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
