-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: companyprofile
-- ------------------------------------------------------
-- Server version	8.0.46

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
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Nexora Product Launch','nexora-product-launch','Introducing Nexora’s latest digital products.','A launch event showcasing new digital solutions.','events/launch.webp','Jakarta',NULL,NULL,'2026-10-22 19:30:50','2026-10-23 19:30:50',1,'2026-10-12 19:30:50','2026-10-12 19:30:50'),(2,'UI/UX Design Workshop','uiux-design-workshop','Hands-on UI/UX workshop.','Learn practical UI/UX design techniques.','events/uiux.webp','Bandung',NULL,NULL,'2026-10-27 19:30:50','2026-10-28 19:30:50',1,'2026-10-12 19:30:50','2026-10-12 19:30:50'),(3,'Web Development Bootcamp','web-development-bootcamp','Intensive web development training.','Build real-world web applications.','events/develop.webp','Yogyakarta',NULL,NULL,'2026-10-30 19:30:50','2026-11-01 19:30:50',1,'2026-10-12 19:30:50','2026-10-12 19:30:50'),(4,'Startup Digital Strategy Meetup','startup-digital-strategy-meetup','Strategy discussion for startups.','Networking and strategy sharing.','events/startup.webp','Surabaya',NULL,NULL,'2026-11-03 19:30:50','2026-11-03 19:30:50',1,'2026-10-12 19:30:50','2026-10-12 19:30:50'),(5,'Modern Web Technology Seminar','modern-web-technology-seminar','Exploring modern web technologies.','Talks on latest web trends.','events/modern.webp','Bali',NULL,NULL,'2026-11-06 19:30:50','2026-11-07 19:30:50',1,'2026-10-12 19:30:50','2026-10-12 19:30:50'),(6,'Design System Masterclass','design-system-masterclass','Advanced design system workshop.','Creating scalable design systems.','events/design.webp','Online',NULL,NULL,'2026-11-09 19:30:50','2026-11-09 19:30:50',1,'2026-10-12 19:30:50','2026-10-12 19:30:50'),(7,'Scalable App Architecture Talk','scalable-app-architecture-talk','Discussion on scalable architecture.','Best practices for scaling apps.','events/scalable.webp','Online',NULL,NULL,'2026-11-11 19:30:50','2026-11-11 19:30:50',1,'2026-10-12 19:30:50','2026-10-12 19:30:50'),(8,'Digital Business Conference','digital-business-conference','Annual digital business conference.','Industry leaders share insights.','events/digital.webp','Jakarta',NULL,NULL,'2026-11-16 19:30:50','2026-11-17 19:30:50',1,'2026-10-12 19:30:50','2026-10-12 19:30:50');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-13 12:16:10
