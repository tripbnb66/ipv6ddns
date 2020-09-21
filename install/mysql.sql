# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 8.0.17)
# Database: ipv6ddns
# Generation Time: 2020-09-21 08:15:28 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table apikey
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apikey`;

CREATE TABLE `apikey` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apikey` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `apikey` WRITE;
/*!40000 ALTER TABLE `apikey` DISABLE KEYS */;

INSERT INTO `apikey` (`id`, `apikey`)
VALUES
	(1,'2319af8b385e95b3f3601cca9a029baa5b51dc20a63a1c696b7bf35b85e4c3d3'),
	(2,'7273d2921759b87847288d97522e0f81624800b0ba874497db2e0827b62eb72f'),
	(3,'de8a2f0e0089cc9200beaf07f6643e8d76d398d0a163976d590f5f6b5c722db4');

/*!40000 ALTER TABLE `apikey` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table db
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db`;

CREATE TABLE `db` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `zone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('MX','CNAME','NS','SOA','A','PTR','AAAA','TXT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ttl` int(11) DEFAULT '600',
  `mx_priority` int(11) DEFAULT NULL,
  `refresh` int(11) DEFAULT '600',
  `retry` int(11) DEFAULT NULL,
  `expire` int(11) DEFAULT '86400',
  `minimum` int(11) DEFAULT '3600',
  `serial` bigint(20) DEFAULT '2020091601',
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `dns_records` WRITE;
/*!40000 ALTER TABLE `dns_records` DISABLE KEYS */;

INSERT INTO `dns_records` (`id`, `zone`, `host`, `type`, `data`, `ttl`, `mx_priority`, `refresh`, `retry`, `expire`, `minimum`, `serial`, `resp_person`, `primary_ns`, `dynaload`, `datestamp`, `regnumber`, `created_at`, `updated_at`)
VALUES
	(1,'example.com','www','A','1.1.1.1',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:36:49'),
	(2,'example.com','cloud','A','1.1.1.1',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:36:51'),
	(3,'example.com','ns','A','1.1.1.1',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:36:53'),
	(4,'example.com','blog','CNAME','cloud.example.com.',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:35:46'),
	(5,'example.com','@','NS','ns.example.com.',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:35:49'),
	(6,'example.com','@','SOA','ns',60,NULL,28800,14400,86400,86400,2020091601,'admin',NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:35:38'),
	(7,'172.104.172.in-addr.arpa','@','SOA','www.example.com.',86400,NULL,3600,15,86400,3600,2020091601,'www.example.com.','www.example.com.',NULL,NULL,'0000000','2020-09-17 06:27:08','2020-09-17 07:37:09'),
	(8,'172.104.172.in-addr.arpa','@','NS','www.example.com.',600,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:15','2020-09-17 07:37:13'),
	(9,'172.104.172.in-addr.arpa','250','PTR','www.example.com.',600,0,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:21','2020-09-21 04:31:02'),
	(10,'172.104.172.in-addr.arpa','111','PTR','www.example.com.',600,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:21','2020-09-17 07:37:20');

/*!40000 ALTER TABLE `dns_records` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table email
# ------------------------------------------------------------

DROP TABLE IF EXISTS `email`;

CREATE TABLE `email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longtiude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_eu` int(11) DEFAULT NULL,
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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

LOCK TABLES `loginfail` WRITE;
/*!40000 ALTER TABLE `loginfail` DISABLE KEYS */;

INSERT INTO `loginfail` (`id`, `email`, `ip`, `created_at`, `checkval`)
VALUES
	(1782,'admin@x.y','172.17.0.1','2020-09-17 08:04:17','c7893359e18be668fbf4abdb6d61dcc6e8446465');

/*!40000 ALTER TABLE `loginfail` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table meta_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meta_data`;

CREATE TABLE `meta_data` (
  `next_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `meta_data` WRITE;
/*!40000 ALTER TABLE `meta_data` DISABLE KEYS */;

INSERT INTO `meta_data` (`next_id`)
VALUES
	(230915);

/*!40000 ALTER TABLE `meta_data` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table other
# ------------------------------------------------------------

DROP TABLE IF EXISTS `other`;

CREATE TABLE `other` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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

LOCK TABLES `replication_heartbeat` WRITE;
/*!40000 ALTER TABLE `replication_heartbeat` DISABLE KEYS */;

INSERT INTO `replication_heartbeat` (`timestamp`)
VALUES
	('2020-09-17 01:04:22');

/*!40000 ALTER TABLE `replication_heartbeat` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `is_deleted` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `is_verified` (`is_verified`),
  KEY `verify_code` (`verify_code`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `pw`, `avator`, `created_at`, `temp_pw`, `temp_pw_created`, `temp_pw_expired`, `verify_code`, `is_verified`, `is_deleted`)
VALUES
	(1,'admin',NULL,NULL,'$2y$10$0/o6nkiQzGj/ELdmoATKVODu.wr0araPqzN8cTlacOcGdc9l6vbrW',NULL,'2020-08-13 11:13:09',NULL,NULL,NULL,NULL,1,0);

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
