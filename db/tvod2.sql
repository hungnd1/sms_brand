-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: tvod2
-- ------------------------------------------------------
-- Server version	5.5.40-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ads`
--



--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `fk_auth_assignment_user1_idx` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_assignment_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `acc_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--






--
-- Table structure for table `site`
--

DROP TABLE IF EXISTS `site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text,
  `status` int(11) NOT NULL DEFAULT '10' COMMENT '10 - active, \n0 - inactive, \n3 - testing',
  `type` smallint(6) NOT NULL DEFAULT '1' COMMENT '1 - noi dia\n2 - quoc te',
  `website` varchar(255) DEFAULT NULL,
  `cp_revernue_percent` double NOT NULL DEFAULT '0',
  `user_admin_id` int(11) DEFAULT NULL,
  `service_sms_number` varchar(126) DEFAULT NULL,
  `free_video_count` int(11) NOT NULL DEFAULT '10' COMMENT 'so luong video mien phi duoc xem trong 1 chu ky free_video_cycle',
  `free_video_cycle` int(11) NOT NULL DEFAULT '7',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `default_service_id` int(11) DEFAULT NULL,
  `currency` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site`
--

LOCK TABLES `site` WRITE;
/*!40000 ALTER TABLE `site` DISABLE KEYS */;
/*!40000 ALTER TABLE `site` ENABLE KEYS */;
UNLOCK TABLES;


INSERT INTO `site`
 (`id`, `name`, `description`, `status`, `type`, `website`, `cp_revernue_percent`, `user_admin_id`, `service_sms_number`, `free_video_count`, `free_video_cycle`, `created_at`, `updated_at`, `default_service_id`, `currency`)
 VALUES ('1', 'Việt Nam', 'Không thao tác trên site này nếu không Client sẽ không kết nối được với API', '10', '1', 'vivas', '70', '2', '8x85', '10', '7', '1469415781', '1470640498', '1', 'VND');

--
-- Table structure for table `site_api_credential`
--

DROP TABLE IF EXISTS `site_api_credential`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_api_credential` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `type` smallint(6) NOT NULL DEFAULT '1' COMMENT '1 - web client (can co secret key cho server va apikey)\n2 - android client (can co api key, packagename va certificate fingerprint\n3 - ios\n4 - windows phone',
  `client_api_key` varchar(128) NOT NULL COMMENT 'dung cho tat cac moi client',
  `client_secret` varchar(128) DEFAULT NULL COMMENT 'dung cho web, ios, windows',
  `description` varchar(1024) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '10' COMMENT '10 - active, \n0 - suspended, \n...',
  `package_name` varchar(200) DEFAULT NULL,
  `certificate_fingerprint` varchar(1024) DEFAULT NULL,
  `bundle_id` varchar(200) DEFAULT NULL,
  `appstore_id` varchar(200) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_service_provider_api_credential_service_provider1_idx` (`site_id`),
  CONSTRAINT `fk_service_provider_api_credential_service_provider1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_api_credential`
--

LOCK TABLES `site_api_credential` WRITE;
/*!40000 ALTER TABLE `site_api_credential` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_api_credential` ENABLE KEYS */;
UNLOCK TABLES;


INSERT INTO `site_api_credential` (`id`, `site_id`, `client_name`, `type`, `client_api_key`, `client_secret`, `description`, `status`, `package_name`, `certificate_fingerprint`, `bundle_id`, `appstore_id`, `created_at`, `updated_at`) VALUES ('5', '1', 'KeyForWeb', '0', '19f7qpvuvl39szgnzc4wtmuzdzpqiej9', 'eo6u3sfp38omyuzoxuag7v6aklrsq0su', 'Key dành cho Web', '10', '', '', '', '', '1469436105', '1469436105');

--
-- Table structure for table `site_streaming_server_asm`
--


--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `type` smallint(6) NOT NULL DEFAULT '1' COMMENT '1 - Admin\n2 - SP\n3 - dealer',
  `site_id` int(10) DEFAULT NULL,
  `dealer_id` int(10) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT 'ID cua accout me',
  `fullname` varchar(255) DEFAULT NULL,
  `user_ref_id` int(11) DEFAULT NULL,
  `access_login_token` varchar(255) DEFAULT NULL,
  `phone_number` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_service_provider1_idx` (`site_id`),
  KEY `fk_user_content_provider1_idx` (`dealer_id`),
  KEY `fk_user_user1_idx` (`parent_id`),
  CONSTRAINT `fk_user_content_provider1` FOREIGN KEY (`dealer_id`) REFERENCES `dealer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_service_provider1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_user1` FOREIGN KEY (`parent_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16  COMMENT='quan ly cac site (tvod viet nam, tvod nga, tvod sec...)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `action` varchar(126) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL COMMENT 'id cua doi tuong tac dong\n(phim, user...)',
  `target_type` smallint(6) DEFAULT NULL COMMENT '1 - user\n2 - cat\n3 - content\n4 - subscriber\n5 - ...',
  `created_at` int(11) DEFAULT NULL,
  `description` text,
  `status` varchar(255) DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  `dealer_id` int(10) DEFAULT NULL,
  `request_detail` varchar(256) DEFAULT NULL,
  `request_params` text,
  PRIMARY KEY (`id`),
  KEY `fk_user_activity_user1_idx` (`user_id`),
  KEY `fk_user_activity_service_provider1_idx` (`site_id`),
  KEY `fk_user_activity_content_provider1_idx` (`dealer_id`),
  CONSTRAINT `fk_user_activity_content_provider1` FOREIGN KEY (`dealer_id`) REFERENCES `dealer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_activity_service_provider1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_activity_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=160 ;
/*!40101 SET character_set_client = @saved_cs_client */;


INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `email`, `role`, `status`, `created_at`, `updated_at`, `type`, `site_id`, `fullname`) VALUES ('1', 'admin', 'IYyGPl-d1h-FaWGGtoNBBqpLZhW0sEqX', '$2y$13$erkMfk9ApOuqChjH7zcvX.0.5TvhMjJbcqyYJaFFfB7Xpk5c.YI5a', 'admin@vivas.vn', '10', '10', '1469415780', '1470701167', '1', '1', 'btvvcnmvnc');

--
-- Dumping data for table `user_activity`
--

LOCK TABLES `user_activity` WRITE;
/*!40000 ALTER TABLE `user_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_activity` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-04  9:11:39
