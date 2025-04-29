-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: Arcadia
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `animal`
--

DROP TABLE IF EXISTS `animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `animal` (
  `animal_id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) DEFAULT NULL,
  `etat` varchar(50) DEFAULT NULL,
  `race_id` int(11) DEFAULT NULL,
  `habitat_id` int(11) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`animal_id`),
  KEY `race_id` (`race_id`),
  KEY `habitat_id` (`habitat_id`),
  CONSTRAINT `animal_ibfk_1` FOREIGN KEY (`race_id`) REFERENCES `race` (`race_id`),
  CONSTRAINT `habitat_id` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`habitat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animal`
--

LOCK TABLES `animal` WRITE;
/*!40000 ALTER TABLE `animal` DISABLE KEYS */;
INSERT INTO `animal` VALUES (1,'Crocodile','Sain',1,2,'doc/photo/Jungle/crocodile.webp'),(2,'Grenouille','Sain',2,2,'doc/photo/Jungle/frog.webp'),(3,'Serpent','Sain',1,2,'doc/photo/Jungle/snake.webp'),(4,'Éléphant','Sain',3,1,'doc/photo/savane/elephant.webp'),(5,'Lion','Sain',3,1,'doc/photo/savane/lion.webp'),(6,'Zèbre','Sain',3,1,'doc/photo/savane/zebras.webp'),(7,'Crapaud','Sain',2,3,'doc/photo/marais/amphibian.webp'),(8,'Caïman','Sain',1,3,'doc/photo/marais/caimen.webp'),(9,'Hippopotame','Sain',3,3,'doc/photo/marais/hippopotamus.webp'),(12,'test','Sain',1,1,'doc/photo/savane/68073f4c0288c.jpg'),(13,'test','Sain',1,1,'doc/photo/savane/6807424ebb12d.jpg');
/*!40000 ALTER TABLE `animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consommation_nourriture`
--

DROP TABLE IF EXISTS `consommation_nourriture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consommation_nourriture` (
  `consommation_id` int(11) NOT NULL AUTO_INCREMENT,
  `animal_id` int(11) NOT NULL,
  `quantite` decimal(10,2) NOT NULL,
  `type_nourriture` varchar(255) NOT NULL,
  `date_consumption` date NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`consommation_id`),
  KEY `animal_id` (`animal_id`),
  CONSTRAINT `consommation_nourriture_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`animal_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consommation_nourriture`
--

LOCK TABLES `consommation_nourriture` WRITE;
/*!40000 ALTER TABLE `consommation_nourriture` DISABLE KEYS */;
INSERT INTO `consommation_nourriture` VALUES (1,1,5.00,'12','1212-12-12','12'),(2,9,500.00,'foin','2025-08-15','test'),(3,1,45.00,'ert','2025-03-25','test');
/*!40000 ALTER TABLE `consommation_nourriture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `habitat`
--

DROP TABLE IF EXISTS `habitat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habitat` (
  `habitat_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `commentaire_habitat` varchar(50) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`habitat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `habitat`
--

LOCK TABLES `habitat` WRITE;
/*!40000 ALTER TABLE `habitat` DISABLE KEYS */;
INSERT INTO `habitat` VALUES (1,'Savane','Grande plaine herbeuse','Présence d\'arbres isolés','doc/photo/Jungle/jungle.webp'),(2,'Jungle','Forêt dense et humide','Nombreux reptiles et amphibiens','doc/photo/marais/Marais.webp'),(3,'Marais','Zone humide','Beaucoup d\'eau stagnante et végétation aquatique','doc/photo/savane/savane.webp');
/*!40000 ALTER TABLE `habitat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `race`
--

DROP TABLE IF EXISTS `race`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `race` (
  `race_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`race_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `race`
--

LOCK TABLES `race` WRITE;
/*!40000 ALTER TABLE `race` DISABLE KEYS */;
INSERT INTO `race` VALUES (1,'Reptile'),(2,'Amphibien'),(3,'Mammifère'),(4,'Oiseau'),(6,'test');
/*!40000 ALTER TABLE `race` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rapport_veterinaire`
--

DROP TABLE IF EXISTS `rapport_veterinaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rapport_veterinaire` (
  `rapport_veterinaire_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `detail` varchar(50) DEFAULT NULL,
  `animal_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`rapport_veterinaire_id`),
  KEY `animal_id` (`animal_id`),
  KEY `fk_user_id` (`user_id`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`user_id`),
  CONSTRAINT `rapport_veterinaire_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`animal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rapport_veterinaire`
--

LOCK TABLES `rapport_veterinaire` WRITE;
/*!40000 ALTER TABLE `rapport_veterinaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `rapport_veterinaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Directeur du Zoo'),(2,'Vétérinaire'),(3,'Employé');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (9,'Restauration,','Espaces de repas pour les visiteurs, avec menus adaptés à tous les âges,'),(10,'Observation des animaux','Zones sécurisées pour observer les animaux dans leur habitat naturel'),(11,'Petit train','Transport touristique dans tout le parc avec commentaires audio'),(12,'Aire de jeux','Espace ludique pour les enfants avec activités et animations'),(14,'test05','test 05');
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateur` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(500) DEFAULT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (23,'$2y$10$tXgrRnLN7H0wIwyT4ktPveSEe2RYl91yIA.15hEDM26Jm8F/qiyL2','veterinaire01','veterinaire01@gmail.com','veterinaire01',2),(24,'$2y$10$d.5mAgxFCGl5Oo.sV0.3ye.d7MmPGiuV/81bWGs.vGbLiiYt9d/O.','employer01','employer01@gmail.com','employer01',3),(25,'$2y$10$jE2rOEj1MMDAFbZEuAG5kuiKgIMyynxUaTi8JTyIPLNTXTp5k/oqm','admin01','admin01@gmail.com','admin01',1);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-27 13:33:35
