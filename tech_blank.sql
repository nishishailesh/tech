-- MariaDB dump 10.19  Distrib 10.5.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: tech
-- ------------------------------------------------------
-- Server version	10.5.11-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
  `id` varchar(200) NOT NULL,
  `course` enum('Lab Tech','XRay Tech','Both') DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(200) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `catagory` enum('OPEN','SEBC','SC','ST','EWS') DEFAULT NULL,
  `physically_handicapped` enum('N','Y') DEFAULT NULL,
  `sex` enum('M','F','O') DEFAULT NULL,
  `bsc` enum('Biochemistry','Chemistry','Biotechnology','Microbiology','Physics','Other') DEFAULT NULL,
  `university` varchar(100) DEFAULT NULL,
  `final_year_marks_obtained` int(11) DEFAULT NULL,
  `final_year_marks_max` int(11) DEFAULT NULL,
  `final_year_SGPA` float DEFAULT NULL,
  `5th_sem_marks_obtained` int(11) DEFAULT NULL,
  `5th_sem_marks_max` int(11) DEFAULT NULL,
  `6th_sem_marks_obtained` int(11) DEFAULT NULL,
  `6th_sem_marks_max` int(11) DEFAULT NULL,
  `5th_sem_SGPA` float DEFAULT NULL,
  `6th_sem_SGPA` float DEFAULT NULL,
  `_photo_id_proof` mediumblob DEFAULT NULL,
  `_photo_id_proof_name` varchar(60) DEFAULT NULL,
  `_date_of_birth_proof` mediumblob DEFAULT NULL,
  `_date_of_birth_proof_name` varchar(60) DEFAULT NULL,
  `_bsc_mark_or_grade_1` mediumblob DEFAULT NULL,
  `_bsc_mark_or_grade_1_name` varchar(60) DEFAULT NULL,
  `_bsc_mark_or_grade_2` mediumblob DEFAULT NULL,
  `_bsc_mark_or_grade_2_name` varchar(60) DEFAULT NULL,
  `_category_certificate` mediumblob DEFAULT NULL,
  `_category_certificate_name` varchar(60) DEFAULT NULL,
  `_non_creamy_layer` mediumblob DEFAULT NULL,
  `_non_creamy_layer_name` varchar(60) DEFAULT NULL,
  `_physically_handicapped_cert` mediumblob DEFAULT NULL,
  `_physically_handicapped_cert_name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `copy_application`
--

DROP TABLE IF EXISTS `copy_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `copy_application` (
  `id` bigint(20) NOT NULL,
  `course` enum('Lab Tech','XRay Tech','Both') DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` bigint(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `catagory` enum('OPEN','SEBC','SC','ST','EWS') DEFAULT NULL,
  `physically_handicapped` enum('N','Y') DEFAULT NULL,
  `sex` enum('M','F','O') DEFAULT NULL,
  `bsc` enum('Biochemistry','Chemistry','Biotechnology','Microbiology','Physics','Other') DEFAULT NULL,
  `university` varchar(100) DEFAULT NULL,
  `final_year_marks_obtained` int(11) DEFAULT NULL,
  `final_year_marks_max` int(11) DEFAULT NULL,
  `final_year_SGPA` float DEFAULT NULL,
  `5th_sem_marks_obtained` int(11) DEFAULT NULL,
  `5th_sem_marks_max` int(11) DEFAULT NULL,
  `6th_sem_marks_obtained` int(11) DEFAULT NULL,
  `6th_sem_marks_max` int(11) DEFAULT NULL,
  `5th_sem_SGPA` float DEFAULT NULL,
  `6th_sem_SGPA` float DEFAULT NULL,
  `_photo_id_proof` mediumblob DEFAULT NULL,
  `_photo_id_proof_name` varchar(60) DEFAULT NULL,
  `_date_of_birth_proof` mediumblob DEFAULT NULL,
  `_date_of_birth_proof_name` varchar(60) DEFAULT NULL,
  `_bsc_mark_or_grade_1` mediumblob DEFAULT NULL,
  `_bsc_mark_or_grade_1_name` varchar(60) DEFAULT NULL,
  `_bsc_mark_or_grade_2` mediumblob DEFAULT NULL,
  `_bsc_mark_or_grade_2_name` varchar(60) DEFAULT NULL,
  `_category_certificate` mediumblob DEFAULT NULL,
  `_category_certificate_name` varchar(60) DEFAULT NULL,
  `_non_creamy_layer` mediumblob DEFAULT NULL,
  `_non_creamy_layer_name` varchar(60) DEFAULT NULL,
  `_physically_handicapped_cert` mediumblob DEFAULT NULL,
  `_physically_handicapped_cert_name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labcode`
--

DROP TABLE IF EXISTS `labcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labcode` (
  `bsc` varchar(50) NOT NULL,
  `code` int(11) NOT NULL,
  PRIMARY KEY (`bsc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qr`
--

DROP TABLE IF EXISTS `qr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qr` (
  `id` varchar(100) NOT NULL,
  `sql` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `record_tables`
--

DROP TABLE IF EXISTS `record_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `record_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `recording_time` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `recorded_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `table_field_specification`
--

DROP TABLE IF EXISTS `table_field_specification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_field_specification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tname` varchar(100) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `ftype` varchar(50) DEFAULT NULL,
  `table` varchar(50) DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tname_fname` (`tname`,`fname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` varchar(200) NOT NULL,
  `password` varchar(300) DEFAULT NULL,
  `expirydate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `verification`
--

DROP TABLE IF EXISTS `verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verification` (
  `id` varchar(200) NOT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `remark` varchar(300) DEFAULT NULL,
  `recording_time` datetime DEFAULT NULL,
  `recorded_by` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `verifier`
--

DROP TABLE IF EXISTS `verifier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verifier` (
  `id` bigint(11) NOT NULL,
  `password` varchar(300) NOT NULL,
  `expirydate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xraycode`
--

DROP TABLE IF EXISTS `xraycode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xraycode` (
  `bsc` varchar(50) NOT NULL,
  `code` int(11) NOT NULL,
  PRIMARY KEY (`bsc`)
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

-- Dump completed on 2022-07-15 18:43:11
