-- MySQL dump 10.11
--
-- Host: localhost    Database: workshop_raleigh
-- ------------------------------------------------------
-- Server version	5.0.41

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
-- Table structure for table `commands`
--

DROP TABLE IF EXISTS `commands`;
CREATE TABLE `commands` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `snippet_command_count` int(11) NOT NULL default '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commands`
--

LOCK TABLES `commands` WRITE;
/*!40000 ALTER TABLE `commands` DISABLE KEYS */;
INSERT INTO `commands` VALUES ('48b69c67-dcdc-42f5-9fcf-d26dcbdd56cb','mysqldump',1,'2008-08-25 00:00:00','2008-08-25 00:00:00'),('48b69c67-232c-432b-ba55-d26dcbdd56cb','TextMate Project Creation',0,'2008-08-25 00:00:00','2008-08-25 00:00:00'),('48b69c67-697c-4906-b20a-d26dcbdd56cb','Hash Generator',0,'2008-08-25 00:00:00','2008-08-25 00:00:00');
/*!40000 ALTER TABLE `commands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commands_snippets`
--

DROP TABLE IF EXISTS `commands_snippets`;
CREATE TABLE `commands_snippets` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `snippet_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `command_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commands_snippets`
--

LOCK TABLES `commands_snippets` WRITE;
/*!40000 ALTER TABLE `commands_snippets` DISABLE KEYS */;
INSERT INTO `commands_snippets` VALUES ('48b6c23d-d954-402e-a19a-0bd3cbdd56cb','48b69c67-1244-4426-950b-d26dcbdd56cb','48b69c67-dcdc-42f5-9fcf-d26dcbdd56cb'),('48b69dc9-1608-46b9-b647-d42ecbdd56cb','48b69c67-c270-409f-aaa6-d26dcbdd56cb','48b69c67-232c-432b-ba55-d26dcbdd56cb'),('48b69dc9-48d0-42e6-b6eb-d42ecbdd56cb','48b69c67-09ec-4fe4-b240-d26dcbdd56cb','48b69c67-697c-4906-b20a-d26dcbdd56cb');
/*!40000 ALTER TABLE `commands_snippets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `snippets`
--

DROP TABLE IF EXISTS `snippets`;
CREATE TABLE `snippets` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `description` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `user_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `snippets`
--

LOCK TABLES `snippets` WRITE;
/*!40000 ALTER TABLE `snippets` DISABLE KEYS */;
INSERT INTO `snippets` VALUES ('48b69c67-1244-4426-950b-d26dcbdd56cb','MySQL Dump','This snippet will make you be able to dump an entire sql file!','48b69c67-789c-4851-b01c-d26dcbdd56cb','2008-08-25 00:00:00','2008-08-28 17:20:29'),('48b69c67-c270-409f-aaa6-d26dcbdd56cb','TextMate Project Creation','This snippet will generate an entire textmate project for you based on a directory.','48b69c67-50a0-4e84-98b8-d26dcbdd56cb','2008-08-25 00:00:00','2008-08-25 00:00:00'),('48b69c67-09ec-4fe4-b240-d26dcbdd56cb','Hash Generator','This snippet will generate a random string with the specified length using the specified characters.','48b69c67-96f0-4942-826a-d26dcbdd56cb','2008-08-25 00:00:00','2008-08-25 00:00:00');
/*!40000 ALTER TABLE `snippets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `email` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `pass` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-08-28 15:30:14
