-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 02, 2023 at 04:30 PM
-- Server version: 10.6.5-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clean`
--

-- --------------------------------------------------------

--
-- Table structure for table `clean`
--

DROP TABLE IF EXISTS `clean`;
CREATE TABLE IF NOT EXISTS `clean` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `scheduled` datetime DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `inspection_notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplies_claimed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F1B0AD49549213EC` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clean`
--

INSERT INTO `clean` (`id`, `property_id`, `scheduled`, `start`, `end`, `notes`, `created`, `modified`, `inspection_notes`, `supplies_claimed`) VALUES
(1, 1, '2023-06-23 08:00:00', NULL, NULL, 'First appointment!', NULL, NULL, NULL, NULL),
(2, 1, '2023-06-30 08:00:00', NULL, NULL, 'Test', '2023-06-20 14:57:17', '2023-06-21 14:32:19', NULL, NULL),
(3, 1, '2023-06-21 17:00:00', '2023-06-21 17:00:00', '2023-06-21 18:30:00', 'Last minute clean needed.', '2023-06-21 14:56:27', '2023-06-26 14:48:39', 'Forgot to put new roll of toilet paper.', NULL),
(4, 2, '2023-06-23 09:00:00', NULL, NULL, NULL, '2023-06-21 16:32:23', '2023-06-21 16:32:23', NULL, NULL),
(5, 3, '2023-06-23 10:00:00', NULL, NULL, NULL, '2023-06-21 16:32:44', '2023-06-21 16:32:44', NULL, NULL),
(6, 2, '2023-06-30 10:30:00', NULL, NULL, NULL, '2023-06-21 16:33:15', '2023-06-21 16:33:15', NULL, NULL),
(8, 2, '2023-06-23 17:00:00', NULL, NULL, NULL, '2023-06-21 16:34:28', '2023-06-21 16:34:28', NULL, NULL),
(9, 2, '2023-07-07 08:00:00', '2023-06-29 20:11:59', '2023-06-29 20:12:57', 'Here are some notes about cleaning this property. Make sure to scrub the toilets well. Stay out of the hot tub. Owner has security cameras on premise.', '2023-06-21 16:36:41', '2023-06-29 16:12:57', NULL, '2023-06-29 20:12:57'),
(11, 1, '2023-07-07 10:00:00', NULL, NULL, NULL, '2023-06-21 16:37:16', '2023-06-21 16:37:16', NULL, NULL);

--
-- Triggers `clean`
--
DROP TRIGGER IF EXISTS `clean_log_insert`;
DELIMITER $$
CREATE TRIGGER `clean_log_insert` BEFORE INSERT ON `clean` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `clean_log_update`;
DELIMITER $$
CREATE TRIGGER `clean_log_update` BEFORE UPDATE ON `clean` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `clean_housekeeper`
--

