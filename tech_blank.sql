-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: tech
-- ------------------------------------------------------
-- Server version	5.5.44-0+deb7u1

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
-- Table structure for table `application`
--

DROP TABLE IF EXISTS `application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application` (
  `id` bigint(20) NOT NULL,
  `course` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(300) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `dob` date NOT NULL,
  `catagory` varchar(10) NOT NULL,
  `sex` varchar(1) NOT NULL,
  `bsc` varchar(30) NOT NULL,
  `university` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `final_marks_obtained` int(11) NOT NULL,
  `final_marks_max` int(11) NOT NULL,
  `final_year_SGPA` float NOT NULL,
  `5th_sem_marks` int(11) NOT NULL,
  `5th_sem_marks_max` int(11) NOT NULL,
  `6th_sem_marks` int(11) NOT NULL,
  `6th_sem_marks_max` int(11) NOT NULL,
  `5th_sem_SGPA` float NOT NULL,
  `6th_sem_SGPA` float NOT NULL,
  `attempt` int(11) NOT NULL,
  `locked` int(11) NOT NULL,
  `verify` int(11) NOT NULL,
  `merit1` int(11) NOT NULL,
  `selected1` int(11) NOT NULL,
  `merit2` int(11) NOT NULL,
  `selected2` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `application_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(11) NOT NULL,
  `password` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-29 21:22:24
