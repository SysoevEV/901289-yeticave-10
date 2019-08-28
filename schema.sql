-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.7.25-log - MySQL Community Server (GPL)
-- Операционная система:         Win32
-- HeidiSQL Версия:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных yeticave
CREATE DATABASE IF NOT EXISTS `yeticave` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `yeticave`;

-- Дамп структуры для таблица yeticave.bets
CREATE TABLE IF NOT EXISTS `bets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `lots_id` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `price` float unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_bets_users` (`users_id`),
  KEY `FK_bets_users_2` (`lots_id`),
  KEY `date_create` (`date_create`),
  KEY `price` (`price`),
  CONSTRAINT `FK_bets_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_bets_users_2` FOREIGN KEY (`lots_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.bets: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `bets` DISABLE KEYS */;
/*!40000 ALTER TABLE `bets` ENABLE KEYS */;

-- Дамп структуры для таблица yeticave.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` char(50) NOT NULL,
  `symbol_code` char(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_name` (`category_name`),
  UNIQUE KEY `symbol_code` (`symbol_code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.categories: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `category_name`, `symbol_code`) VALUES
	(1, 'Доски и лыжи', 'boards'),
	(2, 'Крепления', 'attachment'),
	(3, 'Ботинки', 'boots'),
	(4, 'Одежда', 'clothing'),
	(5, 'Инструменты', 'tools'),
	(6, 'Разное', 'other');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Дамп структуры для таблица yeticave.lots
CREATE TABLE IF NOT EXISTS `lots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id_author` int(10) unsigned NOT NULL,
  `users_id_winner` int(10) unsigned NOT NULL,
  `categories_id` int(10) unsigned NOT NULL,
  `date_create` date NOT NULL,
  `name` char(50) NOT NULL,
  `description` char(50) NOT NULL,
  `img_ref` char(50) NOT NULL,
  `start_price` float unsigned NOT NULL,
  `date_finish` date NOT NULL,
  `bet_step` float unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `FK_lots_users` (`users_id_author`),
  KEY `FK_lots_users_2` (`users_id_winner`),
  KEY `FK_lots_users_3` (`categories_id`),
  KEY `date_create` (`date_create`),
  KEY `date_finish` (`date_finish`),
  KEY `start_price` (`start_price`),
  CONSTRAINT `FK_lots_users` FOREIGN KEY (`users_id_author`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_lots_users_2` FOREIGN KEY (`users_id_winner`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_lots_users_3` FOREIGN KEY (`categories_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.lots: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `lots` DISABLE KEYS */;
/*!40000 ALTER TABLE `lots` ENABLE KEYS */;

-- Дамп структуры для таблица yeticave.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lots_id` int(10) unsigned NOT NULL,
  `bets_id` int(10) unsigned NOT NULL,
  `registration_date` datetime NOT NULL,
  `email` char(50) NOT NULL,
  `name` char(50) NOT NULL,
  `pasword` char(50) NOT NULL,
  `contacts` char(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `name` (`name`),
  KEY `FK_users_lots` (`lots_id`),
  KEY `FK_users_bets` (`bets_id`),
  KEY `registration_date` (`registration_date`),
  CONSTRAINT `FK_users_bets` FOREIGN KEY (`bets_id`) REFERENCES `bets` (`id`),
  CONSTRAINT `FK_users_lots` FOREIGN KEY (`lots_id`) REFERENCES `lots` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
