-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Май 31 2026 г., 01:54
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
(4, 1, 'Розовый'),
(5, 3, 'Тест');

-- --------------------------------------------------------

--
-- Структура таблицы `baskets`
--

CREATE TABLE `baskets` (
  `id_baskets` int NOT NULL,
  `items_id_baskets` int NOT NULL,
  `count_baskets` int NOT NULL,
  `orders_id_baskets` int DEFAULT NULL,
  `cost_baskets` int DEFAULT NULL,
  `discount_baskets` int DEFAULT NULL
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
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id_favorites` int NOT NULL,
  `items_id_favorites` int NOT NULL,
  `users_id_favorites` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE `items` (
  `id_items` int NOT NULL,
  `name_items` varchar(150) NOT NULL,
  `count_items` int NOT NULL,
  `cost_items` int NOT NULL,
  `date_add_items` date NOT NULL,
  `items_type_id_items` int NOT NULL,
  `description_items` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `views_items` int NOT NULL DEFAULT '0',
  `discount_items` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`id_items`, `name_items`, `count_items`, `cost_items`, `date_add_items`, `items_type_id_items`, `description_items`, `views_items`, `discount_items`) VALUES
(1, 'Товар 1', 19, 872, '2026-01-13', 2, NULL, 0, NULL),
(2, 'Товар 2', 14, 682, '2026-01-14', 1, NULL, 0, NULL),
(3, 'Товар 3', 15, 740, '2026-01-14', 2, NULL, 0, NULL),
(4, 'Товар 4', 18, 435, '2026-01-14', 1, NULL, 0, NULL),
(5, 'Товар 5', 25, 366, '2026-01-14', 2, NULL, 0, NULL),
(6, 'Товар 6', 15, 168, '2026-01-14', 1, NULL, 0, NULL),
(7, 'Товар 7', 16, 85, '2026-01-14', 2, NULL, 0, NULL),
(8, 'Товар 8', 26, 921, '2026-01-14', 1, NULL, 0, NULL),
(9, 'Товар 9', 26, 790, '2026-01-14', 2, NULL, 0, NULL),
(10, 'Товар 10', 9, 316, '2026-01-14', 1, NULL, 0, NULL),
(11, 'Товар 11', 16, 164, '2026-01-14', 2, NULL, 0, NULL),
(12, 'Товар 12', 9, 150, '2026-01-14', 1, NULL, 0, NULL),
(13, 'Товар 13', 17, 472, '2026-01-14', 2, NULL, 0, NULL),
(14, 'Товар 14', 11, 461, '2026-01-14', 1, NULL, 0, NULL),
(15, 'Товар 15', 13, 330, '2026-01-14', 2, NULL, 0, NULL),
(16, 'Товар 16', 15, 778, '2026-01-14', 1, NULL, 0, NULL),
(17, 'Товар 17', 21, 448, '2026-01-14', 2, NULL, 0, NULL),
(18, 'Товар 18', 14, 104, '2026-01-14', 1, NULL, 0, NULL),
(19, 'Товар 19', 12, 910, '2026-01-14', 2, NULL, 0, NULL),
(20, 'Товар 20', 9, 860, '2026-01-14', 1, NULL, 0, NULL),
(21, 'Товар 21', 25, 937, '2026-01-14', 2, NULL, 0, NULL),
(22, 'Товар 22', 12, 988, '2026-01-14', 1, NULL, 0, NULL),
(23, 'Товар 23', 20, 495, '2026-01-14', 2, NULL, 0, NULL),
(24, 'Товар 24', 27, 495, '2026-01-14', 1, NULL, 0, NULL),
(25, 'Товар 25', 6, 190, '2026-01-14', 2, NULL, 0, NULL),
(26, 'Товар 26', 15, 38, '2026-01-14', 1, NULL, 0, NULL),
(27, 'Товар 27', 21, 963, '2026-01-14', 2, NULL, 0, NULL),
(28, 'Товар 28', 19, 947, '2026-01-14', 1, NULL, 0, NULL),
(29, 'Товар 29', 19, 764, '2026-01-14', 2, NULL, 0, NULL),
(30, 'Товар 30', 6, 437, '2026-01-14', 1, NULL, 0, NULL),
(31, 'Товар 31', 8, 765, '2026-01-14', 2, NULL, 0, NULL),
(32, 'Товар 32', 7, 938, '2026-01-14', 1, NULL, 0, NULL),
(33, 'Товар 33', 19, 358, '2026-01-14', 2, NULL, 0, NULL),
(34, 'Товар 34', 25, 915, '2026-01-14', 1, NULL, 0, NULL),
(35, 'Товар 35', 8, 470, '2026-01-14', 2, NULL, 0, NULL),
(36, 'Товар 36', 25, 119, '2026-01-14', 1, NULL, 0, NULL),
(37, 'Товар 37', 22, 215, '2026-01-14', 2, NULL, 0, NULL),
(38, 'Товар 38', 11, 754, '2026-01-14', 1, NULL, 0, NULL),
(39, 'Товар 39', 6, 523, '2026-01-14', 2, NULL, 0, NULL),
(40, 'Товар 40', 17, 293, '2026-01-14', 1, NULL, 0, NULL),
(41, 'Товар 41', 7, 243, '2026-01-14', 2, NULL, 0, NULL),
(42, 'Товар 42', 21, 907, '2026-01-14', 1, NULL, 0, NULL),
(43, 'Товар 43', 22, 346, '2026-01-14', 2, NULL, 0, NULL),
(44, 'Товар 44', 15, 817, '2026-01-14', 1, NULL, 0, NULL),
(45, 'Товар 45', 21, 439, '2026-01-14', 2, NULL, 0, NULL),
(46, 'Товар 46', 24, 169, '2026-01-14', 1, NULL, 0, NULL),
(47, 'Товар 47', 18, 659, '2026-01-14', 2, NULL, 0, NULL),
(48, 'Товар 48', 17, 930, '2026-01-14', 1, NULL, 0, NULL),
(49, 'Товар 49', 30, 188, '2026-01-14', 2, NULL, 0, NULL),
(50, 'Товар 50', 7, 742, '2026-01-14', 1, NULL, 0, NULL),
(51, 'Товар 51', 18, 452, '2026-01-14', 2, NULL, 0, NULL),
(52, 'Товар 52', 24, 110, '2026-01-14', 1, NULL, 0, NULL),
(53, 'Товар 53', 7, 125, '2026-01-14', 2, NULL, 0, NULL),
(54, 'Товар 54', 17, 626, '2026-01-14', 1, NULL, 0, NULL),
(55, 'Товар 55', 16, 652, '2026-01-14', 2, NULL, 0, NULL),
(56, 'Товар 56', 11, 633, '2026-01-14', 1, NULL, 0, NULL),
(57, 'Товар 57', 13, 596, '2026-01-14', 2, NULL, 0, NULL),
(58, 'Товар 58', 28, 645, '2026-01-14', 1, NULL, 0, NULL),
(59, 'Товар 59', 10, 399, '2026-01-14', 2, NULL, 0, NULL),
(60, 'Товар 60', 12, 43, '2026-01-14', 1, NULL, 0, NULL),
(61, 'Товар 61', 22, 254, '2026-01-14', 2, NULL, 0, NULL),
(62, 'Товар 62', 6, 196, '2026-01-14', 1, NULL, 0, NULL),
(63, 'Товар 63', 19, 927, '2026-01-14', 2, NULL, 0, NULL),
(64, 'Товар 64', 14, 838, '2026-01-14', 1, NULL, 0, NULL),
(65, 'Товар 65', 20, 205, '2026-01-14', 2, NULL, 0, NULL),
(66, 'Товар 66', 13, 418, '2026-01-14', 1, NULL, 0, NULL),
(67, 'Товар 67', 6, 126, '2026-01-14', 2, NULL, 0, NULL),
(68, 'Товар 68', 11, 684, '2026-01-14', 1, NULL, 0, NULL),
(69, 'Товар 69', 30, 839, '2026-01-14', 2, NULL, 0, NULL),
(70, 'Товар 70', 5, 339, '2026-01-14', 1, NULL, 0, NULL),
(71, 'Товар 71', 10, 560, '2026-01-14', 2, NULL, 0, NULL),
(72, 'Товар 72', 20, 52, '2026-01-14', 1, NULL, 0, NULL),
(73, 'Товар 73', 15, 979, '2026-01-14', 2, NULL, 0, NULL),
(74, 'Товар 74', 29, 517, '2026-01-14', 1, NULL, 0, NULL),
(75, 'Товар 75', 17, 413, '2026-01-14', 2, NULL, 0, NULL),
(76, 'Товар 76', 11, 270, '2026-01-14', 1, NULL, 0, NULL),
(77, 'Товар 77', 24, 290, '2026-01-14', 2, NULL, 0, NULL),
(78, 'Товар 78', 9, 260, '2026-01-14', 1, NULL, 0, NULL),
(79, 'Товар 79', 13, 901, '2026-01-14', 2, NULL, 0, NULL),
(80, 'Товар 80', 6, 877, '2026-01-14', 1, NULL, 0, NULL),
(81, 'Товар 81', 24, 470, '2026-01-14', 2, NULL, 0, NULL),
(82, 'Товар 82', 15, 694, '2026-01-14', 1, NULL, 0, NULL),
(83, 'Товар 83', 30, 103, '2026-01-14', 2, NULL, 0, NULL),
(84, 'Товар 84', 22, 797, '2026-01-14', 1, NULL, 0, NULL),
(85, 'Товар 85', 25, 332, '2026-01-14', 2, NULL, 0, NULL),
(86, 'Товар 86', 27, 657, '2026-01-14', 1, NULL, 0, NULL),
(87, 'Товар 87', 8, 421, '2026-01-14', 2, NULL, 0, NULL),
(88, 'Товар 88', 21, 586, '2026-01-14', 1, NULL, 0, NULL),
(89, 'Товар 89', 18, 16, '2026-01-14', 2, NULL, 0, NULL),
(90, 'Товар 90', 22, 257, '2026-01-14', 1, NULL, 0, NULL),
(91, 'Товар 91', 6, 487, '2026-01-14', 2, NULL, 0, NULL),
(92, 'Товар 92', 29, 79, '2026-01-14', 1, NULL, 0, NULL),
(93, 'Товар 93', 18, 685, '2026-01-14', 2, NULL, 0, NULL),
(94, 'Товар 94', 19, 218, '2026-01-14', 1, NULL, 0, NULL),
(95, 'Товар 95', 9, 820, '2026-01-14', 2, NULL, 0, NULL),
(96, 'Товар 96', 26, 334, '2026-01-14', 1, NULL, 0, NULL),
(97, 'Товар 97', 5, 922, '2026-01-14', 2, NULL, 0, NULL),
(98, 'Товар 98', 11, 250, '2026-01-14', 1, NULL, 0, NULL),
(99, 'Товар 99', 12, 461, '2026-01-14', 2, NULL, 0, NULL),
(100, 'Товар 100', 6, 425, '2026-01-14', 1, NULL, 0, NULL),
(101, 'Товар 101', 28, 620, '2026-01-14', 2, NULL, 0, NULL),
(102, 'Товар 102', 10, 224, '2026-01-14', 1, NULL, 0, NULL),
(103, 'Товар 103', 13, 36, '2026-01-14', 2, NULL, 0, NULL),
(104, 'Товар 104', 6, 787, '2026-01-14', 1, NULL, 0, NULL),
(105, 'Товар 105', 19, 692, '2026-01-14', 2, NULL, 0, NULL),
(106, 'Товар 106', 7, 990, '2026-01-14', 1, NULL, 0, NULL),
(107, 'Товар 107', 6, 639, '2026-01-14', 2, NULL, 0, NULL),
(108, 'Товар 108', 22, 60, '2026-01-14', 1, NULL, 0, NULL),
(109, 'Товар 109', 22, 726, '2026-01-14', 2, NULL, 0, NULL),
(110, 'Товар 110', 8, 191, '2026-01-14', 1, NULL, 0, NULL),
(111, 'Товар 111', 6, 147, '2026-01-14', 2, NULL, 0, NULL),
(112, 'Товар 112', 12, 375, '2026-01-14', 1, NULL, 0, NULL),
(113, 'Товар 113', 6, 206, '2026-01-14', 2, NULL, 0, NULL),
(114, 'Товар 114', 12, 77, '2026-01-14', 1, NULL, 0, NULL),
(115, 'Товар 115', 8, 146, '2026-01-14', 2, NULL, 0, NULL),
(116, 'Товар 116', 18, 26, '2026-01-14', 1, NULL, 0, NULL),
(117, 'Товар 117', 15, 376, '2026-01-14', 2, NULL, 0, NULL),
(118, 'Товар 118', 17, 69, '2026-01-14', 1, NULL, 0, NULL),
(119, 'Товар 119', 6, 73, '2026-01-14', 2, NULL, 0, NULL),
(120, 'Товар 120', 21, 339, '2026-01-14', 1, NULL, 0, NULL),
(121, 'Товар 121', 17, 52, '2026-01-14', 2, NULL, 0, NULL),
(122, 'Товар 122', 15, 352, '2026-01-14', 1, NULL, 0, NULL),
(123, 'Товар 123', 8, 701, '2026-01-14', 2, NULL, 0, NULL),
(124, 'Товар 124', 17, 803, '2026-01-14', 1, NULL, 0, NULL),
(125, 'Товар 125', 9, 590, '2026-01-14', 2, NULL, 0, NULL),
(126, 'Товар 126', 21, 458, '2026-01-14', 1, NULL, 0, NULL),
(127, 'Товар 127', 28, 256, '2026-01-14', 2, NULL, 0, NULL),
(128, 'Товар 128', 8, 344, '2026-01-14', 1, NULL, 0, NULL),
(129, 'Товар 129', 18, 720, '2026-01-14', 2, NULL, 0, NULL),
(130, 'Товар 130', 7, 551, '2026-01-14', 1, NULL, 0, NULL),
(131, 'Товар 131', 26, 546, '2026-01-14', 2, NULL, 0, NULL),
(132, 'Товар 132', 30, 654, '2026-01-14', 1, NULL, 0, NULL),
(133, 'Товар 133', 29, 155, '2026-01-14', 2, NULL, 0, NULL),
(134, 'Товар 134', 27, 324, '2026-01-14', 1, NULL, 0, NULL),
(135, 'Товар 135', 12, 116, '2026-01-14', 2, NULL, 0, NULL),
(136, 'Товар 136', 30, 532, '2026-01-14', 1, NULL, 0, NULL),
(137, 'Товар 137', 10, 506, '2026-01-14', 2, NULL, 0, NULL),
(138, 'Товар 138', 24, 474, '2026-01-14', 1, NULL, 0, NULL),
(139, 'Товар 139', 9, 888, '2026-01-14', 2, NULL, 0, NULL),
(140, 'Товар 140', 16, 129, '2026-01-14', 1, NULL, 0, NULL),
(141, 'Товар 141', 29, 440, '2026-01-14', 2, NULL, 0, NULL),
(142, 'Товар 142', 16, 16, '2026-01-14', 1, NULL, 0, NULL),
(143, 'Товар 143', 26, 463, '2026-01-14', 2, NULL, 0, NULL),
(144, 'Товар 144', 28, 850, '2026-01-14', 1, NULL, 0, NULL),
(145, 'Товар 145', 6, 315, '2026-01-14', 2, NULL, 0, NULL),
(146, 'Товар 146', 25, 72, '2026-01-14', 1, NULL, 0, NULL),
(147, 'Товар 147', 20, 253, '2026-01-14', 2, NULL, 0, NULL),
(148, 'Товар 148', 11, 929, '2026-01-14', 1, NULL, 0, NULL),
(149, 'Товар 149', 18, 100, '2026-01-14', 2, NULL, 0, NULL),
(150, 'Товар 150', 5, 698, '2026-01-14', 1, NULL, 0, NULL),
(151, 'Товар 151', 19, 813, '2026-01-14', 2, NULL, 0, NULL),
(152, 'Товар 152', 23, 377, '2026-01-14', 1, NULL, 0, NULL),
(153, 'Товар 153', 30, 160, '2026-01-14', 2, NULL, 0, NULL),
(154, 'Товар 154', 10, 375, '2026-01-14', 1, NULL, 0, NULL),
(155, 'Товар 155', 26, 967, '2026-01-14', 2, NULL, 0, NULL),
(156, 'Товар 156', 13, 732, '2026-01-14', 1, NULL, 0, NULL),
(157, 'Товар 157', 10, 268, '2026-01-14', 2, NULL, 0, NULL),
(158, 'Товар 158', 20, 765, '2026-01-14', 1, NULL, 0, NULL),
(159, 'Товар 159', 7, 289, '2026-01-14', 2, NULL, 0, NULL),
(160, 'Товар 160', 6, 270, '2026-01-14', 1, NULL, 0, NULL),
(161, 'Товар 161', 29, 419, '2026-01-14', 2, NULL, 0, NULL),
(162, 'Товар 162', 8, 597, '2026-01-14', 1, NULL, 0, NULL),
(163, 'Товар 163', 5, 527, '2026-01-14', 2, NULL, 0, NULL),
(164, 'Товар 164', 16, 257, '2026-01-14', 1, NULL, 0, NULL),
(165, 'Товар 165', 28, 766, '2026-01-14', 2, NULL, 0, NULL),
(166, 'Товар 166', 22, 802, '2026-01-14', 1, NULL, 0, NULL),
(167, 'Товар 167', 25, 531, '2026-01-14', 2, NULL, 0, NULL),
(168, 'Товар 168', 20, 133, '2026-01-14', 1, NULL, 0, NULL),
(169, 'Товар 169', 6, 221, '2026-01-14', 2, NULL, 0, NULL),
(170, 'Товар 170', 10, 579, '2026-01-14', 1, NULL, 0, NULL),
(171, 'Товар 171', 13, 539, '2026-01-14', 2, NULL, 0, NULL),
(172, 'Товар 172', 25, 981, '2026-01-14', 1, NULL, 0, NULL),
(173, 'Товар 173', 20, 494, '2026-01-14', 2, NULL, 0, NULL),
(174, 'Товар 174', 17, 796, '2026-01-14', 1, NULL, 0, NULL),
(175, 'Товар 175', 10, 155, '2026-01-14', 2, NULL, 0, NULL),
(176, 'Товар 176', 16, 493, '2026-01-14', 1, NULL, 0, NULL),
(177, 'Товар 177', 10, 368, '2026-01-14', 2, NULL, 0, NULL),
(178, 'Товар 178', 29, 166, '2026-01-14', 1, NULL, 0, NULL),
(179, 'Товар 179', 24, 261, '2026-01-14', 2, NULL, 0, NULL),
(180, 'Товар 180', 15, 368, '2026-01-14', 1, NULL, 0, NULL),
(181, 'Товар 181', 10, 751, '2026-01-14', 2, NULL, 0, NULL),
(182, 'Товар 182', 14, 531, '2026-01-14', 1, NULL, 0, NULL),
(183, 'Товар 183', 23, 854, '2026-01-14', 2, NULL, 0, NULL),
(184, 'Товар 184', 16, 679, '2026-01-14', 1, NULL, 0, NULL),
(185, 'Товар 185', 5, 647, '2026-01-14', 2, NULL, 0, NULL),
(186, 'Товар 186', 27, 125, '2026-01-14', 1, NULL, 0, NULL),
(187, 'Товар 187', 5, 418, '2026-01-14', 2, NULL, 0, NULL),
(188, 'Товар 188', 18, 658, '2026-01-14', 1, NULL, 0, NULL),
(189, 'Товар 189', 18, 446, '2026-01-14', 2, NULL, 0, NULL),
(190, 'Товар 190', 21, 953, '2026-01-14', 1, NULL, 0, NULL),
(191, 'Товар 191', 20, 322, '2026-01-14', 2, NULL, 0, NULL),
(192, 'Товар 192', 11, 102, '2026-01-14', 1, NULL, 0, NULL),
(193, 'Товар 193', 29, 592, '2026-01-14', 2, NULL, 0, NULL),
(194, 'Товар 194', 18, 372, '2026-01-14', 1, NULL, 0, NULL),
(195, 'Товар 195', 19, 162, '2026-01-14', 2, NULL, 0, NULL),
(196, 'Товар 196', 6, 562, '2026-01-14', 1, NULL, 0, NULL),
(197, 'Товар 197', 5, 241, '2026-01-14', 2, NULL, 0, NULL),
(198, 'Товар 198', 9, 644, '2026-01-14', 1, NULL, 0, NULL),
(199, 'Товар 199', 13, 413, '2026-01-14', 2, NULL, 0, NULL),
(200, 'Товар 200', 20, 137, '2026-01-14', 1, NULL, 0, NULL),
(201, 'Товар 201', 18, 798, '2026-01-14', 2, NULL, 0, NULL),
(202, 'Товар 202', 5, 531, '2026-01-14', 1, NULL, 0, NULL),
(203, 'Товар 203', 11, 45, '2026-01-14', 2, NULL, 0, NULL),
(204, 'Товар 204', 14, 847, '2026-01-14', 1, NULL, 0, NULL),
(205, 'Товар 205', 27, 229, '2026-01-14', 2, NULL, 0, NULL),
(206, 'Товар 206', 13, 962, '2026-01-14', 1, NULL, 0, NULL),
(207, 'Товар 207', 12, 95, '2026-01-14', 2, NULL, 0, NULL),
(208, 'Товар 208', 16, 334, '2026-01-14', 1, NULL, 0, NULL),
(209, 'Товар 209', 6, 88, '2026-01-14', 2, NULL, 0, NULL),
(210, 'Товар 210', 18, 774, '2026-01-14', 1, NULL, 0, NULL),
(211, 'Товар 211', 22, 787, '2026-01-14', 2, NULL, 0, NULL),
(212, 'Товар 212', 12, 91, '2026-01-14', 1, NULL, 0, NULL),
(213, 'Товар 213', 7, 425, '2026-01-14', 2, NULL, 0, NULL),
(214, 'Товар 214', 22, 810, '2026-01-14', 1, NULL, 0, NULL),
(215, 'Товар 215', 7, 996, '2026-01-14', 2, NULL, 0, NULL),
(216, 'Товар 216', 21, 904, '2026-01-14', 1, NULL, 0, NULL),
(217, 'Товар 217', 25, 580, '2026-01-14', 2, NULL, 0, NULL),
(218, 'Товар 218', 7, 513, '2026-01-14', 1, NULL, 0, NULL),
(219, 'Товар 219', 29, 371, '2026-01-14', 2, NULL, 0, NULL),
(220, 'Товар 220', 13, 23, '2026-01-14', 1, NULL, 0, NULL),
(221, 'Товар 221', 15, 614, '2026-01-14', 2, NULL, 0, NULL),
(222, 'Товар 222', 19, 686, '2026-01-14', 1, NULL, 0, NULL),
(223, 'Товар 223', 24, 980, '2026-01-14', 2, NULL, 0, NULL),
(224, 'Товар 224', 30, 662, '2026-01-14', 1, NULL, 0, NULL),
(225, 'Товар 225', 8, 716, '2026-01-14', 2, NULL, 0, NULL),
(226, 'Товар 226', 15, 433, '2026-01-14', 1, NULL, 0, NULL),
(227, 'Товар 227', 17, 176, '2026-01-14', 2, NULL, 0, NULL),
(228, 'Товар 228', 11, 839, '2026-01-14', 1, NULL, 0, NULL),
(229, 'Товар 229', 14, 834, '2026-01-14', 2, NULL, 0, NULL),
(230, 'Товар 230', 14, 11, '2026-01-14', 1, NULL, 0, NULL),
(231, 'Товар 231', 14, 839, '2026-01-14', 2, NULL, 0, NULL),
(232, 'Товар 232', 18, 152, '2026-01-14', 1, NULL, 0, NULL),
(233, 'Товар 233', 15, 137, '2026-01-14', 2, NULL, 0, NULL),
(234, 'Товар 234', 9, 653, '2026-01-14', 1, NULL, 0, NULL),
(235, 'Товар 235', 7, 248, '2026-01-14', 2, NULL, 0, NULL),
(236, 'Товар 236', 11, 626, '2026-01-14', 1, NULL, 0, NULL),
(237, 'Товар 237', 9, 695, '2026-01-14', 2, NULL, 0, NULL),
(238, 'Товар 238', 19, 151, '2026-01-14', 1, NULL, 0, NULL),
(239, 'Товар 239', 25, 467, '2026-01-14', 2, NULL, 0, NULL),
(240, 'Товар 240', 16, 560, '2026-01-14', 1, NULL, 0, NULL),
(241, 'Товар 241', 18, 36, '2026-01-14', 2, NULL, 0, NULL),
(242, 'Товар 242', 17, 286, '2026-01-14', 1, NULL, 0, NULL),
(243, 'Товар 243', 15, 492, '2026-01-14', 2, NULL, 0, NULL),
(244, 'Товар 244', 18, 507, '2026-01-14', 1, NULL, 0, NULL),
(245, 'Товар 245', 28, 614, '2026-01-14', 2, NULL, 0, NULL),
(246, 'Товар 246', 23, 743, '2026-01-14', 1, NULL, 0, NULL),
(247, 'Товар 247', 13, 212, '2026-01-14', 2, NULL, 0, NULL),
(248, 'Товар 248', 28, 317, '2026-01-14', 1, NULL, 0, NULL),
(249, 'Товар 249', 16, 586, '2026-01-14', 2, NULL, 0, NULL),
(250, 'Товар 250', 24, 365, '2026-01-14', 1, NULL, 0, NULL),
(251, 'Товар 251', 24, 43, '2026-01-14', 2, NULL, 0, NULL),
(252, 'Товар 252', 29, 151, '2026-01-14', 1, NULL, 0, NULL),
(253, 'Товар 253', 7, 718, '2026-01-14', 2, NULL, 0, NULL),
(254, 'Товар 254', 25, 314, '2026-01-14', 1, NULL, 0, NULL),
(255, 'Товар 255', 20, 721, '2026-01-14', 2, NULL, 0, NULL),
(256, 'Товар 256', 18, 763, '2026-01-14', 1, NULL, 0, NULL),
(257, 'Товар 257', 30, 229, '2026-01-14', 2, NULL, 0, NULL),
(258, 'Товар 258', 25, 77, '2026-01-14', 1, NULL, 0, NULL),
(259, 'Товар 259', 27, 625, '2026-01-14', 2, NULL, 0, NULL),
(260, 'Товар 260', 17, 336, '2026-01-14', 1, NULL, 0, NULL),
(261, 'Товар 261', 17, 102, '2026-01-14', 2, NULL, 0, NULL),
(262, 'Товар 262', 27, 325, '2026-01-14', 1, NULL, 0, NULL),
(263, 'Товар 263', 23, 891, '2026-01-14', 2, NULL, 0, NULL),
(264, 'Товар 264', 18, 203, '2026-01-14', 1, NULL, 0, NULL),
(265, 'Товар 265', 24, 644, '2026-01-14', 2, NULL, 0, NULL),
(266, 'Товар 266', 19, 186, '2026-01-14', 1, NULL, 0, NULL),
(267, 'Товар 267', 24, 953, '2026-01-14', 2, NULL, 0, NULL),
(268, 'Товар 268', 13, 483, '2026-01-14', 1, NULL, 0, NULL),
(269, 'Товар 269', 15, 741, '2026-01-14', 2, NULL, 0, NULL),
(270, 'Товар 270', 29, 381, '2026-01-14', 1, NULL, 0, NULL),
(271, 'Товар 271', 24, 972, '2026-01-14', 2, NULL, 0, NULL),
(272, 'Товар 272', 10, 257, '2026-01-14', 1, NULL, 0, NULL),
(273, 'Товар 273', 20, 898, '2026-01-14', 2, NULL, 0, NULL),
(274, 'Товар 274', 28, 259, '2026-01-14', 1, NULL, 0, NULL),
(275, 'Товар 275', 27, 106, '2026-01-14', 2, NULL, 0, NULL),
(276, 'Товар 276', 30, 379, '2026-01-14', 1, NULL, 0, NULL),
(277, 'Товар 277', 22, 758, '2026-01-14', 2, NULL, 0, NULL),
(278, 'Товар 278', 16, 79, '2026-01-14', 1, NULL, 0, NULL),
(279, 'Товар 279', 11, 500, '2026-01-14', 2, NULL, 0, NULL),
(280, 'Товар 280', 15, 261, '2026-01-14', 1, NULL, 0, NULL),
(281, 'Товар 281', 15, 820, '2026-01-14', 2, NULL, 0, NULL),
(282, 'Товар 282', 6, 731, '2026-01-14', 1, NULL, 0, NULL),
(283, 'Товар 283', 18, 456, '2026-01-14', 2, NULL, 0, NULL),
(284, 'Товар 284', 15, 207, '2026-01-14', 1, NULL, 0, NULL),
(285, 'Товар 285', 16, 648, '2026-01-14', 2, NULL, 0, NULL),
(286, 'Товар 286', 15, 371, '2026-01-14', 1, NULL, 0, NULL),
(287, 'Товар 287', 13, 582, '2026-01-14', 2, NULL, 0, NULL),
(288, 'Товар 288', 24, 728, '2026-01-14', 1, NULL, 0, NULL),
(289, 'Товар 289', 30, 230, '2026-01-14', 2, NULL, 0, NULL),
(290, 'Товар 290', 5, 689, '2026-01-14', 1, NULL, 0, NULL),
(291, 'Товар 291', 21, 296, '2026-01-14', 2, NULL, 0, NULL),
(292, 'Товар 292', 20, 222, '2026-01-14', 1, NULL, 0, NULL),
(293, 'Товар 293', 24, 555, '2026-01-14', 2, NULL, 0, NULL),
(294, 'Товар 294', 19, 356, '2026-01-14', 1, NULL, 0, NULL),
(295, 'Товар 295', 19, 914, '2026-01-14', 2, NULL, 0, NULL),
(296, 'Товар 296', 28, 938, '2026-01-14', 1, NULL, 0, NULL),
(297, 'Товар 297', 9, 343, '2026-01-14', 2, NULL, 0, NULL),
(298, 'Товар 298', 24, 883, '2026-01-14', 1, NULL, 0, NULL),
(299, 'Товар 299', 26, 585, '2026-01-14', 2, NULL, 0, NULL),
(300, 'Товар 300', 21, 357, '2026-01-14', 1, NULL, 0, NULL),
(301, '123', 20, 101, '2026-01-14', 2, NULL, 0, NULL),
(302, '123', 27, 43, '2026-01-14', 1, NULL, 0, NULL),
(303, '123', 16, 102, '2026-01-14', 2, NULL, 0, NULL),
(304, '123', 10, 175, '2026-01-14', 1, NULL, 0, NULL),
(305, '123', 24, 756, '2026-01-14', 2, NULL, 0, NULL),
(306, '123', 9, 826, '2026-01-14', 1, NULL, 0, NULL),
(307, '123', 21, 368, '2026-01-14', 2, NULL, 0, NULL),
(308, '123', 12, 130, '2026-01-14', 1, NULL, 0, NULL),
(309, '123', 24, 994, '2026-01-14', 2, NULL, 0, NULL),
(310, '123', 17, 766, '2026-01-14', 1, NULL, 0, NULL),
(311, '123', 19, 945, '2026-01-14', 2, NULL, 0, NULL),
(312, '123', 9, 64, '2026-01-14', 1, NULL, 0, NULL),
(313, '123', 28, 485, '2026-01-14', 2, NULL, 0, NULL),
(314, '123', 15, 284, '2026-01-14', 1, NULL, 0, NULL),
(315, '123', 27, 767, '2026-01-14', 2, NULL, 0, NULL),
(316, '123', 27, 452, '2026-01-14', 1, NULL, 0, NULL),
(317, '123', 28, 163, '2026-01-14', 2, NULL, 0, NULL),
(318, '123', 5, 687, '2026-01-14', 1, NULL, 0, NULL),
(319, '123', 9, 337, '2026-01-14', 2, NULL, 0, NULL),
(320, '123', 30, 258, '2026-01-14', 1, NULL, 0, NULL),
(321, '123', 7, 301, '2026-01-14', 2, NULL, 0, NULL),
(322, '123', 23, 570, '2026-01-14', 1, NULL, 0, NULL),
(323, '123', 10, 594, '2026-01-14', 2, NULL, 0, NULL),
(324, '123', 5, 824, '2026-01-14', 1, NULL, 0, NULL),
(325, '123', 19, 86, '2026-01-14', 2, NULL, 0, NULL),
(326, '123', 20, 146, '2026-01-14', 1, NULL, 0, NULL),
(327, '123', 7, 97, '2026-01-14', 2, NULL, 0, NULL),
(328, '123', 6, 229, '2026-01-14', 1, NULL, 0, NULL),
(329, '123', 21, 431, '2026-01-14', 2, NULL, 0, NULL),
(330, '123', 11, 706, '2026-01-14', 1, NULL, 0, NULL),
(331, '123', 22, 229, '2026-01-14', 2, NULL, 0, NULL),
(332, '123', 26, 541, '2026-01-14', 1, NULL, 0, NULL),
(333, '123', 13, 960, '2026-01-14', 2, NULL, 0, NULL),
(334, '123', 12, 931, '2026-01-14', 1, NULL, 0, NULL),
(335, '123', 12, 811, '2026-01-14', 2, NULL, 0, NULL),
(336, '123', 10, 797, '2026-01-14', 1, NULL, 0, NULL),
(337, '123', 11, 337, '2026-01-14', 2, NULL, 0, NULL),
(338, '123', 9, 505, '2026-01-14', 1, NULL, 0, NULL),
(339, '123', 20, 358, '2026-01-14', 2, NULL, 0, NULL),
(340, '123', 8, 445, '2026-01-14', 1, NULL, 0, NULL),
(341, '123', 10, 224, '2026-01-14', 2, NULL, 0, NULL),
(342, '123', 28, 154, '2026-01-14', 1, NULL, 0, NULL),
(343, '123', 17, 286, '2026-01-14', 2, NULL, 0, NULL),
(344, '123', 9, 120, '2026-01-14', 1, NULL, 0, NULL),
(345, '123', 25, 892, '2026-01-14', 2, NULL, 0, NULL),
(346, '123', 12, 890, '2026-01-14', 1, NULL, 0, NULL),
(347, '123', 22, 74, '2026-01-14', 2, NULL, 0, NULL),
(348, '123', 9, 843, '2026-01-14', 1, NULL, 0, NULL),
(349, '123', 26, 981, '2026-01-14', 2, NULL, 0, NULL),
(350, '123', 21, 483, '2026-01-14', 1, NULL, 0, NULL),
(351, '123', 17, 69, '2026-01-14', 2, NULL, 0, NULL),
(352, '123', 19, 153, '2026-01-14', 1, NULL, 0, NULL),
(353, '123', 13, 825, '2026-01-14', 2, NULL, 0, NULL),
(354, '123', 8, 993, '2026-01-14', 1, NULL, 0, NULL),
(355, '123', 23, 620, '2026-01-14', 2, NULL, 0, NULL),
(356, '123', 14, 672, '2026-01-14', 1, NULL, 0, NULL),
(357, '123', 18, 963, '2026-01-14', 2, NULL, 0, NULL),
(358, '123', 28, 240, '2026-01-14', 1, NULL, 0, NULL),
(359, '123', 20, 540, '2026-01-14', 2, NULL, 0, NULL),
(360, '123', 21, 202, '2026-01-14', 1, NULL, 0, NULL),
(361, '123', 12, 18, '2026-01-14', 2, NULL, 0, NULL),
(362, '123', 11, 621, '2026-01-14', 1, NULL, 0, NULL),
(363, '123', 5, 489, '2026-01-14', 2, NULL, 0, NULL),
(364, '123', 6, 730, '2026-01-14', 1, NULL, 0, NULL),
(365, '123', 6, 683, '2026-01-14', 2, NULL, 0, NULL),
(366, '123', 5, 907, '2026-01-14', 1, NULL, 0, NULL),
(367, '123', 23, 806, '2026-01-14', 2, NULL, 0, NULL),
(368, '123', 28, 42, '2026-01-14', 1, NULL, 0, NULL),
(369, '123', 12, 353, '2026-01-14', 2, NULL, 0, NULL),
(370, '123', 6, 998, '2026-01-14', 1, NULL, 0, NULL),
(371, '123', 29, 859, '2026-01-14', 2, NULL, 0, NULL),
(372, '123', 29, 46, '2026-01-14', 1, NULL, 0, NULL),
(373, '123', 11, 778, '2026-01-14', 2, NULL, 0, NULL),
(374, '123', 18, 901, '2026-01-14', 1, NULL, 0, NULL),
(375, '123', 29, 293, '2026-01-14', 2, NULL, 0, NULL),
(376, '123', 21, 916, '2026-01-14', 1, NULL, 0, NULL),
(377, '123', 11, 228, '2026-01-14', 2, NULL, 0, NULL),
(378, '123', 26, 796, '2026-01-14', 1, NULL, 0, NULL),
(379, '123', 20, 291, '2026-01-14', 2, NULL, 0, NULL),
(380, '123', 22, 685, '2026-01-14', 1, NULL, 0, NULL),
(381, '123', 17, 356, '2026-01-14', 2, NULL, 0, NULL),
(382, '123', 26, 103, '2026-01-14', 1, NULL, 0, NULL),
(383, '123', 24, 626, '2026-01-14', 2, NULL, 0, NULL),
(384, '123', 24, 937, '2026-01-14', 1, NULL, 0, NULL),
(385, '123', 22, 483, '2026-01-14', 2, NULL, 0, NULL),
(386, '123', 11, 604, '2026-01-14', 1, NULL, 0, NULL),
(387, '123', 1957, 943, '2026-01-14', 2, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `items_images`
--

CREATE TABLE `items_images` (
  `id_items_images` int NOT NULL,
  `items_id_items_images` int NOT NULL,
  `image_items_images` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `users_deliver_orders` int DEFAULT NULL,
  `datetime_start_deliver_orders` datetime DEFAULT NULL,
  `datetime_end_deliver_orders` datetime DEFAULT NULL
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
(2, 'Арбуз'),
(3, 'Крутое');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id_roles` int NOT NULL,
  `name_roles` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id_roles`, `name_roles`) VALUES
(1, 'Администратор'),
(2, 'Пользователь'),
(3, 'Доставщик'),
(4, 'Техподдержка');

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
-- Структура таблицы `supports`
--

CREATE TABLE `supports` (
  `id_supports` int NOT NULL,
  `talks_id_supports` int NOT NULL,
  `text_supports` varchar(255) NOT NULL,
  `datetime_supports` datetime NOT NULL,
  `image_supports` varchar(255) DEFAULT NULL,
  `users_write_supports` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `talks`
--

CREATE TABLE `talks` (
  `id_talks` int NOT NULL,
  `users_id_talks` int NOT NULL,
  `users_support_talks` int DEFAULT NULL,
  `is_end_talks` tinyint(1) NOT NULL DEFAULT '0',
  `title_talks` varchar(255) NOT NULL,
  `datetime_accept_support_talks` datetime DEFAULT NULL,
  `datetime_end_user_talks` datetime DEFAULT NULL,
  `datetime_start_user_talks` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `roles_id_users` int NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_users`, `email_users`, `password_users`, `name_users`, `date_create_users`, `avatar_users`, `is_banned_users`, `roles_id_users`) VALUES
