-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Апр 14 2026 г., 00:06
-- Версия сервера: 8.4.6
-- Версия PHP: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `store`
--

-- --------------------------------------------------------

--
-- Структура таблицы `attributes`
--

CREATE TABLE `attributes` (
  `id_attributes` int NOT NULL,
  `properties_id_attributes` int NOT NULL,
  `value_attributes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `attributes`
--

INSERT INTO `attributes` (`id_attributes`, `properties_id_attributes`, `value_attributes`) VALUES
(1, 1, 'Красный'),
(2, 2, 'Другой'),
(3, 1, 'Белый'),
(4, 1, 'Розовый');

-- --------------------------------------------------------

--
-- Структура таблицы `baskets`
--

CREATE TABLE `baskets` (
  `id_baskets` int NOT NULL,
  `items_id_baskets` int NOT NULL,
  `count_baskets` int NOT NULL,
  `orders_id_baskets` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id_comments` int NOT NULL,
  `users_id_comments` int NOT NULL,
  `text_comments` varchar(255) DEFAULT NULL,
  `rating_comments` int NOT NULL,
  `date_add_comments` datetime NOT NULL,
  `items_id_comments` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE `items` (
  `id_items` int NOT NULL,
  `name_items` varchar(150) NOT NULL,
  `count_items` int NOT NULL,
  `image_items` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cost_items` int NOT NULL,
  `date_add_items` date NOT NULL,
  `items_type_id_items` int NOT NULL DEFAULT '1',
  `description_items` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `views_items` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`id_items`, `name_items`, `count_items`, `image_items`, `cost_items`, `date_add_items`, `items_type_id_items`, `description_items`, `views_items`) VALUES
(1, 'Товар 1', 19, 'default.png', 872, '2026-01-13', 2, NULL, 0),
(2, 'Товар 2', 14, 'default.png', 682, '2026-01-14', 1, NULL, 0),
(3, 'Товар 3', 15, 'default.png', 740, '2026-01-14', 2, NULL, 0),
(4, 'Товар 4', 18, 'default.png', 435, '2026-01-14', 1, NULL, 0),
(5, 'Товар 5', 25, 'default.png', 366, '2026-01-14', 2, NULL, 0),
(6, 'Товар 6', 15, 'default.png', 168, '2026-01-14', 1, NULL, 0),
(7, 'Товар 7', 16, 'default.png', 85, '2026-01-14', 2, NULL, 0),
(8, 'Товар 8', 26, 'default.png', 921, '2026-01-14', 1, NULL, 0),
(9, 'Товар 9', 26, 'default.png', 790, '2026-01-14', 2, NULL, 0),
(10, 'Товар 10', 9, 'default.png', 316, '2026-01-14', 1, NULL, 0),
(11, 'Товар 11', 16, 'default.png', 164, '2026-01-14', 2, NULL, 0),
(12, 'Товар 12', 9, 'default.png', 150, '2026-01-14', 1, NULL, 0),
(13, 'Товар 13', 17, 'default.png', 472, '2026-01-14', 2, NULL, 0),
(14, 'Товар 14', 11, 'default.png', 461, '2026-01-14', 1, NULL, 0),
(15, 'Товар 15', 13, 'default.png', 330, '2026-01-14', 2, NULL, 0),
(16, 'Товар 16', 15, 'default.png', 778, '2026-01-14', 1, NULL, 0),
(17, 'Товар 17', 21, 'default.png', 448, '2026-01-14', 2, NULL, 0),
(18, 'Товар 18', 14, 'default.png', 104, '2026-01-14', 1, NULL, 0),
(19, 'Товар 19', 12, 'default.png', 910, '2026-01-14', 2, NULL, 0),
(20, 'Товар 20', 9, 'default.png', 860, '2026-01-14', 1, NULL, 0),
(21, 'Товар 21', 25, 'default.png', 937, '2026-01-14', 2, NULL, 0),
(22, 'Товар 22', 12, 'default.png', 988, '2026-01-14', 1, NULL, 0),
(23, 'Товар 23', 20, 'default.png', 495, '2026-01-14', 2, NULL, 0),
(24, 'Товар 24', 27, 'default.png', 495, '2026-01-14', 1, NULL, 0),
(25, 'Товар 25', 6, 'default.png', 190, '2026-01-14', 2, NULL, 0),
(26, 'Товар 26', 15, 'default.png', 38, '2026-01-14', 1, NULL, 0),
(27, 'Товар 27', 21, 'default.png', 963, '2026-01-14', 2, NULL, 0),
(28, 'Товар 28', 19, 'default.png', 947, '2026-01-14', 1, NULL, 0),
(29, 'Товар 29', 19, 'default.png', 764, '2026-01-14', 2, NULL, 0),
(30, 'Товар 30', 6, 'default.png', 437, '2026-01-14', 1, NULL, 0),
(31, 'Товар 31', 8, 'default.png', 765, '2026-01-14', 2, NULL, 0),
(32, 'Товар 32', 7, 'default.png', 938, '2026-01-14', 1, NULL, 0),
(33, 'Товар 33', 19, 'default.png', 358, '2026-01-14', 2, NULL, 0),
(34, 'Товар 34', 25, 'default.png', 915, '2026-01-14', 1, NULL, 0),
(35, 'Товар 35', 8, 'default.png', 470, '2026-01-14', 2, NULL, 0),
(36, 'Товар 36', 25, 'default.png', 119, '2026-01-14', 1, NULL, 0),
(37, 'Товар 37', 22, 'default.png', 215, '2026-01-14', 2, NULL, 0),
(38, 'Товар 38', 11, 'default.png', 754, '2026-01-14', 1, NULL, 0),
(39, 'Товар 39', 6, 'default.png', 523, '2026-01-14', 2, NULL, 0),
(40, 'Товар 40', 17, 'default.png', 293, '2026-01-14', 1, NULL, 0),
(41, 'Товар 41', 7, 'default.png', 243, '2026-01-14', 2, NULL, 0),
(42, 'Товар 42', 21, 'default.png', 907, '2026-01-14', 1, NULL, 0),
(43, 'Товар 43', 22, 'default.png', 346, '2026-01-14', 2, NULL, 0),
(44, 'Товар 44', 15, 'default.png', 817, '2026-01-14', 1, NULL, 0),
(45, 'Товар 45', 21, 'default.png', 439, '2026-01-14', 2, NULL, 0),
(46, 'Товар 46', 24, 'default.png', 169, '2026-01-14', 1, NULL, 0),
(47, 'Товар 47', 18, 'default.png', 659, '2026-01-14', 2, NULL, 0),
(48, 'Товар 48', 17, 'default.png', 930, '2026-01-14', 1, NULL, 0),
(49, 'Товар 49', 30, 'default.png', 188, '2026-01-14', 2, NULL, 0),
(50, 'Товар 50', 7, 'default.png', 742, '2026-01-14', 1, NULL, 0),
(51, 'Товар 51', 18, 'default.png', 452, '2026-01-14', 2, NULL, 0),
(52, 'Товар 52', 24, 'default.png', 110, '2026-01-14', 1, NULL, 0),
(53, 'Товар 53', 7, 'default.png', 125, '2026-01-14', 2, NULL, 0),
(54, 'Товар 54', 17, 'default.png', 626, '2026-01-14', 1, NULL, 0),
(55, 'Товар 55', 16, 'default.png', 652, '2026-01-14', 2, NULL, 0),
(56, 'Товар 56', 11, 'default.png', 633, '2026-01-14', 1, NULL, 0),
(57, 'Товар 57', 13, 'default.png', 596, '2026-01-14', 2, NULL, 0),
(58, 'Товар 58', 28, 'default.png', 645, '2026-01-14', 1, NULL, 0),
(59, 'Товар 59', 10, 'default.png', 399, '2026-01-14', 2, NULL, 0),
(60, 'Товар 60', 12, 'default.png', 43, '2026-01-14', 1, NULL, 0),
(61, 'Товар 61', 22, 'default.png', 254, '2026-01-14', 2, NULL, 0),
(62, 'Товар 62', 6, 'default.png', 196, '2026-01-14', 1, NULL, 0),
(63, 'Товар 63', 19, 'default.png', 927, '2026-01-14', 2, NULL, 0),
(64, 'Товар 64', 14, 'default.png', 838, '2026-01-14', 1, NULL, 0),
(65, 'Товар 65', 20, 'default.png', 205, '2026-01-14', 2, NULL, 0),
(66, 'Товар 66', 13, 'default.png', 418, '2026-01-14', 1, NULL, 0),
(67, 'Товар 67', 6, 'default.png', 126, '2026-01-14', 2, NULL, 0),
(68, 'Товар 68', 11, 'default.png', 684, '2026-01-14', 1, NULL, 0),
(69, 'Товар 69', 30, 'default.png', 839, '2026-01-14', 2, NULL, 0),
(70, 'Товар 70', 5, 'default.png', 339, '2026-01-14', 1, NULL, 0),
(71, 'Товар 71', 10, 'default.png', 560, '2026-01-14', 2, NULL, 0),
(72, 'Товар 72', 20, 'default.png', 52, '2026-01-14', 1, NULL, 0),
(73, 'Товар 73', 15, 'default.png', 979, '2026-01-14', 2, NULL, 0),
(74, 'Товар 74', 29, 'default.png', 517, '2026-01-14', 1, NULL, 0),
(75, 'Товар 75', 17, 'default.png', 413, '2026-01-14', 2, NULL, 0),
(76, 'Товар 76', 11, 'default.png', 270, '2026-01-14', 1, NULL, 0),
(77, 'Товар 77', 24, 'default.png', 290, '2026-01-14', 2, NULL, 0),
(78, 'Товар 78', 9, 'default.png', 260, '2026-01-14', 1, NULL, 0),
(79, 'Товар 79', 13, 'default.png', 901, '2026-01-14', 2, NULL, 0),
(80, 'Товар 80', 6, 'default.png', 877, '2026-01-14', 1, NULL, 0),
(81, 'Товар 81', 24, 'default.png', 470, '2026-01-14', 2, NULL, 0),
(82, 'Товар 82', 15, 'default.png', 694, '2026-01-14', 1, NULL, 0),
(83, 'Товар 83', 30, 'default.png', 103, '2026-01-14', 2, NULL, 0),
(84, 'Товар 84', 22, 'default.png', 797, '2026-01-14', 1, NULL, 0),
(85, 'Товар 85', 25, 'default.png', 332, '2026-01-14', 2, NULL, 0),
(86, 'Товар 86', 27, 'default.png', 657, '2026-01-14', 1, NULL, 0),
(87, 'Товар 87', 8, 'default.png', 421, '2026-01-14', 2, NULL, 0),
(88, 'Товар 88', 21, 'default.png', 586, '2026-01-14', 1, NULL, 0),
(89, 'Товар 89', 18, 'default.png', 16, '2026-01-14', 2, NULL, 0),
(90, 'Товар 90', 22, 'default.png', 257, '2026-01-14', 1, NULL, 0),
(91, 'Товар 91', 6, 'default.png', 487, '2026-01-14', 2, NULL, 0),
(92, 'Товар 92', 29, 'default.png', 79, '2026-01-14', 1, NULL, 0),
(93, 'Товар 93', 18, 'default.png', 685, '2026-01-14', 2, NULL, 0),
(94, 'Товар 94', 19, 'default.png', 218, '2026-01-14', 1, NULL, 0),
(95, 'Товар 95', 9, 'default.png', 820, '2026-01-14', 2, NULL, 0),
(96, 'Товар 96', 26, 'default.png', 334, '2026-01-14', 1, NULL, 0),
(97, 'Товар 97', 5, 'default.png', 922, '2026-01-14', 2, NULL, 0),
(98, 'Товар 98', 11, 'default.png', 250, '2026-01-14', 1, NULL, 0),
(99, 'Товар 99', 12, 'default.png', 461, '2026-01-14', 2, NULL, 0),
(100, 'Товар 100', 6, 'default.png', 425, '2026-01-14', 1, NULL, 0),
(101, 'Товар 101', 28, 'default.png', 620, '2026-01-14', 2, NULL, 0),
(102, 'Товар 102', 10, 'default.png', 224, '2026-01-14', 1, NULL, 0),
(103, 'Товар 103', 13, 'default.png', 36, '2026-01-14', 2, NULL, 0),
(104, 'Товар 104', 6, 'default.png', 787, '2026-01-14', 1, NULL, 0),
(105, 'Товар 105', 19, 'default.png', 692, '2026-01-14', 2, NULL, 0),
(106, 'Товар 106', 7, 'default.png', 990, '2026-01-14', 1, NULL, 0),
(107, 'Товар 107', 6, 'default.png', 639, '2026-01-14', 2, NULL, 0),
(108, 'Товар 108', 22, 'default.png', 60, '2026-01-14', 1, NULL, 0),
(109, 'Товар 109', 22, 'default.png', 726, '2026-01-14', 2, NULL, 0),
(110, 'Товар 110', 8, 'default.png', 191, '2026-01-14', 1, NULL, 0),
(111, 'Товар 111', 6, 'default.png', 147, '2026-01-14', 2, NULL, 0),
(112, 'Товар 112', 12, 'default.png', 375, '2026-01-14', 1, NULL, 0),
(113, 'Товар 113', 6, 'default.png', 206, '2026-01-14', 2, NULL, 0),
(114, 'Товар 114', 12, 'default.png', 77, '2026-01-14', 1, NULL, 0),
(115, 'Товар 115', 8, 'default.png', 146, '2026-01-14', 2, NULL, 0),
(116, 'Товар 116', 18, 'default.png', 26, '2026-01-14', 1, NULL, 0),
(117, 'Товар 117', 15, 'default.png', 376, '2026-01-14', 2, NULL, 0),
(118, 'Товар 118', 17, 'default.png', 69, '2026-01-14', 1, NULL, 0),
(119, 'Товар 119', 6, 'default.png', 73, '2026-01-14', 2, NULL, 0),
(120, 'Товар 120', 21, 'default.png', 339, '2026-01-14', 1, NULL, 0),
(121, 'Товар 121', 17, 'default.png', 52, '2026-01-14', 2, NULL, 0),
(122, 'Товар 122', 15, 'default.png', 352, '2026-01-14', 1, NULL, 0),
(123, 'Товар 123', 8, 'default.png', 701, '2026-01-14', 2, NULL, 0),
(124, 'Товар 124', 17, 'default.png', 803, '2026-01-14', 1, NULL, 0),
(125, 'Товар 125', 9, 'default.png', 590, '2026-01-14', 2, NULL, 0),
(126, 'Товар 126', 21, 'default.png', 458, '2026-01-14', 1, NULL, 0),
(127, 'Товар 127', 28, 'default.png', 256, '2026-01-14', 2, NULL, 0),
(128, 'Товар 128', 8, 'default.png', 344, '2026-01-14', 1, NULL, 0),
(129, 'Товар 129', 18, 'default.png', 720, '2026-01-14', 2, NULL, 0),
(130, 'Товар 130', 7, 'default.png', 551, '2026-01-14', 1, NULL, 0),
(131, 'Товар 131', 26, 'default.png', 546, '2026-01-14', 2, NULL, 0),
(132, 'Товар 132', 30, 'default.png', 654, '2026-01-14', 1, NULL, 0),
(133, 'Товар 133', 29, 'default.png', 155, '2026-01-14', 2, NULL, 0),
(134, 'Товар 134', 27, 'default.png', 324, '2026-01-14', 1, NULL, 0),
(135, 'Товар 135', 12, 'default.png', 116, '2026-01-14', 2, NULL, 0),
(136, 'Товар 136', 30, 'default.png', 532, '2026-01-14', 1, NULL, 0),
(137, 'Товар 137', 10, 'default.png', 506, '2026-01-14', 2, NULL, 0),
(138, 'Товар 138', 24, 'default.png', 474, '2026-01-14', 1, NULL, 0),
(139, 'Товар 139', 9, 'default.png', 888, '2026-01-14', 2, NULL, 0),
(140, 'Товар 140', 16, 'default.png', 129, '2026-01-14', 1, NULL, 0),
(141, 'Товар 141', 29, 'default.png', 440, '2026-01-14', 2, NULL, 0),
(142, 'Товар 142', 16, 'default.png', 16, '2026-01-14', 1, NULL, 0),
(143, 'Товар 143', 26, 'default.png', 463, '2026-01-14', 2, NULL, 0),
(144, 'Товар 144', 28, 'default.png', 850, '2026-01-14', 1, NULL, 0),
(145, 'Товар 145', 6, 'default.png', 315, '2026-01-14', 2, NULL, 0),
(146, 'Товар 146', 25, 'default.png', 72, '2026-01-14', 1, NULL, 0),
(147, 'Товар 147', 20, 'default.png', 253, '2026-01-14', 2, NULL, 0),
(148, 'Товар 148', 11, 'default.png', 929, '2026-01-14', 1, NULL, 0),
(149, 'Товар 149', 18, 'default.png', 100, '2026-01-14', 2, NULL, 0),
(150, 'Товар 150', 5, 'default.png', 698, '2026-01-14', 1, NULL, 0),
(151, 'Товар 151', 19, 'default.png', 813, '2026-01-14', 2, NULL, 0),
(152, 'Товар 152', 23, 'default.png', 377, '2026-01-14', 1, NULL, 0),
(153, 'Товар 153', 30, 'default.png', 160, '2026-01-14', 2, NULL, 0),
(154, 'Товар 154', 10, 'default.png', 375, '2026-01-14', 1, NULL, 0),
(155, 'Товар 155', 26, 'default.png', 967, '2026-01-14', 2, NULL, 0),
(156, 'Товар 156', 13, 'default.png', 732, '2026-01-14', 1, NULL, 0),
(157, 'Товар 157', 10, 'default.png', 268, '2026-01-14', 2, NULL, 0),
(158, 'Товар 158', 20, 'default.png', 765, '2026-01-14', 1, NULL, 0),
(159, 'Товар 159', 7, 'default.png', 289, '2026-01-14', 2, NULL, 0),
(160, 'Товар 160', 6, 'default.png', 270, '2026-01-14', 1, NULL, 0),
(161, 'Товар 161', 29, 'default.png', 419, '2026-01-14', 2, NULL, 0),
(162, 'Товар 162', 8, 'default.png', 597, '2026-01-14', 1, NULL, 0),
(163, 'Товар 163', 5, 'default.png', 527, '2026-01-14', 2, NULL, 0),
(164, 'Товар 164', 16, 'default.png', 257, '2026-01-14', 1, NULL, 0),
(165, 'Товар 165', 28, 'default.png', 766, '2026-01-14', 2, NULL, 0),
(166, 'Товар 166', 22, 'default.png', 802, '2026-01-14', 1, NULL, 0),
(167, 'Товар 167', 25, 'default.png', 531, '2026-01-14', 2, NULL, 0),
(168, 'Товар 168', 20, 'default.png', 133, '2026-01-14', 1, NULL, 0),
(169, 'Товар 169', 6, 'default.png', 221, '2026-01-14', 2, NULL, 0),
(170, 'Товар 170', 10, 'default.png', 579, '2026-01-14', 1, NULL, 0),
(171, 'Товар 171', 13, 'default.png', 539, '2026-01-14', 2, NULL, 0),
(172, 'Товар 172', 25, 'default.png', 981, '2026-01-14', 1, NULL, 0),
(173, 'Товар 173', 20, 'default.png', 494, '2026-01-14', 2, NULL, 0),
(174, 'Товар 174', 17, 'default.png', 796, '2026-01-14', 1, NULL, 0),
(175, 'Товар 175', 10, 'default.png', 155, '2026-01-14', 2, NULL, 0),
(176, 'Товар 176', 16, 'default.png', 493, '2026-01-14', 1, NULL, 0),
(177, 'Товар 177', 10, 'default.png', 368, '2026-01-14', 2, NULL, 0),
(178, 'Товар 178', 29, 'default.png', 166, '2026-01-14', 1, NULL, 0),
(179, 'Товар 179', 24, 'default.png', 261, '2026-01-14', 2, NULL, 0),
(180, 'Товар 180', 15, 'default.png', 368, '2026-01-14', 1, NULL, 0),
(181, 'Товар 181', 10, 'default.png', 751, '2026-01-14', 2, NULL, 0),
(182, 'Товар 182', 14, 'default.png', 531, '2026-01-14', 1, NULL, 0),
(183, 'Товар 183', 23, 'default.png', 854, '2026-01-14', 2, NULL, 0),
(184, 'Товар 184', 16, 'default.png', 679, '2026-01-14', 1, NULL, 0),
(185, 'Товар 185', 5, 'default.png', 647, '2026-01-14', 2, NULL, 0),
(186, 'Товар 186', 27, 'default.png', 125, '2026-01-14', 1, NULL, 0),
(187, 'Товар 187', 5, 'default.png', 418, '2026-01-14', 2, NULL, 0),
(188, 'Товар 188', 18, 'default.png', 658, '2026-01-14', 1, NULL, 0),
(189, 'Товар 189', 18, 'default.png', 446, '2026-01-14', 2, NULL, 0),
(190, 'Товар 190', 21, 'default.png', 953, '2026-01-14', 1, NULL, 0),
(191, 'Товар 191', 20, 'default.png', 322, '2026-01-14', 2, NULL, 0),
(192, 'Товар 192', 11, 'default.png', 102, '2026-01-14', 1, NULL, 0),
(193, 'Товар 193', 29, 'default.png', 592, '2026-01-14', 2, NULL, 0),
(194, 'Товар 194', 18, 'default.png', 372, '2026-01-14', 1, NULL, 0),
(195, 'Товар 195', 19, 'default.png', 162, '2026-01-14', 2, NULL, 0),
(196, 'Товар 196', 6, 'default.png', 562, '2026-01-14', 1, NULL, 0),
(197, 'Товар 197', 5, 'default.png', 241, '2026-01-14', 2, NULL, 0),
(198, 'Товар 198', 9, 'default.png', 644, '2026-01-14', 1, NULL, 0),
(199, 'Товар 199', 13, 'default.png', 413, '2026-01-14', 2, NULL, 0),
(200, 'Товар 200', 20, 'default.png', 137, '2026-01-14', 1, NULL, 0),
(201, 'Товар 201', 18, 'default.png', 798, '2026-01-14', 2, NULL, 0),
(202, 'Товар 202', 5, 'default.png', 531, '2026-01-14', 1, NULL, 0),
(203, 'Товар 203', 11, 'default.png', 45, '2026-01-14', 2, NULL, 0),
(204, 'Товар 204', 14, 'default.png', 847, '2026-01-14', 1, NULL, 0),
(205, 'Товар 205', 27, 'default.png', 229, '2026-01-14', 2, NULL, 0),
(206, 'Товар 206', 13, 'default.png', 962, '2026-01-14', 1, NULL, 0),
(207, 'Товар 207', 12, 'default.png', 95, '2026-01-14', 2, NULL, 0),
(208, 'Товар 208', 16, 'default.png', 334, '2026-01-14', 1, NULL, 0),
(209, 'Товар 209', 6, 'default.png', 88, '2026-01-14', 2, NULL, 0),
(210, 'Товар 210', 18, 'default.png', 774, '2026-01-14', 1, NULL, 0),
(211, 'Товар 211', 22, 'default.png', 787, '2026-01-14', 2, NULL, 0),
(212, 'Товар 212', 12, 'default.png', 91, '2026-01-14', 1, NULL, 0),
(213, 'Товар 213', 7, 'default.png', 425, '2026-01-14', 2, NULL, 0),
(214, 'Товар 214', 22, 'default.png', 810, '2026-01-14', 1, NULL, 0),
(215, 'Товар 215', 7, 'default.png', 996, '2026-01-14', 2, NULL, 0),
(216, 'Товар 216', 21, 'default.png', 904, '2026-01-14', 1, NULL, 0),
(217, 'Товар 217', 25, 'default.png', 580, '2026-01-14', 2, NULL, 0),
(218, 'Товар 218', 7, 'default.png', 513, '2026-01-14', 1, NULL, 0),
(219, 'Товар 219', 29, 'default.png', 371, '2026-01-14', 2, NULL, 0),
(220, 'Товар 220', 13, 'default.png', 23, '2026-01-14', 1, NULL, 0),
(221, 'Товар 221', 15, 'default.png', 614, '2026-01-14', 2, NULL, 0),
(222, 'Товар 222', 19, 'default.png', 686, '2026-01-14', 1, NULL, 0),
(223, 'Товар 223', 24, 'default.png', 980, '2026-01-14', 2, NULL, 0),
(224, 'Товар 224', 30, 'default.png', 662, '2026-01-14', 1, NULL, 0),
(225, 'Товар 225', 8, 'default.png', 716, '2026-01-14', 2, NULL, 0),
(226, 'Товар 226', 15, 'default.png', 433, '2026-01-14', 1, NULL, 0),
(227, 'Товар 227', 17, 'default.png', 176, '2026-01-14', 2, NULL, 0),
(228, 'Товар 228', 11, 'default.png', 839, '2026-01-14', 1, NULL, 0),
(229, 'Товар 229', 14, 'default.png', 834, '2026-01-14', 2, NULL, 0),
(230, 'Товар 230', 14, 'default.png', 11, '2026-01-14', 1, NULL, 0),
(231, 'Товар 231', 14, 'default.png', 839, '2026-01-14', 2, NULL, 0),
(232, 'Товар 232', 18, 'default.png', 152, '2026-01-14', 1, NULL, 0),
(233, 'Товар 233', 15, 'default.png', 137, '2026-01-14', 2, NULL, 0),
(234, 'Товар 234', 9, 'default.png', 653, '2026-01-14', 1, NULL, 0),
(235, 'Товар 235', 7, 'default.png', 248, '2026-01-14', 2, NULL, 0),
(236, 'Товар 236', 11, 'default.png', 626, '2026-01-14', 1, NULL, 0),
(237, 'Товар 237', 9, 'default.png', 695, '2026-01-14', 2, NULL, 0),
(238, 'Товар 238', 19, 'default.png', 151, '2026-01-14', 1, NULL, 0),
(239, 'Товар 239', 25, 'default.png', 467, '2026-01-14', 2, NULL, 0),
(240, 'Товар 240', 16, 'default.png', 560, '2026-01-14', 1, NULL, 0),
(241, 'Товар 241', 18, 'default.png', 36, '2026-01-14', 2, NULL, 0),
(242, 'Товар 242', 17, 'default.png', 286, '2026-01-14', 1, NULL, 0),
(243, 'Товар 243', 15, 'default.png', 492, '2026-01-14', 2, NULL, 0),
(244, 'Товар 244', 18, 'default.png', 507, '2026-01-14', 1, NULL, 0),
(245, 'Товар 245', 28, 'default.png', 614, '2026-01-14', 2, NULL, 0),
(246, 'Товар 246', 23, 'default.png', 743, '2026-01-14', 1, NULL, 0),
(247, 'Товар 247', 13, 'default.png', 212, '2026-01-14', 2, NULL, 0),
(248, 'Товар 248', 28, 'default.png', 317, '2026-01-14', 1, NULL, 0),
(249, 'Товар 249', 16, 'default.png', 586, '2026-01-14', 2, NULL, 0),
(250, 'Товар 250', 24, 'default.png', 365, '2026-01-14', 1, NULL, 0),
(251, 'Товар 251', 24, 'default.png', 43, '2026-01-14', 2, NULL, 0),
(252, 'Товар 252', 29, 'default.png', 151, '2026-01-14', 1, NULL, 0),
(253, 'Товар 253', 7, 'default.png', 718, '2026-01-14', 2, NULL, 0),
(254, 'Товар 254', 25, 'default.png', 314, '2026-01-14', 1, NULL, 0),
(255, 'Товар 255', 20, 'default.png', 721, '2026-01-14', 2, NULL, 0),
(256, 'Товар 256', 18, 'default.png', 763, '2026-01-14', 1, NULL, 0),
(257, 'Товар 257', 30, 'default.png', 229, '2026-01-14', 2, NULL, 0),
(258, 'Товар 258', 25, 'default.png', 77, '2026-01-14', 1, NULL, 0),
(259, 'Товар 259', 27, 'default.png', 625, '2026-01-14', 2, NULL, 0),
(260, 'Товар 260', 17, 'default.png', 336, '2026-01-14', 1, NULL, 0),
(261, 'Товар 261', 17, 'default.png', 102, '2026-01-14', 2, NULL, 0),
(262, 'Товар 262', 27, 'default.png', 325, '2026-01-14', 1, NULL, 0),
(263, 'Товар 263', 23, 'default.png', 891, '2026-01-14', 2, NULL, 0),
(264, 'Товар 264', 18, 'default.png', 203, '2026-01-14', 1, NULL, 0),
(265, 'Товар 265', 24, 'default.png', 644, '2026-01-14', 2, NULL, 0),
(266, 'Товар 266', 19, 'default.png', 186, '2026-01-14', 1, NULL, 0),
(267, 'Товар 267', 24, 'default.png', 953, '2026-01-14', 2, NULL, 0),
(268, 'Товар 268', 13, 'default.png', 483, '2026-01-14', 1, NULL, 0),
(269, 'Товар 269', 15, 'default.png', 741, '2026-01-14', 2, NULL, 0),
(270, 'Товар 270', 29, 'default.png', 381, '2026-01-14', 1, NULL, 0),
(271, 'Товар 271', 24, 'default.png', 972, '2026-01-14', 2, NULL, 0),
(272, 'Товар 272', 10, 'default.png', 257, '2026-01-14', 1, NULL, 0),
(273, 'Товар 273', 20, 'default.png', 898, '2026-01-14', 2, NULL, 0),
(274, 'Товар 274', 28, 'default.png', 259, '2026-01-14', 1, NULL, 0),
(275, 'Товар 275', 27, 'default.png', 106, '2026-01-14', 2, NULL, 0),
(276, 'Товар 276', 30, 'default.png', 379, '2026-01-14', 1, NULL, 0),
(277, 'Товар 277', 22, 'default.png', 758, '2026-01-14', 2, NULL, 0),
(278, 'Товар 278', 16, 'default.png', 79, '2026-01-14', 1, NULL, 0),
(279, 'Товар 279', 11, 'default.png', 500, '2026-01-14', 2, NULL, 0),
(280, 'Товар 280', 15, 'default.png', 261, '2026-01-14', 1, NULL, 0),
(281, 'Товар 281', 15, 'default.png', 820, '2026-01-14', 2, NULL, 0),
(282, 'Товар 282', 6, 'default.png', 731, '2026-01-14', 1, NULL, 0),
(283, 'Товар 283', 18, 'default.png', 456, '2026-01-14', 2, NULL, 0),
(284, 'Товар 284', 15, 'default.png', 207, '2026-01-14', 1, NULL, 0),
(285, 'Товар 285', 16, 'default.png', 648, '2026-01-14', 2, NULL, 0),
(286, 'Товар 286', 15, 'default.png', 371, '2026-01-14', 1, NULL, 0),
(287, 'Товар 287', 13, 'default.png', 582, '2026-01-14', 2, NULL, 0),
(288, 'Товар 288', 24, 'default.png', 728, '2026-01-14', 1, NULL, 0),
(289, 'Товар 289', 30, 'default.png', 230, '2026-01-14', 2, NULL, 0),
(290, 'Товар 290', 5, 'default.png', 689, '2026-01-14', 1, NULL, 0),
(291, 'Товар 291', 21, 'default.png', 296, '2026-01-14', 2, NULL, 0),
(292, 'Товар 292', 20, 'default.png', 222, '2026-01-14', 1, NULL, 0),
(293, 'Товар 293', 24, 'default.png', 555, '2026-01-14', 2, NULL, 0),
(294, 'Товар 294', 19, 'default.png', 356, '2026-01-14', 1, NULL, 0),
(295, 'Товар 295', 19, 'default.png', 914, '2026-01-14', 2, NULL, 0),
(296, 'Товар 296', 28, 'default.png', 938, '2026-01-14', 1, NULL, 0),
(297, 'Товар 297', 9, 'default.png', 343, '2026-01-14', 2, NULL, 0),
(298, 'Товар 298', 24, 'default.png', 883, '2026-01-14', 1, NULL, 0),
(299, 'Товар 299', 26, 'default.png', 585, '2026-01-14', 2, NULL, 0),
(300, 'Товар 300', 21, 'default.png', 357, '2026-01-14', 1, NULL, 0),
(301, '123', 20, 'default.png', 101, '2026-01-14', 2, NULL, 0),
(302, '123', 27, 'default.png', 43, '2026-01-14', 1, NULL, 0),
(303, '123', 16, 'default.png', 102, '2026-01-14', 2, NULL, 0),
(304, '123', 10, 'default.png', 175, '2026-01-14', 1, NULL, 0),
(305, '123', 24, 'default.png', 756, '2026-01-14', 2, NULL, 0),
(306, '123', 9, 'default.png', 826, '2026-01-14', 1, NULL, 0),
(307, '123', 21, 'default.png', 368, '2026-01-14', 2, NULL, 0),
(308, '123', 12, 'default.png', 130, '2026-01-14', 1, NULL, 0),
(309, '123', 24, 'default.png', 994, '2026-01-14', 2, NULL, 0),
(310, '123', 17, 'default.png', 766, '2026-01-14', 1, NULL, 0),
(311, '123', 19, 'default.png', 945, '2026-01-14', 2, NULL, 0),
(312, '123', 9, 'default.png', 64, '2026-01-14', 1, NULL, 0),
(313, '123', 28, 'default.png', 485, '2026-01-14', 2, NULL, 0),
(314, '123', 15, 'default.png', 284, '2026-01-14', 1, NULL, 0),
(315, '123', 27, 'default.png', 767, '2026-01-14', 2, NULL, 0),
(316, '123', 27, 'default.png', 452, '2026-01-14', 1, NULL, 0),
(317, '123', 28, 'default.png', 163, '2026-01-14', 2, NULL, 0),
(318, '123', 5, 'default.png', 687, '2026-01-14', 1, NULL, 0),
(319, '123', 9, 'default.png', 337, '2026-01-14', 2, NULL, 0),
(320, '123', 30, 'default.png', 258, '2026-01-14', 1, NULL, 0),
(321, '123', 7, 'default.png', 301, '2026-01-14', 2, NULL, 0),
(322, '123', 23, 'default.png', 570, '2026-01-14', 1, NULL, 0),
(323, '123', 10, 'default.png', 594, '2026-01-14', 2, NULL, 0),
(324, '123', 5, 'default.png', 824, '2026-01-14', 1, NULL, 0),
(325, '123', 19, 'default.png', 86, '2026-01-14', 2, NULL, 0),
(326, '123', 20, 'default.png', 146, '2026-01-14', 1, NULL, 0),
(327, '123', 7, 'default.png', 97, '2026-01-14', 2, NULL, 0),
(328, '123', 6, 'default.png', 229, '2026-01-14', 1, NULL, 0),
(329, '123', 21, 'default.png', 431, '2026-01-14', 2, NULL, 0),
(330, '123', 11, 'default.png', 706, '2026-01-14', 1, NULL, 0),
(331, '123', 22, 'default.png', 229, '2026-01-14', 2, NULL, 0),
(332, '123', 26, 'default.png', 541, '2026-01-14', 1, NULL, 0),
(333, '123', 13, 'default.png', 960, '2026-01-14', 2, NULL, 0),
(334, '123', 12, 'default.png', 931, '2026-01-14', 1, NULL, 0),
(335, '123', 12, 'default.png', 811, '2026-01-14', 2, NULL, 0),
(336, '123', 10, 'default.png', 797, '2026-01-14', 1, NULL, 0),
(337, '123', 11, 'default.png', 337, '2026-01-14', 2, NULL, 0),
(338, '123', 9, 'default.png', 505, '2026-01-14', 1, NULL, 0),
(339, '123', 20, 'default.png', 358, '2026-01-14', 2, NULL, 0),
(340, '123', 8, 'default.png', 445, '2026-01-14', 1, NULL, 0),
(341, '123', 10, 'default.png', 224, '2026-01-14', 2, NULL, 0),
(342, '123', 28, 'default.png', 154, '2026-01-14', 1, NULL, 0),
(343, '123', 17, 'default.png', 286, '2026-01-14', 2, NULL, 0),
(344, '123', 9, 'default.png', 120, '2026-01-14', 1, NULL, 0),
(345, '123', 25, 'default.png', 892, '2026-01-14', 2, NULL, 0),
(346, '123', 12, 'default.png', 890, '2026-01-14', 1, NULL, 0),
(347, '123', 22, 'default.png', 74, '2026-01-14', 2, NULL, 0),
(348, '123', 9, 'default.png', 843, '2026-01-14', 1, NULL, 0),
(349, '123', 26, 'default.png', 981, '2026-01-14', 2, NULL, 0),
(350, '123', 21, 'default.png', 483, '2026-01-14', 1, NULL, 0),
(351, '123', 17, 'default.png', 69, '2026-01-14', 2, NULL, 0),
(352, '123', 19, 'default.png', 153, '2026-01-14', 1, NULL, 0),
(353, '123', 13, 'default.png', 825, '2026-01-14', 2, NULL, 0),
(354, '123', 8, 'default.png', 993, '2026-01-14', 1, NULL, 0),
(355, '123', 23, 'default.png', 620, '2026-01-14', 2, NULL, 0),
(356, '123', 14, 'default.png', 672, '2026-01-14', 1, NULL, 0),
(357, '123', 18, 'default.png', 963, '2026-01-14', 2, NULL, 0),
(358, '123', 28, 'default.png', 240, '2026-01-14', 1, NULL, 0),
(359, '123', 20, 'default.png', 540, '2026-01-14', 2, NULL, 0),
(360, '123', 21, 'default.png', 202, '2026-01-14', 1, NULL, 0),
(361, '123', 12, 'default.png', 18, '2026-01-14', 2, NULL, 0),
(362, '123', 11, 'default.png', 621, '2026-01-14', 1, NULL, 0),
(363, '123', 5, 'default.png', 489, '2026-01-14', 2, NULL, 0),
(364, '123', 6, 'default.png', 730, '2026-01-14', 1, NULL, 0),
(365, '123', 6, 'default.png', 683, '2026-01-14', 2, NULL, 0),
(366, '123', 5, 'default.png', 907, '2026-01-14', 1, NULL, 0),
(367, '123', 23, 'default.png', 806, '2026-01-14', 2, NULL, 0),
(368, '123', 28, 'default.png', 42, '2026-01-14', 1, NULL, 0),
(369, '123', 12, 'default.png', 353, '2026-01-14', 2, NULL, 0),
(370, '123', 6, 'default.png', 998, '2026-01-14', 1, NULL, 0),
(371, '123', 29, 'default.png', 859, '2026-01-14', 2, NULL, 0),
(372, '123', 29, 'default.png', 46, '2026-01-14', 1, NULL, 0),
(373, '123', 11, 'default.png', 778, '2026-01-14', 2, NULL, 0),
(374, '123', 18, 'default.png', 901, '2026-01-14', 1, NULL, 0),
(375, '123', 29, 'default.png', 293, '2026-01-14', 2, NULL, 0),
(376, '123', 21, 'default.png', 916, '2026-01-14', 1, NULL, 0),
(377, '123', 11, 'default.png', 228, '2026-01-14', 2, NULL, 0),
(378, '123', 26, 'default.png', 796, '2026-01-14', 1, NULL, 0),
(379, '123', 20, 'default.png', 291, '2026-01-14', 2, NULL, 0),
(380, '123', 22, 'default.png', 685, '2026-01-14', 1, NULL, 0),
(381, '123', 17, 'default.png', 356, '2026-01-14', 2, NULL, 0),
(382, '123', 26, 'default.png', 103, '2026-01-14', 1, NULL, 0),
(383, '123', 24, 'default.png', 626, '2026-01-14', 2, NULL, 0),
(384, '123', 24, 'default.png', 937, '2026-01-14', 1, NULL, 0),
(385, '123', 22, 'default.png', 483, '2026-01-14', 2, NULL, 0),
(386, '123', 11, 'default.png', 604, '2026-01-14', 1, NULL, 0),
(387, '123', 19, 'default.png', 943, '2026-01-14', 2, NULL, 0),
(388, '123', 13, 'default.png', 100, '2026-01-14', 1, NULL, 0),
(389, '123', 11, 'default.png', 869, '2026-01-14', 2, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `items_properties`
--

CREATE TABLE `items_properties` (
  `id_items_properties` int NOT NULL,
  `items_id_items_properties` int NOT NULL,
  `attributes_id_items_properties` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `items_type`
--

CREATE TABLE `items_type` (
  `id_items_type` int NOT NULL,
  `name_items_type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `items_type`
--

INSERT INTO `items_type` (`id_items_type`, `name_items_type`) VALUES
(1, 'Обычный'),
(2, 'Сложный'),
(3, 'Средний');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id_orders` int NOT NULL,
  `status_id_orders` int NOT NULL,
  `users_id_orders` int NOT NULL,
  `datetime_buy_orders` datetime DEFAULT NULL,
  `datetime_receipt_orders` datetime DEFAULT NULL,
  `address_orders` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `note_orders` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `datetime_start_orders` datetime DEFAULT NULL,
  `datetime_end_orders` datetime DEFAULT NULL,
  `users_deliver_orders` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `properties`
--

CREATE TABLE `properties` (
  `id_properties` int NOT NULL,
  `name_properties` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `properties`
--

INSERT INTO `properties` (`id_properties`, `name_properties`) VALUES
(1, 'Цвет'),
(2, 'Арбуз');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id_status` int NOT NULL,
  `name_status` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id_status`, `name_status`) VALUES
(1, 'В корзине'),
(2, 'Ожидание'),
(3, 'Сборка'),
(4, 'Доставка'),
(5, 'Доставлено'),
(6, 'Получено');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_users` int NOT NULL,
  `email_users` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password_users` varchar(150) NOT NULL,
  `name_users` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date_create_users` date DEFAULT NULL,
  `avatar_users` varchar(100) DEFAULT NULL,
  `is_banned_users` tinyint(1) NOT NULL DEFAULT '0',
  `is_deliver_users` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_users`, `email_users`, `password_users`, `name_users`, `date_create_users`, `avatar_users`, `is_banned_users`, `is_deliver_users`) VALUES
(1, 'admin@admin.com', '$2y$12$73pbhz0bIrj0J/DDZhU6AO3VSXrr2ZI8DlcXWyFm6g1qmqOZoQzvi', 'Админ', '2025-11-30', NULL, 0, 1),
(2, 'user@user.com', '$2y$12$.iazHfKUey3WBOZFxhJgReCkDIXLx9zjStcHGXNfOzUhKX9Ddn35q', 'пользователь', '2025-11-30', NULL, 0, 0),
(3, 'test@test.com', '$2y$12$uEnyqUE7pYWjb.nlWsd6O.GZNONfVQB0MoSh5eXi0YMDlMt9FTVlC', 'Тест', '2026-03-17', NULL, 1, 0),
(4, 'del1@g.g', '$2y$12$73pbhz0bIrj0J/DDZhU6AO3VSXrr2ZI8DlcXWyFm6g1qmqOZoQzvi', 'ДоставщикОдин', '2026-04-06', NULL, 0, 1),
(5, 'del2@g.g', '$2y$12$73pbhz0bIrj0J/DDZhU6AO3VSXrr2ZI8DlcXWyFm6g1qmqOZoQzvi', 'ДоставщикДва', '2026-04-06', NULL, 0, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id_attributes`),
  ADD KEY `properties_id_attributes` (`properties_id_attributes`);

--
-- Индексы таблицы `baskets`
--
ALTER TABLE `baskets`
  ADD PRIMARY KEY (`id_baskets`),
  ADD KEY `items_id_baskets` (`items_id_baskets`),
  ADD KEY `orders_id_baskets` (`orders_id_baskets`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comments`),
  ADD KEY `id_users_comments` (`users_id_comments`),
  ADD KEY `items_id_comments` (`items_id_comments`);

--
-- Индексы таблицы `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id_items`),
  ADD KEY `id_items_type_items` (`items_type_id_items`);

--
-- Индексы таблицы `items_properties`
--
ALTER TABLE `items_properties`
  ADD PRIMARY KEY (`id_items_properties`),
  ADD KEY `items_id_items_properties` (`items_id_items_properties`),
  ADD KEY `attributes_id_items_properties` (`attributes_id_items_properties`);

--
-- Индексы таблицы `items_type`
--
ALTER TABLE `items_type`
  ADD PRIMARY KEY (`id_items_type`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_orders`),
  ADD KEY `status_id_orders` (`status_id_orders`),
  ADD KEY `users_id_orders` (`users_id_orders`),
  ADD KEY `user_deliver_baskets` (`users_deliver_orders`);

--
-- Индексы таблицы `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id_properties`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `login_users` (`email_users`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id_attributes` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `baskets`
--
ALTER TABLE `baskets`
  MODIFY `id_baskets` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comments` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id_items` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;

--
-- AUTO_INCREMENT для таблицы `items_properties`
--
ALTER TABLE `items_properties`
  MODIFY `id_items_properties` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `items_type`
--
ALTER TABLE `items_type`
  MODIFY `id_items_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `properties`
--
ALTER TABLE `properties`
  MODIFY `id_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `attributes`
--
ALTER TABLE `attributes`
  ADD CONSTRAINT `attributes_ibfk_1` FOREIGN KEY (`properties_id_attributes`) REFERENCES `properties` (`id_properties`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `baskets`
--
ALTER TABLE `baskets`
  ADD CONSTRAINT `baskets_ibfk_1` FOREIGN KEY (`items_id_baskets`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `baskets_ibfk_2` FOREIGN KEY (`orders_id_baskets`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`users_id_comments`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`items_id_comments`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`items_type_id_items`) REFERENCES `items_type` (`id_items_type`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `items_properties`
--
ALTER TABLE `items_properties`
  ADD CONSTRAINT `items_properties_ibfk_1` FOREIGN KEY (`items_id_items_properties`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `items_properties_ibfk_2` FOREIGN KEY (`attributes_id_items_properties`) REFERENCES `attributes` (`id_attributes`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`status_id_orders`) REFERENCES `status` (`id_status`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`users_id_orders`) REFERENCES `users` (`id_users`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`users_deliver_orders`) REFERENCES `users` (`id_users`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
