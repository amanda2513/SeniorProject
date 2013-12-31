CREATE DATABASE  IF NOT EXISTS `grad_expedition` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `grad_expedition`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: grad_expedition
-- ------------------------------------------------------
-- Server version	5.5.33

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
-- Table structure for table `assigned_judges`
--

DROP TABLE IF EXISTS `assigned_judges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assigned_judges` (
  `judge_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`judge_id`,`project_id`),
  KEY `assigned_judges_project_id_idx` (`project_id`),
  CONSTRAINT `assigned_judges_judge_id_fk` FOREIGN KEY (`judge_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assigned_judges_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assigned_judges`
--

LOCK TABLES `assigned_judges` WRITE;
/*!40000 ALTER TABLE `assigned_judges` DISABLE KEYS */;
/*!40000 ALTER TABLE `assigned_judges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(45) NOT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `category_UNIQUE` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (25,'Oral Presentation'),(24,'Poster');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `criteria_scores`
--

DROP TABLE IF EXISTS `criteria_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `criteria_scores` (
  `scoring_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `judge_id` int(11) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `criteria_score` int(11) DEFAULT NULL,
  PRIMARY KEY (`scoring_id`),
  KEY `criteriascore_judgeid_fk_idx` (`judge_id`),
  KEY `criteriasore_criteriaid_fk_idx` (`criteria_id`),
  KEY `criteriascore_projectid_fk` (`project_id`),
  CONSTRAINT `criteriascore_judgeid_fk` FOREIGN KEY (`judge_id`) REFERENCES `assigned_judges` (`judge_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `criteriascore_projectid_fk` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON UPDATE CASCADE,
  CONSTRAINT `criteriasore_criteriaid_fk` FOREIGN KEY (`criteria_id`) REFERENCES `subcat_criteria` (`criteria_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `criteria_scores`
--

LOCK TABLES `criteria_scores` WRITE;
/*!40000 ALTER TABLE `criteria_scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `criteria_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participants`
--

DROP TABLE IF EXISTS `participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participants` (
  `participant_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`participant_id`),
  KEY `participants_project_id_fk_idx` (`project_id`),
  CONSTRAINT `participants_participant_id_fk` FOREIGN KEY (`participant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participants`
--

LOCK TABLES `participants` WRITE;
/*!40000 ALTER TABLE `participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(45) DEFAULT NULL,
  `abstract` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcat_criteria`
--

DROP TABLE IF EXISTS `subcat_criteria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcat_criteria` (
  `criteria_id` int(11) NOT NULL AUTO_INCREMENT,
  `criteria_description` varchar(20000) NOT NULL,
  `criteria_points` int(11) NOT NULL,
  `subcat_id` int(11) NOT NULL,
  PRIMARY KEY (`criteria_id`),
  KEY `subcat_id_idx` (`subcat_id`),
  CONSTRAINT `subcat_criteria_subcat_id_fk` FOREIGN KEY (`subcat_id`) REFERENCES `subcategories` (`subcat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcat_criteria`
--

LOCK TABLES `subcat_criteria` WRITE;
/*!40000 ALTER TABLE `subcat_criteria` DISABLE KEYS */;
INSERT INTO `subcat_criteria` VALUES (91,'Objective or reason for research stated',10,35),(92,'Significance/relevance stated',10,35),(93,'Project Design and execution explained',10,35),(94,'Interpretation of results included',10,35),(95,'Clarity of illustrations, graphics, figures, photos, etc.',10,36),(96,'Spelling, grammar, sentence structure, absence of jargon',10,36),(97,'Ability to be understood by general audience',10,36),(98,'Ability to answer questions',10,37),(99,'Effective incorporation of materials in oral presentation',10,37),(100,'Effective incorporation of materials in oral presentation',10,37),(101,'Overall Score',100,38);
/*!40000 ALTER TABLE `subcat_criteria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategories` (
  `subcat_id` int(11) NOT NULL AUTO_INCREMENT,
  `subcat_name` varchar(45) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`subcat_id`),
  KEY `cat_id_idx` (`cat_id`),
  CONSTRAINT `subcategories_cat_id_fk` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (35,'Content',24),(36,'Display',24),(37,'Oral',24),(38,'Presentation',25);
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `homepage_message` varchar(2000) DEFAULT NULL,
  `exhib_date` date DEFAULT NULL,
  `exhib_location` varchar(45) DEFAULT NULL,
  `exhib_start` varchar(45) DEFAULT NULL,
  `exhib_end` varchar(45) DEFAULT NULL,
  `reg_start_date` date DEFAULT NULL,
  `reg_cutoff_date` date DEFAULT NULL,
  `restrict_access` int(11) DEFAULT NULL,
  `judges_per_project` int(11) DEFAULT NULL,
  `projects_per_judge` int(11) DEFAULT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES (1,'Welcome! We\'re very excited about the next Graduate Exhibition. <br> Pardon us while we set up the system. Please visit us again soon!','0000-00-00',NULL,NULL,NULL,'0000-00-00','0000-00-00',0,NULL,NULL);
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `college` varchar(45) DEFAULT NULL,
  `department` varchar(45) DEFAULT NULL,
  `usertype` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'Enabled',
  PRIMARY KEY (`username`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'es2538','Laura','Moreno Cubillos','College of Engineering','Computer Science','admin','Enabled'),(1,'masteradmin','master','master','master','master','admin','Enabled');
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

-- Dump completed on 2013-12-30 22:26:21
