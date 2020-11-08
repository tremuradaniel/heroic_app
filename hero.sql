-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.17 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for hero_app
DROP DATABASE IF EXISTS `hero_app`;
CREATE DATABASE IF NOT EXISTS `hero_app` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `hero_app`;

-- Dumping structure for table hero_app.combatant_skills_intervals
DROP TABLE IF EXISTS `combatant_skills_intervals`;
CREATE TABLE IF NOT EXISTS `combatant_skills_intervals` (
  `combatant_type_id` int(11) unsigned NOT NULL,
  `trait_id` int(11) unsigned NOT NULL,
  `min` smallint(6) NOT NULL DEFAULT '0',
  `max` smallint(6) NOT NULL DEFAULT '0',
  KEY `combatant_type` (`combatant_type_id`),
  KEY `combatant_traits` (`trait_id`),
  CONSTRAINT `combatant_traits` FOREIGN KEY (`trait_id`) REFERENCES `combatant_traits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `combatant_type` FOREIGN KEY (`combatant_type_id`) REFERENCES `combatant_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table hero_app.combatant_skills_intervals: ~0 rows (approximately)
/*!40000 ALTER TABLE `combatant_skills_intervals` DISABLE KEYS */;
INSERT INTO `combatant_skills_intervals` (`combatant_type_id`, `trait_id`, `min`, `max`) VALUES
	(1, 1, 70, 100),
	(1, 2, 70, 80),
	(1, 3, 45, 55),
	(1, 4, 40, 50),
	(1, 5, 10, 30),
	(2, 2, 60, 90),
	(2, 3, 40, 60),
	(2, 4, 40, 60),
	(2, 5, 25, 40),
	(2, 1, 60, 90);
/*!40000 ALTER TABLE `combatant_skills_intervals` ENABLE KEYS */;

-- Dumping structure for table hero_app.combatant_traits
DROP TABLE IF EXISTS `combatant_traits`;
CREATE TABLE IF NOT EXISTS `combatant_traits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `traits` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table hero_app.combatant_traits: ~4 rows (approximately)
/*!40000 ALTER TABLE `combatant_traits` DISABLE KEYS */;
INSERT INTO `combatant_traits` (`id`, `traits`) VALUES
	(1, 'Health'),
	(2, 'Strength'),
	(3, 'Defence'),
	(4, 'Speed'),
	(5, 'Luck');
/*!40000 ALTER TABLE `combatant_traits` ENABLE KEYS */;

-- Dumping structure for table hero_app.combatant_types
DROP TABLE IF EXISTS `combatant_types`;
CREATE TABLE IF NOT EXISTS `combatant_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table hero_app.combatant_types: ~0 rows (approximately)
/*!40000 ALTER TABLE `combatant_types` DISABLE KEYS */;
INSERT INTO `combatant_types` (`id`, `type`) VALUES
	(1, 'hero'),
	(2, 'beast');
/*!40000 ALTER TABLE `combatant_types` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
