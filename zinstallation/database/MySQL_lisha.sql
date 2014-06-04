-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: lisha
-- ------------------------------------------------------
-- Server version	5.5.37-0+wheezy1-log

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
-- Table structure for table `lisha_config`
--

DROP TABLE IF EXISTS `lisha_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lisha_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'key identifier number',
  `value` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Value of configuration',
  `unite` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Unit of configuration value',
  `FORMAT` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'format expected if any',
  `THEME` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Configuration group',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Group of type',
  `designation` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Main configuration parameters';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lisha_config`
--

LOCK TABLES `lisha_config` WRITE;
/*!40000 ALTER TABLE `lisha_config` DISABLE KEYS */;
INSERT INTO `lisha_config` VALUES (1,'ENG','','string 3 digits','','','Root language'),(2,'ssid','string','','Reserved','','Keyword for session token'),(3,'28800','seconds','','','','Page Timeout\r\nDo not exceed 86400 (24 hours) !!'),(4,'doc_user/picture/','string','','','','Path for user documentation pictures'),(5,'doc_user/video/','string','','','','Path for user documentation video'),(6,'doc_tech/picture/','string','','','','Path for technical documentation pictures'),(7,'doc_tech/video/','string','','','','Path for technical documentation video'),(8,'f1','string','','','','Url key name to load recorder custom view'),(9,'GroupTheme','string','','','','Customer specific column name for theme group'),(10,'FROM -- #LISHA_MAIN_FROM_PARSING#','string','','','','String used to split query FROM outfield to tables and conditions'),(11,'50000','row','numeric positif','Export','','Max rows enable to export'),(12,'LNG','string','','Reserved','','Key word for url language directive'),(13,'ID','string','','Reserved','','Object ID');
/*!40000 ALTER TABLE `lisha_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lisha_i18n`
--

DROP TABLE IF EXISTS `lisha_i18n`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lisha_i18n` (
  `id` char(3) COLLATE utf8_unicode_ci NOT NULL COMMENT 'language identifier',
  `id_tiny` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'description',
  `date_format` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Date format by country',
  `decimal_symbol` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Digit used for decimal',
  `thousand_symbol` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Thousand digit',
  `number_of_decimal` int(2) unsigned NOT NULL COMMENT 'Default how many decimal for float',
  `currency` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Currency digit',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Localization information';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lisha_i18n`
--

LOCK TABLES `lisha_i18n` WRITE;
/*!40000 ALTER TABLE `lisha_i18n` DISABLE KEYS */;
INSERT INTO `lisha_i18n` VALUES ('FRA','fr','Français','%d/%m/%Y',',',' ',3,'€'),('ENG','en','English','%Y-%m-%d','.',',',2,'$');
/*!40000 ALTER TABLE `lisha_i18n` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lisha_filter`
--

DROP TABLE IF EXISTS `lisha_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lisha_filter` (
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `key` mediumint(5) NOT NULL AUTO_INCREMENT,
  `id` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Lisha id used',
  `id_column` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('IEQ','IBT','EEQ','EBT','DMD','QSC','SMD','CPS','ORD','SIZ','ALI') COLLATE utf8_unicode_ci NOT NULL,
  `val1` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `val2` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `val3` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`name`,`id`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Record lisha custom viewer';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lisha_filter`
--

LOCK TABLES `lisha_filter` WRITE;
/*!40000 ALTER TABLE `lisha_filter` DISABLE KEYS */;
INSERT INTO `lisha_filter` VALUES ('Project Manager',37,'lisha_bugs','reference','ALI','12','center','','2013-03-26 21:16:06'),('Project Manager',35,'lisha_bugs','reference','CPS','12','','','2013-03-26 21:16:06'),('Project Manager',36,'lisha_bugs','reference','SMD','12','%','','2013-03-26 21:16:06'),('Project Manager',34,'lisha_bugs','qui','ALI','11','center','','2013-03-26 21:16:06'),('Project Manager',32,'lisha_bugs','qui','CPS','11','','','2013-03-26 21:16:06'),('Project Manager',33,'lisha_bugs','qui','SMD','11','%','','2013-03-26 21:16:06'),('Project Manager',31,'lisha_bugs','details','ALI','10','center','','2013-03-26 21:16:06'),('Project Manager',30,'lisha_bugs','details','SMD','10','%','','2013-03-26 21:16:06'),('Project Manager',29,'lisha_bugs','details','CPS','10','','','2013-03-26 21:16:06'),('Project Manager',28,'lisha_bugs','flag','ALI','9','center','','2013-03-26 21:16:06'),('Project Manager',27,'lisha_bugs','flag','SMD','9','%','','2013-03-26 21:16:06'),('Project Manager',26,'lisha_bugs','flag','CPS','9','','','2013-03-26 21:16:06'),('Project Manager',25,'lisha_bugs','status','ALI','8','center','','2013-03-26 21:16:06'),('Project Manager',24,'lisha_bugs','status','SMD','8','%','','2013-03-26 21:16:06'),('Project Manager',23,'lisha_bugs','status','CPS','8','','','2013-03-26 21:16:06'),('Project Manager',22,'lisha_bugs','Description','ALI','7','left','','2013-03-26 21:16:06'),('Project Manager',21,'lisha_bugs','Description','SMD','7','%','','2013-03-26 21:16:06'),('Project Manager',20,'lisha_bugs','Description','CPS','7','','','2013-03-26 21:16:06'),('Project Manager',19,'lisha_bugs','DateCrea','ALI','6','center','','2013-03-26 21:16:06'),('Project Manager',18,'lisha_bugs','DateCrea','SMD','6','%','','2013-03-26 21:16:06'),('Project Manager',17,'lisha_bugs','DateCrea','CPS','6','','','2013-03-26 21:16:06'),('Project Manager',16,'lisha_bugs','version','ALI','5','center','','2013-03-26 21:16:06'),('Project Manager',15,'lisha_bugs','version','SMD','5','','','2013-03-26 21:16:06'),('Project Manager',14,'lisha_bugs','version','CPS','5','','','2013-03-26 21:16:06'),('Project Manager',13,'lisha_bugs','classe','ALI','4','center','','2013-03-26 21:16:06'),('Project Manager',12,'lisha_bugs','classe','SMD','4','%','','2013-03-26 21:16:06'),('Project Manager',11,'lisha_bugs','classe','CPS','4','','','2013-03-26 21:16:06'),('Project Manager',10,'lisha_bugs','type','ALI','3','left','','2013-03-26 21:16:06'),('Project Manager',9,'lisha_bugs','type','SMD','3','%','','2013-03-26 21:16:06'),('Project Manager',8,'lisha_bugs','type','CPS','3','','','2013-03-26 21:16:06'),('Project Manager',7,'lisha_bugs','business','ALI','2','center','','2013-03-26 21:16:06'),('Project Manager',6,'lisha_bugs','business','SMD','2','','','2013-03-26 21:16:06'),('Project Manager',5,'lisha_bugs','business','QSC','2','Project Manager','','2013-03-26 21:16:06'),('Project Manager',1,'lisha_bugs','id','CPS','1','','','2013-03-26 21:16:06'),('Project Manager',2,'lisha_bugs','id','SMD','1','','','2013-03-26 21:16:06'),('Project Manager',3,'lisha_bugs','id','ALI','1','center','','2013-03-26 21:16:06'),('Project Manager',4,'lisha_bugs','business','CPS','2','','','2013-03-26 21:16:06'),('order',3,'lisha_zdev','daterec','ALI','1','center','','2013-07-22 20:39:50'),('order',1,'lisha_zdev','daterec','CPS','1','','','2013-07-22 20:39:50'),('order',2,'lisha_zdev','daterec','SMD','1','%','','2013-07-22 20:39:50'),('colpos',25,'lisha_zdev','MyGroupTheme','ALI','7','center','','2013-07-12 08:46:00'),('Project Manager',41,'lisha_bugs','last_mod','ALI','13','center','','2013-03-26 21:16:06'),('colpos',19,'lisha_zdev','status','CPS','6','','','2013-07-12 08:46:00'),('date',12,'lisha_zdev','amount','ALI','3','left','','2013-07-11 09:17:02'),('date',13,'lisha_zdev','upper','CPS','4','','','2013-07-11 09:17:02'),('date',14,'lisha_zdev','upper','SMD','4','%','','2013-07-11 09:17:02'),('Project Manager',40,'lisha_bugs','last_mod','SMD','13','%','','2013-03-26 21:16:06'),('Project Manager',39,'lisha_bugs','last_mod','CPS','13','','','2013-03-26 21:16:06'),('Project Manager',38,'lisha_bugs','last_mod','ORD','1','DESC','13','2013-03-26 21:16:06'),('colpos',7,'lisha_zdev','daterec','ALI','2','center','','2013-07-12 08:46:00'),('colpos',8,'lisha_zdev','description','ORD','2','DESC','3','2013-07-12 08:46:00'),('colpos',9,'lisha_zdev','description','CPS','3','','','2013-07-12 08:46:00'),('colpos',10,'lisha_zdev','description','SMD','3','%','','2013-07-12 08:46:00'),('colpos',11,'lisha_zdev','description','ALI','3','left','','2013-07-12 08:46:00'),('colpos',12,'lisha_zdev','amount','ORD','3','ASC','4','2013-07-12 08:46:00'),('colpos',13,'lisha_zdev','amount','CPS','4','','','2013-07-12 08:46:00'),('colpos',14,'lisha_zdev','amount','SMD','4','%','','2013-07-12 08:46:00'),('colpos',15,'lisha_zdev','amount','ALI','4','left','','2013-07-12 08:46:00'),('date',3,'lisha_zdev','daterec','SMD','1','%','','2013-07-11 09:17:02'),('date',4,'lisha_zdev','daterec','ALI','1','center','','2013-07-11 09:17:02'),('date',5,'lisha_zdev','description','ORD','2','DESC','2','2013-07-11 09:17:02'),('date',6,'lisha_zdev','description','CPS','2','','','2013-07-11 09:17:02'),('date',7,'lisha_zdev','description','SMD','2','%','','2013-07-11 09:17:02'),('date',8,'lisha_zdev','description','ALI','2','left','','2013-07-11 09:17:02'),('date',9,'lisha_zdev','amount','ORD','3','ASC','3','2013-07-11 09:17:02'),('date',1,'lisha_zdev','daterec','CPS','1','','','2013-07-11 09:17:02'),('tata',1,'lisha_zdev','daterec','CPS','1','','','2013-11-25 17:45:23'),('colpos',24,'lisha_zdev','MyGroupTheme','SMD','7','%','','2013-07-12 08:46:00'),('colpos',23,'lisha_zdev','MyGroupTheme','DMD','7','','','2013-07-12 08:46:00'),('colpos',22,'lisha_zdev','MyGroupTheme','CPS','7','','','2013-07-12 08:46:00'),('colpos',21,'lisha_zdev','status','ALI','6','center','','2013-07-12 08:46:00'),('colpos',20,'lisha_zdev','status','SMD','6','%','','2013-07-12 08:46:00'),('colpos',18,'lisha_zdev','upper','ALI','5','left','','2013-07-12 08:46:00'),('colpos',16,'lisha_zdev','upper','CPS','5','','','2013-07-12 08:46:00'),('colpos',17,'lisha_zdev','upper','SMD','5','%','','2013-07-12 08:46:00'),('colpos',6,'lisha_zdev','daterec','SMD','2','%','','2013-07-12 08:46:00'),('colpos',5,'lisha_zdev','daterec','CPS','2','','','2013-07-12 08:46:00'),('colpos',4,'lisha_zdev','index','ALI','1','center','','2013-07-12 08:46:00'),('colpos',3,'lisha_zdev','index','SMD','1','%','','2013-07-12 08:46:00'),('colpos',2,'lisha_zdev','index','CPS','1','','','2013-07-12 08:46:00'),('date',26,'lisha_zdev','MyGroupTheme','ALI','7','center','','2013-07-11 09:17:02'),('date',25,'lisha_zdev','MyGroupTheme','SMD','7','%','','2013-07-11 09:17:02'),('date',23,'lisha_zdev','MyGroupTheme','CPS','7','','','2013-07-11 09:17:02'),('date',21,'lisha_zdev','status','SMD','6','%','','2013-07-11 09:17:02'),('date',22,'lisha_zdev','status','ALI','6','center','','2013-07-11 09:17:02'),('date',20,'lisha_zdev','status','CPS','6','','','2013-07-11 09:17:02'),('date',18,'lisha_zdev','index','SMD','5','%','','2013-07-11 09:17:02'),('date',19,'lisha_zdev','index','ALI','5','center','','2013-07-11 09:17:02'),('date',16,'lisha_zdev','index','ORD','1','DESC','5','2013-07-11 09:17:02'),('date',17,'lisha_zdev','index','CPS','5','','','2013-07-11 09:17:02'),('date',15,'lisha_zdev','upper','ALI','4','left','','2013-07-11 09:17:02'),('date',10,'lisha_zdev','amount','CPS','3','','','2013-07-11 09:17:02'),('date',2,'lisha_zdev','daterec','QSC','1','2013-07-11','','2013-07-11 09:17:02'),('tata',2,'lisha_zdev','daterec','SMD','1','%','','2013-11-25 17:45:23'),('colpos',1,'lisha_zdev','index','ORD','1','DESC','1','2013-07-12 08:46:00'),('date',24,'lisha_zdev','MyGroupTheme','DMD','7','','','2013-07-11 09:17:02'),('date',11,'lisha_zdev','amount','SMD','3','%','','2013-07-11 09:17:02'),('test',19,'lisha_zdev','index','ALI','5','center','','2013-07-05 16:10:14'),('test',18,'lisha_zdev','index','SMD','5','%','','2013-07-05 16:10:14'),('test',17,'lisha_zdev','index','CPS','5','','','2013-07-05 16:10:14'),('test',16,'lisha_zdev','index','ORD','1','DESC','5','2013-07-05 16:10:14'),('test',15,'lisha_zdev','upper','ALI','4','left','','2013-07-05 16:10:14'),('test',14,'lisha_zdev','upper','SMD','4','%','','2013-07-05 16:10:14'),('test',13,'lisha_zdev','upper','CPS','4','','','2013-07-05 16:10:14'),('test',12,'lisha_zdev','amount','ALI','3','left','','2013-07-05 16:10:14'),('test',11,'lisha_zdev','amount','SMD','3','%','','2013-07-05 16:10:14'),('test',10,'lisha_zdev','amount','CPS','3','','','2013-07-05 16:10:14'),('test',9,'lisha_zdev','amount','ORD','3','ASC','3','2013-07-05 16:10:14'),('test',8,'lisha_zdev','description','ALI','2','left','','2013-07-05 16:10:14'),('test',7,'lisha_zdev','description','SMD','2','%','','2013-07-05 16:10:14'),('test',6,'lisha_zdev','description','CPS','2','','','2013-07-05 16:10:14'),('test',5,'lisha_zdev','description','ORD','2','DESC','2','2013-07-05 16:10:14'),('test',4,'lisha_zdev','daterec','ALI','1','center','','2013-07-05 16:10:14'),('test',3,'lisha_zdev','daterec','SMD','1','%','','2013-07-05 16:10:14'),('test',2,'lisha_zdev','daterec','QSC','1','2013-07-06','','2013-07-05 16:10:14'),('test',1,'lisha_zdev','daterec','CPS','1','','','2013-07-05 16:10:14'),('test',20,'lisha_zdev','status','CPS','6','','','2013-07-05 16:10:14'),('test',21,'lisha_zdev','status','SMD','6','%','','2013-07-05 16:10:14'),('test',22,'lisha_zdev','status','ALI','6','center','','2013-07-05 16:10:14'),('test',23,'lisha_zdev','MyGroupTheme','CPS','7','','','2013-07-05 16:10:14'),('test',24,'lisha_zdev','MyGroupTheme','DMD','7','','','2013-07-05 16:10:14'),('test',25,'lisha_zdev','MyGroupTheme','SMD','7','%','','2013-07-05 16:10:14'),('test',26,'lisha_zdev','MyGroupTheme','ALI','7','center','','2013-07-05 16:10:14'),('order',4,'lisha_zdev','description','ORD','2','DESC','2','2013-07-22 20:39:50'),('order',5,'lisha_zdev','description','CPS','2','','','2013-07-22 20:39:50'),('order',6,'lisha_zdev','description','SMD','2','%','','2013-07-22 20:39:50'),('order',7,'lisha_zdev','description','ALI','2','left','','2013-07-22 20:39:50'),('order',8,'lisha_zdev','amount','ORD','3','ASC','3','2013-07-22 20:39:50'),('order',9,'lisha_zdev','amount','CPS','3','','','2013-07-22 20:39:50'),('order',10,'lisha_zdev','amount','SMD','3','%','','2013-07-22 20:39:50'),('order',11,'lisha_zdev','amount','ALI','3','left','','2013-07-22 20:39:50'),('order',12,'lisha_zdev','upper','CPS','4','','','2013-07-22 20:39:50'),('order',13,'lisha_zdev','upper','SMD','4','%','','2013-07-22 20:39:50'),('order',14,'lisha_zdev','upper','ALI','4','left','','2013-07-22 20:39:50'),('order',15,'lisha_zdev','index','ORD','1','DESC','5','2013-07-22 20:39:50'),('order',16,'lisha_zdev','index','CPS','5','','','2013-07-22 20:39:50'),('order',17,'lisha_zdev','index','SMD','5','%','','2013-07-22 20:39:50'),('order',18,'lisha_zdev','index','ALI','5','center','','2013-07-22 20:39:50'),('order',19,'lisha_zdev','status','CPS','6','','','2013-07-22 20:39:50'),('order',20,'lisha_zdev','status','SMD','6','%','','2013-07-22 20:39:50'),('order',21,'lisha_zdev','status','ALI','6','center','','2013-07-22 20:39:50'),('order',22,'lisha_zdev','MyGroupTheme','CPS','7','','','2013-07-22 20:39:50'),('order',23,'lisha_zdev','MyGroupTheme','DMD','7','','','2013-07-22 20:39:50'),('order',24,'lisha_zdev','MyGroupTheme','SMD','7','%','','2013-07-22 20:39:50'),('order',25,'lisha_zdev','MyGroupTheme','ALI','7','center','','2013-07-22 20:39:50'),('date_sort',1,'lisha_zdev','daterec','ORD','1','ASC','1','2013-07-23 07:21:09'),('date_sort',2,'lisha_zdev','daterec','CPS','1','','','2013-07-23 07:21:09'),('date_sort',3,'lisha_zdev','daterec','SMD','1','%','','2013-07-23 07:21:09'),('date_sort',4,'lisha_zdev','daterec','ALI','1','center','','2013-07-23 07:21:09'),('date_sort',5,'lisha_zdev','description','CPS','2','','','2013-07-23 07:21:09'),('date_sort',6,'lisha_zdev','description','SMD','2','%','','2013-07-23 07:21:09'),('date_sort',7,'lisha_zdev','description','ALI','2','left','','2013-07-23 07:21:09'),('date_sort',8,'lisha_zdev','amount','CPS','3','','','2013-07-23 07:21:09'),('date_sort',9,'lisha_zdev','amount','SMD','3','%','','2013-07-23 07:21:09'),('date_sort',10,'lisha_zdev','amount','ALI','3','left','','2013-07-23 07:21:09'),('date_sort',11,'lisha_zdev','upper','CPS','4','','','2013-07-23 07:21:09'),('date_sort',12,'lisha_zdev','upper','SMD','4','%','','2013-07-23 07:21:09'),('date_sort',13,'lisha_zdev','upper','ALI','4','left','','2013-07-23 07:21:09'),('date_sort',14,'lisha_zdev','index','CPS','5','','','2013-07-23 07:21:09'),('date_sort',15,'lisha_zdev','index','SMD','5','%','','2013-07-23 07:21:09'),('date_sort',16,'lisha_zdev','index','ALI','5','center','','2013-07-23 07:21:09'),('date_sort',17,'lisha_zdev','status','CPS','6','','','2013-07-23 07:21:09'),('date_sort',18,'lisha_zdev','status','SMD','6','%','','2013-07-23 07:21:09'),('date_sort',19,'lisha_zdev','status','ALI','6','center','','2013-07-23 07:21:09'),('date_sort',20,'lisha_zdev','MyGroupTheme','CPS','7','','','2013-07-23 07:21:09'),('date_sort',21,'lisha_zdev','MyGroupTheme','DMD','7','','','2013-07-23 07:21:09'),('date_sort',22,'lisha_zdev','MyGroupTheme','SMD','7','%','','2013-07-23 07:21:09'),('date_sort',23,'lisha_zdev','MyGroupTheme','ALI','7','center','','2013-07-23 07:21:09'),('tata',1,'lisha_transaction','daterec','CPS','1','','','2013-09-05 13:37:30'),('tata',2,'lisha_transaction','daterec','SMD','1','%','','2013-09-05 13:37:30'),('tata',3,'lisha_transaction','daterec','ALI','1','center','','2013-09-05 13:37:30'),('tata',4,'lisha_transaction','description','CPS','2','','','2013-09-05 13:37:30'),('tata',5,'lisha_transaction','description','SMD','2','%','','2013-09-05 13:37:30'),('tata',6,'lisha_transaction','description','ALI','2','left','','2013-09-05 13:37:30'),('tata',7,'lisha_transaction','mode','CPS','3','','','2013-09-05 13:37:30'),('tata',8,'lisha_transaction','mode','SMD','3','%','','2013-09-05 13:37:30'),('tata',9,'lisha_transaction','mode','ALI','3','left','','2013-09-05 13:37:30'),('tata',10,'lisha_transaction','text','CPS','4','','','2013-09-05 13:37:30'),('tata',11,'lisha_transaction','text','SMD','4','%','','2013-09-05 13:37:30'),('tata',12,'lisha_transaction','text','ALI','4','left','','2013-09-05 13:37:30'),('tata',13,'lisha_transaction','amount','CPS','5','','','2013-09-05 13:37:30'),('tata',14,'lisha_transaction','amount','SMD','5','%','','2013-09-05 13:37:30'),('tata',15,'lisha_transaction','amount','ALI','5','left','','2013-09-05 13:37:30'),('tata',16,'lisha_transaction','encrypt','CPS','6','','','2013-09-05 13:37:30'),('tata',17,'lisha_transaction','encrypt','SMD','6','%','','2013-09-05 13:37:30'),('tata',18,'lisha_transaction','encrypt','ALI','6','left','','2013-09-05 13:37:30'),('tata',19,'lisha_transaction','index','CPS','7','','','2013-09-05 13:37:30'),('tata',20,'lisha_transaction','index','SMD','7','%','','2013-09-05 13:37:30'),('tata',21,'lisha_transaction','index','ALI','7','center','','2013-09-05 13:37:30'),('tata',22,'lisha_transaction','checkme','CPS','8','','','2013-09-05 13:37:30'),('tata',23,'lisha_transaction','checkme','SMD','8','%','','2013-09-05 13:37:30'),('tata',24,'lisha_transaction','checkme','ALI','8','center','','2013-09-05 13:37:30'),('tata',25,'lisha_transaction','status','CPS','9','','','2013-09-05 13:37:30'),('tata',26,'lisha_transaction','status','SMD','9','%','','2013-09-05 13:37:30'),('tata',27,'lisha_transaction','status','ALI','9','center','','2013-09-05 13:37:30'),('tata',28,'lisha_transaction','datum','CPS','10','','','2013-09-05 13:37:30'),('tata',29,'lisha_transaction','datum','SMD','10','%','','2013-09-05 13:37:30'),('tata',30,'lisha_transaction','datum','ALI','10','center','','2013-09-05 13:37:30'),('tata',31,'lisha_transaction','MyGroupTheme','CPS','11','','','2013-09-05 13:37:30'),('tata',32,'lisha_transaction','MyGroupTheme','DMD','11','','','2013-09-05 13:37:30'),('tata',33,'lisha_transaction','MyGroupTheme','SMD','11','%','','2013-09-05 13:37:30'),('tata',34,'lisha_transaction','MyGroupTheme','ALI','11','center','','2013-09-05 13:37:30'),('tata',3,'lisha_zdev','daterec','ALI','1','center','','2013-11-25 17:45:23'),('tata',4,'lisha_zdev','otherdate','CPS','2','','','2013-11-25 17:45:23'),('tata',5,'lisha_zdev','otherdate','SMD','2','%','','2013-11-25 17:45:23'),('tata',6,'lisha_zdev','otherdate','ALI','2','center','','2013-11-25 17:45:23'),('tata',7,'lisha_zdev','password','CPS','3','','','2013-11-25 17:45:23'),('tata',8,'lisha_zdev','password','SMD','3','__CONTAIN__','','2013-11-25 17:45:23'),('tata',9,'lisha_zdev','password','ALI','3','left','','2013-11-25 17:45:23'),('tata',10,'lisha_zdev','description','ORD','2','DESC','4','2013-11-25 17:45:23'),('tata',11,'lisha_zdev','description','CPS','4','','','2013-11-25 17:45:23'),('tata',12,'lisha_zdev','description','SMD','4','__CONTAIN__','','2013-11-25 17:45:23'),('tata',13,'lisha_zdev','description','ALI','4','left','','2013-11-25 17:45:23'),('tata',14,'lisha_zdev','amount','ORD','3','ASC','5','2013-11-25 17:45:23'),('tata',15,'lisha_zdev','amount','CPS','5','','','2013-11-25 17:45:23'),('tata',16,'lisha_zdev','amount','SMD','5','__CONTAIN__','','2013-11-25 17:45:23'),('tata',17,'lisha_zdev','amount','ALI','5','left','','2013-11-25 17:45:23'),('tata',18,'lisha_zdev','upper','CPS','6','','','2013-11-25 17:45:23'),('tata',19,'lisha_zdev','upper','SMD','6','__CONTAIN__','','2013-11-25 17:45:23'),('tata',20,'lisha_zdev','upper','ALI','6','left','','2013-11-25 17:45:23'),('tata',21,'lisha_zdev','index','ORD','1','DESC','7','2013-11-25 17:45:23'),('tata',22,'lisha_zdev','index','CPS','7','','','2013-11-25 17:45:23'),('tata',23,'lisha_zdev','index','SMD','7','__CONTAIN__','','2013-11-25 17:45:23'),('tata',24,'lisha_zdev','index','ALI','7','center','','2013-11-25 17:45:23'),('tata',25,'lisha_zdev','status','CPS','8','','','2013-11-25 17:45:23'),('tata',26,'lisha_zdev','status','SMD','8','__CONTAIN__','','2013-11-25 17:45:23'),('tata',27,'lisha_zdev','status','ALI','8','center','','2013-11-25 17:45:23'),('tata',28,'lisha_zdev','MyGroupTheme','CPS','9','','','2013-11-25 17:45:23'),('tata',29,'lisha_zdev','MyGroupTheme','DMD','9','','','2013-11-25 17:45:23'),('tata',30,'lisha_zdev','MyGroupTheme','SMD','9','__CONTAIN__','','2013-11-25 17:45:23'),('tata',31,'lisha_zdev','MyGroupTheme','ALI','9','center','','2013-11-25 17:45:23'),('tata',32,'lisha_zdev','##GLOBALF##','IEQ','.','','','2013-11-25 17:45:23');
/*!40000 ALTER TABLE `lisha_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lisha_internal`
--

DROP TABLE IF EXISTS `lisha_internal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lisha_internal` (
  `id` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'lisha key',
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'feature name',
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'displayed name',
  `type` enum('IEQ','IBT','EEQ','EBT','DMD','QSC','SMD','CPS','ORD','SIZ','ALI') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Class of data',
  `ordre` int(5) unsigned NOT NULL COMMENT 'row order',
  `low` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'feature low value',
  `high` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'high value',
  `low1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'low feature1',
  `high1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'high feature1',
  `low2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'low feature2',
  `high2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'high feature2',
  `low3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'low feature3',
  `high3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'high feature3',
  `low4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'low feature4',
  `high4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'high feature4',
  `tlu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'time last update',
  UNIQUE KEY `id` (`id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='internal use only';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lisha_internal`
--

LOCK TABLES `lisha_internal` WRITE;
/*!40000 ALTER TABLE `lisha_internal` DISABLE KEYS */;
INSERT INTO `lisha_internal` VALUES ('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','daterec','datum ⛵',NULL,1,'1',NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','description','⏩ ♌',NULL,2,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','mode','Mymodule',NULL,3,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','my_numeric','MyNum',NULL,4,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','thistext','ModuleLibHere',NULL,5,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','amount','',NULL,6,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','encrypt','Maj xxxxx',NULL,7,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','index','Libid',NULL,8,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','checkme','Libcheckbox',NULL,9,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','status','MyColorStatus',NULL,10,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','datum','other date',NULL,11,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','MyGroupTheme','MyGroupTheme',NULL,12,'0',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('5a8a5bced3b45d8f5b2eaf5225daeeca038d13591177871438lisha_transaction','text','Mass text',NULL,13,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:13:02'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','daterec','date',NULL,1,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','otherdate','Date Time !!',NULL,2,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','password','Encode/Decode',NULL,3,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','description','Caption',NULL,4,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','amount','normal',NULL,5,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','upper','Upper',NULL,6,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','index','id',NULL,7,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','icon','my_icon',NULL,8,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','status','status',NULL,9,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('0311ecf95b937c0a78bc32554b8468a084b558d2903399011lisha_zdev','MyGroupTheme','MyGroupTheme',NULL,10,'0',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-02 20:16:06'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','daterec','date',NULL,1,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','otherdate','Date Time !!',NULL,2,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','password','Encode/Decode',NULL,3,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','description','Caption',NULL,4,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','amount','normal',NULL,5,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','upper','Upper',NULL,6,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','index','id',NULL,7,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','icon','my_icon',NULL,8,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','status','status',NULL,9,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00'),('ccff1fdec37dc93e98dbfd9cc567c0631c835ced559980153lisha_zdev','MyGroupTheme','MyGroupTheme',NULL,10,'0',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-06-03 04:13:00');
/*!40000 ALTER TABLE `lisha_internal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lisha_internal_filter`
--

DROP TABLE IF EXISTS `lisha_internal_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lisha_internal_filter` (
  `id` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'lisha key',
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'feature name',
  `type` enum('IEQ','IBT','EEQ','EBT') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'group of value and range',
  `count` int(5) NOT NULL AUTO_INCREMENT COMMENT 'row order',
  `operator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'If not defined, use default one',
  `low` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Low value',
  `high` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'high value for range',
  UNIQUE KEY `id` (`id`,`name`,`type`,`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='internal use only';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lisha_internal_filter`
--

LOCK TABLES `lisha_internal_filter` WRITE;
/*!40000 ALTER TABLE `lisha_internal_filter` DISABLE KEYS */;
INSERT INTO `lisha_internal_filter` VALUES ('647509fd1de4e28ca58a8f979be0a283477775981124854266lisha_zdev','description','IEQ',1,NULL,'a',NULL),('647509fd1de4e28ca58a8f979be0a283477775981124854266lisha_zdev','description','IEQ',2,NULL,'b',NULL),('647509fd1de4e28ca58a8f979be0a283477775981124854266lisha_zdev','description','IEQ',3,NULL,'c',NULL),('647509fd1de4e28ca58a8f979be0a283477775981124854266lisha_zdev','description','IEQ',4,NULL,'d',NULL),('55b696e364c565f1af5be180e8183cf0062b92711059754796lisha_zdev','description','IEQ',2,NULL,'b',NULL),('55b696e364c565f1af5be180e8183cf0062b92711059754796lisha_zdev','description','IEQ',1,NULL,'a',NULL),('647509fd1de4e28ca58a8f979be0a283477775981124854266lisha_zdev','description','IEQ',7,NULL,'g',NULL),('647509fd1de4e28ca58a8f979be0a283477775981124854266lisha_zdev','description','IEQ',6,NULL,'f',NULL),('647509fd1de4e28ca58a8f979be0a283477775981124854266lisha_zdev','description','IEQ',5,NULL,'e',NULL);
/*!40000 ALTER TABLE `lisha_internal_filter` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-03  6:32:44
