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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.bets: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `bets` DISABLE KEYS */;
INSERT INTO `bets` (`id`, `user_id`, `lot_id`, `date_create`, `price`) VALUES
	(26, 5, 19, '2019-09-28 14:32:45', 15220);
/*!40000 ALTER TABLE `bets` ENABLE KEYS */;

-- Дамп структуры для таблица yeticave.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `symbol_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symbol_code` (`symbol_code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
  `user_id_winner` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `img_ref` varchar(100) DEFAULT NULL,
  `start_price` int(10) unsigned NOT NULL,
  `date_finish` date NOT NULL,
  `bet_step` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date_create` (`date_create`),
  KEY `date_finish` (`date_finish`),
  KEY `start_price` (`start_price`),
  KEY `FK_lots_users` (`user_id_author`),
  KEY `FK_lots_users_2` (`user_id_winner`),
  KEY `FK_lots_categories` (`category_id`),
  FULLTEXT KEY `search_lot` (`name`,`description`),
  CONSTRAINT `FK_lots_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_lots_users` FOREIGN KEY (`user_id_author`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_lots_users_2` FOREIGN KEY (`user_id_winner`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.lots: ~13 rows (приблизительно)
/*!40000 ALTER TABLE `lots` DISABLE KEYS */;
INSERT INTO `lots` (`id`, `user_id_author`, `user_id_winner`, `category_id`, `date_create`, `name`, `description`, `img_ref`, `start_price`, `date_finish`, `bet_step`) VALUES
	(1, 5, NULL, 1, '2019-08-31 13:41:04', '2014 Rossignol District Snowboard', 'Лыжи мои любимые еду асфальт лёд зима', 'img/lot-1.jpg', 10999, '2019-09-30', 10),
	(2, 5, NULL, 1, '2019-08-31 13:41:04', 'DC Ply Mens 2016/2017 Snowboard', 'Сноуборд одна лыжа зима', 'img/lot-2.jpg', 25032, '2019-09-29', 15),
	(3, 5, NULL, 2, '2019-09-01 13:41:04', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепление не упадёшь круто ехать зима', 'img/lot-3.jpg', 8000, '2019-10-01', 5),
	(4, 5, NULL, 3, '2019-08-29 13:41:04', 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки не босиком купил сам зима', 'img/lot-4.jpg', 11014, '2019-10-01', 4),
	(5, 5, NULL, 4, '2019-08-27 13:41:04', 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка тёплая зима', 'img/lot-5.jpg', 7516, '2019-10-11', 15),
	(6, 5, NULL, 6, '2019-09-03 13:41:04', 'Маска Oakley Canopy', 'Маска крутая зима', 'img/lot-6.jpg', 5672, '2019-09-29', 20),
	(7, 5, NULL, 6, '2019-09-17 19:32:58', 'Птицекот', 'Отличный лот птицекот, лучший для зимы\r\nи тёплых дней. Зима.', '/uploads/97PtxTEHIts.jpg', 124, '2019-09-28', 3),
	(18, 6, NULL, 5, '2019-09-24 15:41:11', 'Оранжевый шлем', 'Оранжевый шлем для апельсиновой прогулки', '/uploads/images.jpg', 1000, '2019-09-29', 10),
	(19, 8, NULL, 6, '2019-09-24 15:45:21', 'Набор лыжника №1', 'Всё что нужно для комфортного отдыха и прогулки на лыжах.', '/uploads/ilustracao-de-equipamento-de-esqui_23-2147984553.jpg', 15220, '2019-09-29', 100),
	(20, 9, NULL, 5, '2019-09-24 15:48:21', 'Синих чехол', 'Прекрасный зимний синий чехол для лыж.', '/uploads/product_pict_8908936_.png', 2551, '2019-09-30', 50),
	(21, 10, NULL, 1, '2019-09-24 15:54:06', 'Супер-лыжи', 'Отличные лыжи для настоящих ценителей. Зима и солнце, прогулки на свежем воздухе.', '/uploads/1249_large-square.jpg', 7302, '2019-09-30', 150),
	(22, 8, NULL, 1, '2019-09-24 18:29:47', 'Комплект горнолыжника', 'Отличный комплект для катания на горных лыжах. Зима и лыжи.', '/uploads/1323_large-square.png', 8500, '2019-09-30', 50),
	(23, 10, NULL, 4, '2019-09-24 18:50:31', 'Чёрный шлем', 'Прочный шлем для катания на лыжах и сноуборде. Зима, солнце и свежий воздух.', '/uploads/1247_large-square.jpg', 1500, '2019-09-29', 25);
/*!40000 ALTER TABLE `lots` ENABLE KEYS */;

-- Дамп структуры для таблица yeticave.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registration_date` datetime NOT NULL,
  `email` char(50) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `contacts` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `registration_date` (`registration_date`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы yeticave.users: ~8 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `registration_date`, `email`, `username`, `password`, `contacts`) VALUES
	(5, '2019-09-12 19:16:28', 'plageat@ngs.ru', 'Евгений', '$2y$10$AmF2PZmJJ/seCag7FYusRej0dv72t3caHFEryXSe7NAc4PiPNO3xq', 'Home, forest.'),
	(6, '2019-09-17 19:30:49', 'papa@mail.ru', 'Papa', '$2y$10$eDtMc9UfnIWQHtZWvykFhu6mT9TvtMnlXQz1Q7xHkaOSIJMgxZ7HW', 'My Contacts'),
	(7, '2019-09-20 22:51:03', 'test@mail.ru', 'Pavlik', '$2y$10$xQQeJwQ8dWDw8I.071fWreWyD7hiYDqRd81BNyuHeVgs820b85Nye', 'my home-8'),
	(8, '2019-09-24 15:42:43', 'atakamba@yandex.ru', 'Atakamba', '$2y$10$cfKOD0buQuBHae4l996Fa.3ysIb/e.qBrc/haX.ppix/1dr3FQqvm', 'home, 8'),
	(9, '2019-09-24 15:46:41', 'evgenyis-84@mail.ru', 'Limberman', '$2y$10$WzplxeBqeMmT.lvzf62Waumlb2hTeJnWwcRquj8c2kNPgQTVVXyVm', 'home,9'),
	(10, '2019-09-24 15:51:40', 'sysoeva.ta@yandex.ru', 'Татьяна', '$2y$10$sKmCfyu3B/I7yKc8ZKR88.IEg6UF9CI3/InbHSMf0aMDmUw9J1B.2', 'home , 9'),
	(13, '2019-09-27 13:26:24', 'done@test.ru', 'Максим', '$2y$10$N0lJCjanQ48340PPRl5UF.UZMrq/kK9V7lo.o721FK4c/GwlsguW6', 'Мой дом');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
