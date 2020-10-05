-- MySQL dump 10.13  Distrib 8.0.21, for Linux (x86_64)
--
-- Host: localhost    Database: ipv6ddns
-- ------------------------------------------------------
-- Server version	8.0.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `ipv6ddns`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ipv6ddns` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `ipv6ddns`;

--
-- Table structure for table `apikey`
--

DROP TABLE IF EXISTS `apikey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apikey` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `apikey` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apikey`
--

LOCK TABLES `apikey` WRITE;
/*!40000 ALTER TABLE `apikey` DISABLE KEYS */;
INSERT INTO `apikey` VALUES (1,'2319af8b385e95b3f3601cca9a029baa5b51dc20a63a1c696b7bf35b85e4c3d3'),(2,'7273d2921759b87847288d97522e0f81624800b0ba874497db2e0827b62eb72f'),(3,'de8a2f0e0089cc9200beaf07f6643e8d76d398d0a163976d590f5f6b5c722db4');
/*!40000 ALTER TABLE `apikey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `db`
--

DROP TABLE IF EXISTS `db`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `db`
--

LOCK TABLES `db` WRITE;
/*!40000 ALTER TABLE `db` DISABLE KEYS */;
/*!40000 ALTER TABLE `db` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dns_records`
--

DROP TABLE IF EXISTS `dns_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dns_records`
--

LOCK TABLES `dns_records` WRITE;
/*!40000 ALTER TABLE `dns_records` DISABLE KEYS */;
INSERT INTO `dns_records` VALUES (1,'qualityitinc.com','www','A','172.104.172.135',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:36:49'),(2,'qualityitinc.com','cloud','A','192.168.1.3',60,0,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-23 08:21:46'),(3,'qualityitinc.com','ns','A','172.104.172.135',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:36:53'),(4,'qualityitinc.com','blog','CNAME','cloud.qualityitinc.com.',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:35:46'),(5,'qualityitinc.com','@','NS','ns.qualityitinc.com.',60,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:35:49'),(6,'qualityitinc.com','@','SOA','ns',60,NULL,28800,14400,86400,86400,2020091601,'admin',NULL,NULL,NULL,'0000000','2020-09-17 06:27:01','2020-09-17 07:35:38'),(7,'172.104.172.in-addr.arpa','@','SOA','www.qualityitinc.com.',86400,NULL,3600,15,86400,3600,2020091601,'www.qualityitinc.com.','www.qualityitinc.com.',NULL,NULL,'0000000','2020-09-17 06:27:08','2020-09-17 07:37:09'),(8,'172.104.172.in-addr.arpa','@','NS','www.qualityitinc.com.',600,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:15','2020-09-17 07:37:13'),(9,'172.104.172.in-addr.arpa','250','PTR','www.qualityitinc.com.',600,0,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:21','2020-09-21 04:31:02'),(10,'172.104.172.in-addr.arpa','111','PTR','www.qualityitinc.com.',600,NULL,600,NULL,86400,3600,2020091601,NULL,NULL,NULL,NULL,'0000000','2020-09-17 06:27:21','2020-09-17 07:37:20'),(12,'qualityitinc.com','test','AAAA','2600::',600,0,600,15,86400,3600,202009230808,'admin','dns.google',0,NULL,'0000000','2020-09-23 08:08:07',NULL);
/*!40000 ALTER TABLE `dns_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email`
--

LOCK TABLES `email` WRITE;
/*!40000 ALTER TABLE `email` DISABLE KEYS */;
/*!40000 ALTER TABLE `email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inactive_zones`
--

DROP TABLE IF EXISTS `inactive_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inactive_zones` (
  `zone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inactive_zones`
--

LOCK TABLES `inactive_zones` WRITE;
/*!40000 ALTER TABLE `inactive_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `inactive_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipdata`
--

DROP TABLE IF EXISTS `ipdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipdata`
--

LOCK TABLES `ipdata` WRITE;
/*!40000 ALTER TABLE `ipdata` DISABLE KEYS */;
/*!40000 ALTER TABLE `ipdata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipfail`
--

DROP TABLE IF EXISTS `ipfail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ipfail` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipfail`
--

LOCK TABLES `ipfail` WRITE;
/*!40000 ALTER TABLE `ipfail` DISABLE KEYS */;
/*!40000 ALTER TABLE `ipfail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login`
--

LOCK TABLES `login` WRITE;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` VALUES (1,NULL,'210.61.97.134','2020-09-21 08:41:09','179b528f2e76c5c6d70197413203d24dd771e3e5'),(2,NULL,'114.136.234.247','2020-09-23 08:06:28','8fc25bb9f18d06bdfdfd8b3582cde2b89a5db506'),(3,NULL,'114.136.234.247','2020-09-23 09:17:36','8fc25bb9f18d06bdfdfd8b3582cde2b89a5db506'),(4,NULL,'210.61.97.134','2020-09-24 09:03:20','179b528f2e76c5c6d70197413203d24dd771e3e5');
/*!40000 ALTER TABLE `login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loginfail`
--

DROP TABLE IF EXISTS `loginfail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loginfail`
--

LOCK TABLES `loginfail` WRITE;
/*!40000 ALTER TABLE `loginfail` DISABLE KEYS */;
INSERT INTO `loginfail` VALUES (1782,'admin@x.y','172.17.0.1','2020-09-17 08:04:17','c7893359e18be668fbf4abdb6d61dcc6e8446465');
/*!40000 ALTER TABLE `loginfail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meta_data`
--

DROP TABLE IF EXISTS `meta_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meta_data` (
  `next_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meta_data`
--

LOCK TABLES `meta_data` WRITE;
/*!40000 ALTER TABLE `meta_data` DISABLE KEYS */;
INSERT INTO `meta_data` VALUES (230915);
/*!40000 ALTER TABLE `meta_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `other`
--

DROP TABLE IF EXISTS `other`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `other`
--

LOCK TABLES `other` WRITE;
/*!40000 ALTER TABLE `other` DISABLE KEYS */;
/*!40000 ALTER TABLE `other` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `replication_heartbeat`
--

DROP TABLE IF EXISTS `replication_heartbeat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `replication_heartbeat` (
  `timestamp` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `replication_heartbeat`
--

LOCK TABLES `replication_heartbeat` WRITE;
/*!40000 ALTER TABLE `replication_heartbeat` DISABLE KEYS */;
INSERT INTO `replication_heartbeat` VALUES ('2020-09-17 01:04:22');
/*!40000 ALTER TABLE `replication_heartbeat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `is_admin` tinyint unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `is_verified` (`is_verified`),
  KEY `verify_code` (`verify_code`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin',NULL,NULL,'$2y$10$0/o6nkiQzGj/ELdmoATKVODu.wr0araPqzN8cTlacOcGdc9l6vbrW',NULL,'2020-08-13 11:13:09',NULL,NULL,NULL,NULL,1,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xfr_table`
--

DROP TABLE IF EXISTS `xfr_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `xfr_table` (
  `zone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `client` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `zone_client_index` (`zone`,`client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xfr_table`
--

LOCK TABLES `xfr_table` WRITE;
/*!40000 ALTER TABLE `xfr_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `xfr_table` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-24  9:27:46
