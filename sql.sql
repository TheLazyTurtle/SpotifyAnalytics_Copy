-- MySQL dump 10.19  Distrib 10.3.29-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: spotifyDev
-- ------------------------------------------------------
-- Server version	10.3.29-MariaDB-0ubuntu0.20.04.1

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
-- Table structure for table `filterSetting`
--

DROP TABLE IF EXISTS `filterSetting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filterSetting` (
  `graphID` int(11) NOT NULL,
  `userID` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`graphID`,`name`,`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filterSetting`
--

LOCK TABLES `filterSetting` WRITE;
/*!40000 ALTER TABLE `filterSetting` DISABLE KEYS */;
INSERT INTO `filterSetting` VALUES (1,'11182819693','maxDate','2099-01-01'),(1,'11182819693','maxPlayed','9999'),(1,'11182819693','minDate','2020-01-01'),(1,'11182819693','minPlayed','20'),(2,'11182819693','amount','10'),(2,'11182819693','artist',''),(2,'11182819693','maxDate','2099-01-01'),(2,'11182819693','minDate','2020-01-01'),(3,'11182819693','amount','10'),(3,'11182819693','maxDate','2099-01-01'),(3,'11182819693','minDate','2020-01-01'),(4,'11182819693','maxDate','2099-01-01'),(4,'11182819693','minDate','2020-01-01'),(4,'11182819693','song','');
/*!40000 ALTER TABLE `filterSetting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `graph`
--

DROP TABLE IF EXISTS `graph`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `graph` (
  `graphID` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `xValueType` varchar(45) NOT NULL,
  `api` varchar(45) NOT NULL,
  `titleX` varchar(45) NOT NULL,
  `titleY` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `containerID` varchar(45) NOT NULL,
  PRIMARY KEY (`graphID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `graph`
--

LOCK TABLES `graph` WRITE;
/*!40000 ALTER TABLE `graph` DISABLE KEYS */;
INSERT INTO `graph` VALUES (1,'All Songs Played','string','/api/song/allSongsPlayed.php','','','column','all_Songs_Played'),(2,'Top Songs','string','/api/song/topSongs.php','','','column','top_Songs'),(3,'Top Artist','string','/api/artist/topArtist.php','','','column','top_Artist'),(4,'Played Per Day','dateTime','/api/song/playedPerDay.php','','','spline','played_Per_Day');
/*!40000 ALTER TABLE `graph` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inputfield`
--

DROP TABLE IF EXISTS `inputfield`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inputfield` (
  `graphID` int(11) NOT NULL,
  `inputFieldID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`graphID`,`inputFieldID`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inputfield`
--

LOCK TABLES `inputfield` WRITE;
/*!40000 ALTER TABLE `inputfield` DISABLE KEYS */;
INSERT INTO `inputfield` VALUES (1,1,'minPlayed','Minimaal afgespeeld','number'),(1,2,'maxPlayed','Maximaal afgespeeld','number'),(2,3,'artist','Artiest','text'),(2,4,'amount','Top Hoeveel','number'),(3,5,'amount','Top Hoeveel','number'),(4,6,'song','Nummer naam','text');
/*!40000 ALTER TABLE `inputfield` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spotifyData`
--

DROP TABLE IF EXISTS `spotifyData`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spotifyData` (
  `userID` int(11) NOT NULL,
  `authToken` varchar(255) NOT NULL,
  `refreshToken` varchar(255) DEFAULT NULL,
  `ExpireDate` int(11) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spotifyData`
--

LOCK TABLES `spotifyData` WRITE;
/*!40000 ALTER TABLE `spotifyData` DISABLE KEYS */;
/*!40000 ALTER TABLE `spotifyData` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `userID` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(80) DEFAULT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT current_timestamp(),
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('11182819693','The Lazy Turtle','test','test','test@test.com','$2y$10$idFaNQ51tWkgvvKv5zkYOOe8jbybiij057.7jXa1OKflt6z0KSbR.',1,'2021-06-15 10:56:46','2021-06-15 08:56:46',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-17  9:38:34