DROP TABLE IF EXISTS `clean_housekeeper`;
CREATE TABLE IF NOT EXISTS `clean_housekeeper` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clean_id` int(11) DEFAULT NULL,
  `housekeeper_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EB9599C0C9C202A6` (`clean_id`),
  KEY `IDX_EB9599C0362EB9BC` (`housekeeper_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clean_housekeeper`
--

INSERT INTO `clean_housekeeper` (`id`, `clean_id`, `housekeeper_id`, `created`, `modified`) VALUES
(1, 2, 1, '2023-06-20 14:57:17', '2023-06-20 14:57:17'),
(2, 3, 1, '2023-06-21 14:56:27', '2023-06-21 14:56:27'),
(3, 4, 4, '2023-06-21 16:32:23', '2023-06-21 16:32:23'),
(4, 4, 2, '2023-06-21 16:32:23', '2023-06-21 16:32:23'),
(5, 5, 3, '2023-06-21 16:32:44', '2023-06-21 16:32:44'),
(6, 5, 1, '2023-06-21 16:32:44', '2023-06-21 16:32:44'),
(7, 6, 2, '2023-06-21 16:33:15', '2023-06-21 16:33:15'),
(8, 6, 3, '2023-06-21 16:33:15', '2023-06-21 16:33:15'),
(11, 8, 4, '2023-06-21 16:34:28', '2023-06-21 16:34:28'),
(12, 8, 3, '2023-06-21 16:34:28', '2023-06-21 16:34:28'),
(13, 9, 4, '2023-06-21 16:36:41', '2023-06-21 16:36:41'),
(14, 9, 2, '2023-06-21 16:36:41', '2023-06-21 16:36:41'),
(17, 11, 2, '2023-06-21 16:37:16', '2023-06-21 16:37:16'),
(18, 11, 3, '2023-06-21 16:37:16', '2023-06-21 16:37:16');

--
-- Triggers `clean_housekeeper`
--
DROP TRIGGER IF EXISTS `clean_housekeeper_log_insert`;
DELIMITER $$
CREATE TRIGGER `clean_housekeeper_log_insert` BEFORE INSERT ON `clean_housekeeper` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `clean_housekeeper_log_update`;
DELIMITER $$
CREATE TRIGGER `clean_housekeeper_log_update` BEFORE UPDATE ON `clean_housekeeper` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `clean_linen`
--

DROP TABLE IF EXISTS `clean_linen`;
CREATE TABLE IF NOT EXISTS `clean_linen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clean_id` int(11) DEFAULT NULL,
  `linen_id` int(11) DEFAULT NULL,
  `units` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AFAB5B2DC9C202A6` (`clean_id`),
  KEY `IDX_AFAB5B2DDD9D69AE` (`linen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clean_linen`
--

INSERT INTO `clean_linen` (`id`, `clean_id`, `linen_id`, `units`, `created`, `modified`) VALUES
(1, 2, 5, 2, '2023-06-20 16:17:06', '2023-06-20 16:17:06'),
(2, 2, 6, 3, '2023-06-20 16:32:54', '2023-06-20 16:32:54'),
(3, 2, 5, 2, '2023-06-21 14:15:17', '2023-06-21 14:15:17'),
(4, 2, 5, 2, '2023-06-21 14:15:47', '2023-06-21 14:15:47'),
(5, 3, 5, 3, '2023-06-26 14:49:03', '2023-06-26 14:49:03');

--
-- Triggers `clean_linen`
--
DROP TRIGGER IF EXISTS `clean_linen_log_insert`;
DELIMITER $$
CREATE TRIGGER `clean_linen_log_insert` BEFORE INSERT ON `clean_linen` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `clean_linen_log_update`;
DELIMITER $$
CREATE TRIGGER `clean_linen_log_update` BEFORE UPDATE ON `clean_linen` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `clean_photo`
--

DROP TABLE IF EXISTS `clean_photo`;
CREATE TABLE IF NOT EXISTS `clean_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clean_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_971B3F7AC9C202A6` (`clean_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clean_photo`
--

INSERT INTO `clean_photo` (`id`, `clean_id`, `title`, `url`, `created`, `modified`) VALUES
(1, 1, 'Luna', '/uploads/images/rottie-6491ef3d1f717.jpg', '2023-06-20 14:26:05', '2023-06-20 14:26:05'),
(2, 1, 'Luna', '/uploads/images/rottie-6491ef61178c3.jpg', '2023-06-20 14:26:41', '2023-06-20 14:26:41'),
(3, 2, 'Luna', '/uploads/images/rottie-64931e920350e.jpg', '2023-06-21 12:00:18', '2023-06-21 12:00:18'),
(5, 3, 'Front Room', '/uploads/images/AdobeStock-177114025-1080x675-6499de8d45e5e.jpg', '2023-06-26 14:53:01', '2023-06-26 14:53:01'),
(6, 3, 'Office', '/uploads/images/apartment-6499dea7b45d2.jpg', '2023-06-26 14:53:27', '2023-06-26 14:53:27'),
(7, 3, 'Living Room', '/uploads/images/at-house-tours-stock-archive-2e3210d61011254a4aae43df9f239c6ab2db6292-6499deb3653f6.jpg', '2023-06-26 14:53:39', '2023-06-26 14:53:39'),
(8, 3, 'Bed Room', '/uploads/images/FiveTipsforKeepingyourApartmentCleanandOrganized-6499dec5d9fd0.jpg', '2023-06-26 14:53:57', '2023-06-26 14:53:57'),
(16, 9, 'Living Room', '/uploads/images/AdobeStock-177114025-1080x675-649de5a592de2.jpg', '2023-06-29 16:12:21', '2023-06-29 16:12:21'),
(17, 9, 'Bathroom', '/uploads/images/apartment-649de5b422e6e.jpg', '2023-06-29 16:12:36', '2023-06-29 16:12:36'),
(18, 9, 'Bedroom', '/uploads/images/apartment-for-rent-in-boston-ma-052917-feature-649de5be9aa81.jpg', '2023-06-29 16:12:46', '2023-06-29 16:12:46');

--
-- Triggers `clean_photo`
--
DROP TRIGGER IF EXISTS `clean_photo_log_insert`;
DELIMITER $$
CREATE TRIGGER `clean_photo_log_insert` BEFORE INSERT ON `clean_photo` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `clean_photo_log_update`;
DELIMITER $$
CREATE TRIGGER `clean_photo_log_update` BEFORE UPDATE ON `clean_photo` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `clean_supply`
--

DROP TABLE IF EXISTS `clean_supply`;
CREATE TABLE IF NOT EXISTS `clean_supply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clean_id` int(11) DEFAULT NULL,
  `supply_id` int(11) DEFAULT NULL,
  `units` double DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_71263843C9C202A6` (`clean_id`),
  KEY `IDX_71263843FF28C0D8` (`supply_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clean_supply`
--

INSERT INTO `clean_supply` (`id`, `clean_id`, `supply_id`, `units`, `created`, `modified`) VALUES
(1, 2, 3, 2, '2023-06-20 16:32:23', '2023-06-20 16:32:23'),
(2, 2, 4, 1, '2023-06-21 14:19:40', '2023-06-21 14:19:40'),
(3, 3, 4, 1, '2023-06-26 14:49:22', '2023-06-26 14:49:22'),
(4, 3, 2, 1, '2023-06-26 14:49:39', '2023-06-26 14:49:39');

--
-- Triggers `clean_supply`
--
DROP TRIGGER IF EXISTS `clean_supply_log_insert`;
DELIMITER $$
CREATE TRIGGER `clean_supply_log_insert` BEFORE INSERT ON `clean_supply` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `clean_supply_log_update`;
DELIMITER $$
CREATE TRIGGER `clean_supply_log_update` BEFORE UPDATE ON `clean_supply` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postalcode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `calendar_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_81398E09A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `user_id`, `first_name`, `last_name`, `company`, `address`, `city`, `state`, `postalcode`, `created`, `modified`, `calendar_url`, `active`) VALUES
(2, 1, 'Scott', 'Reynolds', 'Sherlock Homes', '305 Chester St', 'Myrtle Beach', 'SC', '29577', NULL, '2023-06-27 10:57:32', NULL, 1),
(3, 6, 'Person', 'Tester', 'Persons Houses', '432 That St', 'Myrtle Beach', 'SC', '29577', '2023-06-21 16:25:51', '2023-06-27 10:57:42', NULL, 1),
(5, 5, 'Customer', 'Homes', 'Customer Homes', '653 Some Rd', 'Myrtle Beach', 'SC', '29577', '2023-06-21 16:27:33', '2023-06-27 11:22:37', NULL, 1);

--
-- Triggers `customer`
--
DROP TRIGGER IF EXISTS `customer_log_insert`;
DELIMITER $$
CREATE TRIGGER `customer_log_insert` BEFORE INSERT ON `customer` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `customer_log_update`;
DELIMITER $$
CREATE TRIGGER `customer_log_update` BEFORE UPDATE ON `customer` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230614195159', '2023-06-14 19:52:10', 31),
('DoctrineMigrations\\Version20230615144140', '2023-06-15 14:41:58', 16),
('DoctrineMigrations\\Version20230615155505', '2023-06-15 15:55:18', 69),
('DoctrineMigrations\\Version20230615210058', '2023-06-15 21:01:07', 49),
('DoctrineMigrations\\Version20230616135214', '2023-06-16 13:52:41', 43),
('DoctrineMigrations\\Version20230616184418', '2023-06-16 18:44:29', 16),
('DoctrineMigrations\\Version20230616184652', '2023-06-16 18:47:04', 17),
('DoctrineMigrations\\Version20230619183321', '2023-06-19 18:52:28', 70),
('DoctrineMigrations\\Version20230619210418', '2023-06-19 21:04:29', 44),
('DoctrineMigrations\\Version20230619211120', '2023-06-19 21:11:29', 42),
('DoctrineMigrations\\Version20230619211510', '2023-06-19 21:15:23', 73),
('DoctrineMigrations\\Version20230619212154', '2023-06-19 21:22:05', 149),
('DoctrineMigrations\\Version20230620173439', '2023-06-20 17:34:48', 31),
('DoctrineMigrations\\Version20230620173603', '2023-06-20 17:53:24', 325),
('DoctrineMigrations\\Version20230622155128', '2023-06-22 15:51:37', 20),
('DoctrineMigrations\\Version20230622161021', '2023-06-22 16:10:31', 17),
('DoctrineMigrations\\Version20230623180326', '2023-06-23 18:03:35', 18),
('DoctrineMigrations\\Version20230623190327', '2023-06-23 19:03:36', 18),
('DoctrineMigrations\\Version20230623202009', '2023-06-23 20:20:30', 17),
('DoctrineMigrations\\Version20230626200933', '2023-06-26 20:09:46', 21),
('DoctrineMigrations\\Version20230626204138', '2023-06-26 20:41:47', 18),
('DoctrineMigrations\\Version20230627145705', '2023-06-27 14:57:13', 19),
('DoctrineMigrations\\Version20230628210455', '2023-06-28 21:05:04', 17);

-- --------------------------------------------------------

--
-- Table structure for table `housekeeper`
--

DROP TABLE IF EXISTS `housekeeper`;
CREATE TABLE IF NOT EXISTS `housekeeper` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postalcode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `i_nine_front` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `i_nine_back` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_front` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_back` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2D700DF0A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `housekeeper`
--

INSERT INTO `housekeeper` (`id`, `first_name`, `last_name`, `address`, `city`, `state`, `postalcode`, `created`, `modified`, `employee_id`, `user_id`, `i_nine_front`, `i_nine_back`, `id_front`, `id_back`, `active`) VALUES
(1, 'Scott', 'Reynolds', '305 Chester St', 'Myrtle Beach', 'SC', '29577', NULL, '2023-06-26 16:42:00', '123456', 1, NULL, NULL, NULL, NULL, 1),
(2, 'House', 'Keeper', '555 Some Rd', 'Myrtle Beach', 'SC', '29577', '2023-06-21 16:14:59', '2023-06-26 16:42:02', '111111', 4, NULL, NULL, NULL, NULL, 1),
(3, 'Some', 'One', '123 That Rd', 'Myrtle Beach', 'SC', '29577', '2023-06-21 16:15:31', '2023-06-26 16:42:04', '222222', 3, NULL, NULL, NULL, NULL, 1),
(4, 'Test', 'Housekeeper', '333 Over There St', 'Myrtle Beach', 'SC', '29577', '2023-06-21 16:16:09', '2023-06-26 16:42:08', '333333', 2, NULL, NULL, NULL, NULL, 0);

--
-- Triggers `housekeeper`
--
DROP TRIGGER IF EXISTS `housekeeper_log_insert`;
DELIMITER $$
CREATE TRIGGER `housekeeper_log_insert` BEFORE INSERT ON `housekeeper` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `housekeeper_log_update`;
DELIMITER $$
CREATE TRIGGER `housekeeper_log_update` BEFORE UPDATE ON `housekeeper` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `linen`
--

DROP TABLE IF EXISTS `linen`;
CREATE TABLE IF NOT EXISTS `linen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `units` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `linen`
--

INSERT INTO `linen` (`id`, `name`, `units`, `created`, `modified`) VALUES
(1, 'King Size Sheets', 10, NULL, NULL),
(2, 'Queen Size Sheets', 15, NULL, NULL),
(3, 'Twin Size Sheets', 20, NULL, NULL),
(4, 'Washcloths', 10, NULL, NULL),
(5, 'Bath Towels', 3, NULL, '2023-06-26 14:49:03'),
(6, 'Hand Towels', 10, NULL, NULL),
(7, 'Kitchen Towels', 0, NULL, '2023-06-21 13:55:26');

--
-- Triggers `linen`
--
DROP TRIGGER IF EXISTS `linen_log_insert`;
DELIMITER $$
CREATE TRIGGER `linen_log_insert` BEFORE INSERT ON `linen` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `linen_log_update`;
DELIMITER $$
CREATE TRIGGER `linen_log_update` BEFORE UPDATE ON `linen` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

DROP TABLE IF EXISTS `property`;
CREATE TABLE IF NOT EXISTS `property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postalcode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `square_feet` int(11) DEFAULT NULL,
  `kings` int(11) DEFAULT NULL,
  `queens` int(11) DEFAULT NULL,
  `twins` int(11) DEFAULT NULL,
  `towels` int(11) DEFAULT NULL,
  `hand_towels` int(11) DEFAULT NULL,
  `wash_cloths` int(11) DEFAULT NULL,
  `instructions` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `door_code` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `cost` double DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8BF21CDE9395C3F3` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`id`, `customer_id`, `title`, `address`, `city`, `state`, `postalcode`, `latitude`, `longitude`, `bedrooms`, `bathrooms`, `square_feet`, `kings`, `queens`, `twins`, `towels`, `hand_towels`, `wash_cloths`, `instructions`, `door_code`, `created`, `modified`, `cost`, `active`) VALUES
(1, 2, 'Big House', '305 Chester', 'Myrtle Beach', 'SC', '29577', '33.6876061', '-78.8893106', 4, 3, 2500, 0, 6, 2, 20, 10, 10, 'Check in at gate for day pass.', '6969', NULL, '2023-06-27 11:24:31', 125, 1),
(2, 3, 'Beach Bungalo', '307 Chester St', 'Myrtle Beach', 'SC', '29577', '33.6876882', '-78.8895639', 3, 1, 1500, 0, 2, 2, 10, 5, 5, 'Stay out of the hot tub.', '1234', '2023-06-21 16:28:57', '2023-06-27 11:43:19', 75, 1),
(3, 3, 'Beach Getaway', '6545 Ocean Blvd', 'Myrtle Beach', 'SC', '29577', '33.694004', '-78.8870027', 3, 1, 2000, 1, 2, 2, 7, 4, 3, 'Go in through Back door', '3453', '2023-06-21 16:30:34', '2023-06-27 11:43:46', NULL, 1);

--
-- Triggers `property`
--
DROP TRIGGER IF EXISTS `property_log_insert`;
DELIMITER $$
CREATE TRIGGER `property_log_insert` BEFORE INSERT ON `property` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `property_log_update`;
DELIMITER $$
CREATE TRIGGER `property_log_update` BEFORE UPDATE ON `property` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `property_photo`
--

DROP TABLE IF EXISTS `property_photo`;
CREATE TABLE IF NOT EXISTS `property_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D2A44515549213EC` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_photo`
--

INSERT INTO `property_photo` (`id`, `property_id`, `title`, `url`, `created`, `modified`) VALUES
(2, 1, 'Luna', '/uploads/images/rottie-6490bb05e0f25.jpg', NULL, NULL);

--
-- Triggers `property_photo`
--
DROP TRIGGER IF EXISTS `property_photo_log_insert`;
DELIMITER $$
CREATE TRIGGER `property_photo_log_insert` BEFORE INSERT ON `property_photo` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `property_photo_log_update`;
DELIMITER $$
CREATE TRIGGER `property_photo_log_update` BEFORE UPDATE ON `property_photo` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `supply`
--

DROP TABLE IF EXISTS `supply`;
CREATE TABLE IF NOT EXISTS `supply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `units` double DEFAULT NULL,
  `units_measurement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supply`
--

INSERT INTO `supply` (`id`, `name`, `units`, `units_measurement`, `created`, `modified`) VALUES
(1, 'Window Cleaner', 10, 'gallons', NULL, NULL),
(2, 'Pin Sol', 2, 'gallons', NULL, '2023-06-26 14:49:39'),
(3, 'Pledge', 10, 'cans', NULL, NULL),
(4, 'Bleach', 18, 'gallons', '2023-06-20 16:33:49', '2023-06-26 14:49:22'),
(5, 'Toilet Paper', 500, 'rolls', '2023-06-21 12:01:02', '2023-06-21 12:01:50'),
(6, 'Paper Towels', 100, 'rolls', '2023-06-21 12:01:31', '2023-06-21 12:01:45');

--
-- Triggers `supply`
--
DROP TRIGGER IF EXISTS `supply_log_insert`;
DELIMITER $$
CREATE TRIGGER `supply_log_insert` BEFORE INSERT ON `supply` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `supply_log_update`;
DELIMITER $$
CREATE TRIGGER `supply_log_update` BEFORE UPDATE ON `supply` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `created`, `modified`) VALUES
(1, 'scott@surfsideweb.com', '[\"ROLE_USER\",\"ROLE_ADMIN\"]', '$2y$13$u3fSH0zz0ku8dUOgNCZ.7.Ckcser06b./prxJq3WB8BurR5WFp9Wq', NULL, NULL),
(2, 'test@notarealaddress.com', '[\"ROLE_HOUSEKEEPER\"]', '$2y$13$ABy6mqaPlPzbsi7ZhdTGDevQHFCBtJP6izdDeO6FvrO57qpFnJLam', '2023-06-21 16:12:01', '2023-06-26 14:43:51'),
(3, 'someone@notarealaddress.com', '[\"ROLE_HOUSEKEEPER\"]', '$2y$10$3DPoOsjYbVgV8Xnepyq39eahwpLp5B.kN8RuZy3jcFA8Rgo/ydky.', '2023-06-21 16:12:35', '2023-06-21 16:22:21'),
(4, 'housekeeper@notarealaddress.com', '[\"ROLE_HOUSEKEEPER\"]', '$2y$10$knG3YO1aarQfyGHrFUdo..nw7n2t8yKJPUsm5ao6iMZqKh86Eu2y.', '2023-06-21 16:13:04', '2023-06-21 16:22:02'),
(5, 'customer@notarealaddress.com', '[\"ROLE_CUSTOMER\"]', '$2y$10$VdNnhrQYcMWaawiHLuXzm.DlAjMt5haxZRf2mIYkWbYyTSmWHhAly', '2023-06-21 16:24:33', '2023-06-21 16:24:33'),
(6, 'person@notarealaddress.com', '[\"ROLE_CUSTOMER\"]', '$2y$10$pfMBBdi5SGJJYVFzF8hnoegxH89fBw.lXniuSh3smSe49Gdl0HBWe', '2023-06-21 16:25:08', '2023-06-21 16:25:08');

--
-- Triggers `user`
--
DROP TRIGGER IF EXISTS `user_log_insert`;
DELIMITER $$
CREATE TRIGGER `user_log_insert` BEFORE INSERT ON `user` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `user_log_update`;
DELIMITER $$
CREATE TRIGGER `user_log_update` BEFORE UPDATE ON `user` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END
$$
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clean`
--
ALTER TABLE `clean`
  ADD CONSTRAINT `FK_F1B0AD49549213EC` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`);

--
-- Constraints for table `clean_housekeeper`
--
ALTER TABLE `clean_housekeeper`
  ADD CONSTRAINT `FK_EB9599C0362EB9BC` FOREIGN KEY (`housekeeper_id`) REFERENCES `housekeeper` (`id`),
  ADD CONSTRAINT `FK_EB9599C0C9C202A6` FOREIGN KEY (`clean_id`) REFERENCES `clean` (`id`);

--
-- Constraints for table `clean_linen`
--
ALTER TABLE `clean_linen`
  ADD CONSTRAINT `FK_AFAB5B2DC9C202A6` FOREIGN KEY (`clean_id`) REFERENCES `clean` (`id`),
  ADD CONSTRAINT `FK_AFAB5B2DDD9D69AE` FOREIGN KEY (`linen_id`) REFERENCES `linen` (`id`);

--
-- Constraints for table `clean_photo`
--
ALTER TABLE `clean_photo`
  ADD CONSTRAINT `FK_971B3F7AC9C202A6` FOREIGN KEY (`clean_id`) REFERENCES `clean` (`id`);

--
-- Constraints for table `clean_supply`
--
ALTER TABLE `clean_supply`
  ADD CONSTRAINT `FK_71263843C9C202A6` FOREIGN KEY (`clean_id`) REFERENCES `clean` (`id`),
  ADD CONSTRAINT `FK_71263843FF28C0D8` FOREIGN KEY (`supply_id`) REFERENCES `supply` (`id`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `FK_81398E09A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `housekeeper`
--
ALTER TABLE `housekeeper`
  ADD CONSTRAINT `FK_2D700DF0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `FK_8BF21CDE9395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `property_photo`
--
ALTER TABLE `property_photo`
  ADD CONSTRAINT `FK_D2A44515549213EC` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