(1, 'admin@admin.com', '$2y$12$8LMBMMOS4akH4L7RVMCiGuht366OX4kBwF42ZtFz4q5LOQBJ0MoMi', 'Админ', '2025-11-30', NULL, 0, 1),
(2, 'user@user.com', '$2y$12$.iazHfKUey3WBOZFxhJgReCkDIXLx9zjStcHGXNfOzUhKX9Ddn35q', 'пользователь', '2025-11-30', NULL, 0, 2),
(3, 'test@test.com', '$2y$12$uEnyqUE7pYWjb.nlWsd6O.GZNONfVQB0MoSh5eXi0YMDlMt9FTVlC', 'Тест', '2026-03-17', NULL, 1, 2),
(4, 'del1@g.g', '$2y$12$73pbhz0bIrj0J/DDZhU6AO3VSXrr2ZI8DlcXWyFm6g1qmqOZoQzvi', 'ДоставщикОдин', '2026-04-06', NULL, 0, 3),
(5, 'del2@g.g', '$2y$12$73pbhz0bIrj0J/DDZhU6AO3VSXrr2ZI8DlcXWyFm6g1qmqOZoQzvi', 'ДоставщикДва', '2026-04-06', NULL, 0, 3),
(6, 'support@support.com', '$2y$12$NwKRC2m9kxTbyixtilAlB.0U11evwtkcynPm1AmclIbGdhnp0zabG', 'ПоддрежкаОдин', '2026-05-16', NULL, 0, 4);

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
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id_favorites`),
  ADD KEY `favorites_ibfk_1` (`items_id_favorites`),
  ADD KEY `favorites_ibfk_2` (`users_id_favorites`);

--
-- Индексы таблицы `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id_items`),
  ADD KEY `id_items_type_items` (`items_type_id_items`);

--
-- Индексы таблицы `items_images`
--
ALTER TABLE `items_images`
  ADD PRIMARY KEY (`id_items_images`),
  ADD KEY `items_images_ibfk_1` (`items_id_items_images`);

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
  ADD KEY `orders_ibfk_1` (`status_id_orders`),
  ADD KEY `orders_ibfk_2` (`users_id_orders`),
  ADD KEY `orders_ibfk_3` (`users_deliver_orders`);

--
-- Индексы таблицы `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id_properties`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_roles`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Индексы таблицы `supports`
--
ALTER TABLE `supports`
  ADD PRIMARY KEY (`id_supports`),
  ADD KEY `supports_ibfk_1` (`talks_id_supports`),
  ADD KEY `supports_ibfk_2` (`users_write_supports`);

