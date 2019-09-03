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
  `user_id` int(10) unsigned NOT NULL,
  `lot_id` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `price` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_create` (`date_create`),
  KEY `price` (`price`),
  KEY `FK_bets_users` (`user_id`),
  KEY `FK_bets_lots` (`lot_id`),
  CONSTRAINT `FK_bets_lots` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`),
  CONSTRAINT `FK_bets_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.bets: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `bets` DISABLE KEYS */;
INSERT INTO `bets` (`id`, `user_id`, `lot_id`, `date_create`, `price`) VALUES
	(1, 3, 5, '2019-09-01 13:41:04', 1500),
	(2, 1, 1, '2019-09-01 13:41:04', 2500),
	(3, 4, 2, '2019-09-01 13:41:04', 3500);
/*!40000 ALTER TABLE `bets` ENABLE KEYS */;

-- Дамп структуры для таблица yeticave.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `symbol_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symbol_code` (`symbol_code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.categories: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `symbol_code`) VALUES
	(1, 'Доски и лыжи', 'boards'),
	(2, 'Крепления ', 'attachment'),
	(3, 'Ботинки', 'boots'),
	(4, 'Одежда', 'clothing'),
	(5, 'Инструменты', 'tools'),
	(6, 'Разное', 'other');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Дамп структуры для таблица yeticave.lots
CREATE TABLE IF NOT EXISTS `lots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_author` int(10) unsigned NOT NULL,
  `user_id_winner` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `img_ref` varchar(50) NOT NULL,
  `start_price` int(10) unsigned NOT NULL,
  `date_finish` date NOT NULL,
  `bet_step` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `date_create` (`date_create`),
  KEY `date_finish` (`date_finish`),
  KEY `start_price` (`start_price`),
  KEY `FK_lots_users` (`user_id_author`),
  KEY `FK_lots_users_2` (`user_id_winner`),
  KEY `FK_lots_categories` (`category_id`),
  CONSTRAINT `FK_lots_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_lots_users` FOREIGN KEY (`user_id_author`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_lots_users_2` FOREIGN KEY (`user_id_winner`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.lots: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `lots` DISABLE KEYS */;
INSERT INTO `lots` (`id`, `user_id_author`, `user_id_winner`, `category_id`, `date_create`, `name`, `description`, `img_ref`, `start_price`, `date_finish`, `bet_step`) VALUES
	(1, 1, 2, 1, '2019-08-31 13:41:04', '2014 Rossignol District Snowboard', 'lotdescript#1', 'img/lot-1.jpg', 10999, '2019-09-04', 10),
	(2, 2, 3, 1, '2019-08-31 13:41:04', 'DC Ply Mens 2016/2017 Snowboard', 'lotdescript#2', 'img/lot-2.jpg', 159999, '2019-09-06', 15),
	(3, 4, 2, 2, '2019-09-01 13:41:04', 'Крепления Union Contact Pro 2015 года размер L/XL', 'lotdescript#3', 'img/lot-3.jpg', 8000, '2019-09-03', 5),
	(4, 2, 1, 3, '2019-08-29 13:41:04', 'Ботинки для сноуборда DC Mutiny Charocal', 'lotdescript#4', 'img/lot-4.jpg', 10999, '2019-09-05', 4),
	(5, 1, 3, 4, '2019-08-27 13:41:04', 'Куртка для сноуборда DC Mutiny Charocal', 'lotdescript#5', 'img/lot-5.jpg', 7500, '2019-10-11', 15),
	(6, 3, 2, 6, '2019-09-03 13:41:04', 'Маска Oakley Canopy', 'lotdescript#6', 'img/lot-6.jpg', 5400, '2019-09-09', 20);
/*!40000 ALTER TABLE `lots` ENABLE KEYS */;

-- Дамп структуры для таблица yeticave.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registration_date` datetime NOT NULL,
  `email` char(50) NOT NULL,
  `username` char(50) NOT NULL,
  `pasword` char(50) NOT NULL,
  `contacts` char(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `registration_date` (`registration_date`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.users: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `registration_date`, `email`, `username`, `pasword`, `contacts`) VALUES
	(1, '2019-09-01 13:41:04', 'user1@mail.ru', 'user1', 'user1pass', 'Brooklyn , street'),
	(2, '2019-09-01 13:41:04', 'user2@mail.ru', 'user2', 'user2pass', 'Saratov , home'),
	(3, '2019-09-01 13:41:04', 'user3@mail.ru', 'user3', 'user3pass', 'Torino , home'),
	(4, '2019-09-01 13:41:04', 'user4@mail.ru', 'user4', 'user4pass', 'Manchester , street');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