--
-- Индексы таблицы `talks`
--
ALTER TABLE `talks`
  ADD PRIMARY KEY (`id_talks`),
  ADD KEY `users_id_talks` (`users_id_talks`),
  ADD KEY `users_support_talks` (`users_support_talks`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `login_users` (`email_users`),
  ADD KEY `roles_id_users` (`roles_id_users`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id_attributes` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `baskets`
--
ALTER TABLE `baskets`
  MODIFY `id_baskets` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comments` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id_favorites` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id_items` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=388;

--
-- AUTO_INCREMENT для таблицы `items_images`
--
ALTER TABLE `items_images`
  MODIFY `id_items_images` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_orders` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `properties`
--
ALTER TABLE `properties`
  MODIFY `id_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id_roles` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `supports`
--
ALTER TABLE `supports`
  MODIFY `id_supports` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `talks`
--
ALTER TABLE `talks`
  MODIFY `id_talks` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`items_id_favorites`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`users_id_favorites`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`items_type_id_items`) REFERENCES `items_type` (`id_items_type`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `items_images`
--
ALTER TABLE `items_images`
  ADD CONSTRAINT `items_images_ibfk_1` FOREIGN KEY (`items_id_items_images`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE RESTRICT;

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
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`status_id_orders`) REFERENCES `status` (`id_status`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`users_id_orders`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`users_deliver_orders`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `supports`
--
ALTER TABLE `supports`
  ADD CONSTRAINT `supports_ibfk_1` FOREIGN KEY (`talks_id_supports`) REFERENCES `talks` (`id_talks`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `supports_ibfk_2` FOREIGN KEY (`users_write_supports`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `talks`
--
ALTER TABLE `talks`
  ADD CONSTRAINT `talks_ibfk_1` FOREIGN KEY (`users_id_talks`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `talks_ibfk_2` FOREIGN KEY (`users_support_talks`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roles_id_users`) REFERENCES `roles` (`id_roles`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
