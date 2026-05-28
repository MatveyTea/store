-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Май 29 2026 г., 00:14
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
(5, 4, 'Что-то');

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
  `image_items` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cost_items` int NOT NULL,
  `date_add_items` date NOT NULL,
  `items_type_id_items` int NOT NULL,
  `description_items` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `views_items` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`id_items`, `name_items`, `count_items`, `image_items`, `cost_items`, `date_add_items`, `items_type_id_items`, `description_items`, `views_items`) VALUES
(1, 'Товар 1', 19, NULL, 872, '2026-01-13', 2, NULL, 0),
(2, 'Товар 2', 14, NULL, 682, '2026-01-14', 1, NULL, 0),
(3, 'Товар 3', 15, NULL, 740, '2026-01-14', 2, NULL, 0),
(4, 'Товар 4', 18, NULL, 435, '2026-01-14', 1, NULL, 0),
(5, 'Товар 5', 25, NULL, 366, '2026-01-14', 2, NULL, 0),
(6, 'Товар 6', 15, NULL, 168, '2026-01-14', 1, NULL, 0),
(7, 'Товар 7', 16, NULL, 85, '2026-01-14', 2, NULL, 0),
(8, 'Товар 8', 26, NULL, 921, '2026-01-14', 1, NULL, 0),
(9, 'Товар 9', 26, NULL, 790, '2026-01-14', 2, NULL, 0),
(10, 'Товар 10', 9, NULL, 316, '2026-01-14', 1, NULL, 0),
(11, 'Товар 11', 16, NULL, 164, '2026-01-14', 2, NULL, 0),
(12, 'Товар 12', 9, NULL, 150, '2026-01-14', 1, NULL, 0),
(13, 'Товар 13', 17, NULL, 472, '2026-01-14', 2, NULL, 0),
(14, 'Товар 14', 11, NULL, 461, '2026-01-14', 1, NULL, 0),
(15, 'Товар 15', 13, NULL, 330, '2026-01-14', 2, NULL, 0),
(16, 'Товар 16', 15, NULL, 778, '2026-01-14', 1, NULL, 0),
(17, 'Товар 17', 21, NULL, 448, '2026-01-14', 2, NULL, 0),
(18, 'Товар 18', 14, NULL, 104, '2026-01-14', 1, NULL, 0),
(19, 'Товар 19', 12, NULL, 910, '2026-01-14', 2, NULL, 0),
(20, 'Товар 20', 9, NULL, 860, '2026-01-14', 1, NULL, 0),
(21, 'Товар 21', 25, NULL, 937, '2026-01-14', 2, NULL, 0),
(22, 'Товар 22', 12, NULL, 988, '2026-01-14', 1, NULL, 0),
(23, 'Товар 23', 20, NULL, 495, '2026-01-14', 2, NULL, 0),
(24, 'Товар 24', 27, NULL, 495, '2026-01-14', 1, NULL, 0),
(25, 'Товар 25', 6, NULL, 190, '2026-01-14', 2, NULL, 0),
(26, 'Товар 26', 15, NULL, 38, '2026-01-14', 1, NULL, 0),
(27, 'Товар 27', 21, NULL, 963, '2026-01-14', 2, NULL, 0),
(28, 'Товар 28', 19, NULL, 947, '2026-01-14', 1, NULL, 0),
(29, 'Товар 29', 19, NULL, 764, '2026-01-14', 2, NULL, 0),
(30, 'Товар 30', 6, NULL, 437, '2026-01-14', 1, NULL, 0),
(31, 'Товар 31', 8, NULL, 765, '2026-01-14', 2, NULL, 0),
(32, 'Товар 32', 7, NULL, 938, '2026-01-14', 1, NULL, 0),
(33, 'Товар 33', 19, NULL, 358, '2026-01-14', 2, NULL, 0),
(34, 'Товар 34', 25, NULL, 915, '2026-01-14', 1, NULL, 0),
(35, 'Товар 35', 8, NULL, 470, '2026-01-14', 2, NULL, 0),
(36, 'Товар 36', 25, NULL, 119, '2026-01-14', 1, NULL, 0),
(37, 'Товар 37', 22, NULL, 215, '2026-01-14', 2, NULL, 0),
(38, 'Товар 38', 11, NULL, 754, '2026-01-14', 1, NULL, 0),
(39, 'Товар 39', 6, NULL, 523, '2026-01-14', 2, NULL, 0),
(40, 'Товар 40', 17, NULL, 293, '2026-01-14', 1, NULL, 0),
(41, 'Товар 41', 7, NULL, 243, '2026-01-14', 2, NULL, 0),
(42, 'Товар 42', 21, NULL, 907, '2026-01-14', 1, NULL, 0),
(43, 'Товар 43', 22, NULL, 346, '2026-01-14', 2, NULL, 0),
(44, 'Товар 44', 15, NULL, 817, '2026-01-14', 1, NULL, 0),
(45, 'Товар 45', 21, NULL, 439, '2026-01-14', 2, NULL, 0),
(46, 'Товар 46', 24, NULL, 169, '2026-01-14', 1, NULL, 0),
(47, 'Товар 47', 18, NULL, 659, '2026-01-14', 2, NULL, 0),
(48, 'Товар 48', 17, NULL, 930, '2026-01-14', 1, NULL, 0),
(49, 'Товар 49', 30, NULL, 188, '2026-01-14', 2, NULL, 0),
(50, 'Товар 50', 7, NULL, 742, '2026-01-14', 1, NULL, 0),
(51, 'Товар 51', 18, NULL, 452, '2026-01-14', 2, NULL, 0),
(52, 'Товар 52', 24, NULL, 110, '2026-01-14', 1, NULL, 0),
(53, 'Товар 53', 7, NULL, 125, '2026-01-14', 2, NULL, 0),
(54, 'Товар 54', 17, NULL, 626, '2026-01-14', 1, NULL, 0),
(55, 'Товар 55', 16, NULL, 652, '2026-01-14', 2, NULL, 0),
(56, 'Товар 56', 11, NULL, 633, '2026-01-14', 1, NULL, 0),
(57, 'Товар 57', 13, NULL, 596, '2026-01-14', 2, NULL, 0),
(58, 'Товар 58', 28, NULL, 645, '2026-01-14', 1, NULL, 0),
(59, 'Товар 59', 10, NULL, 399, '2026-01-14', 2, NULL, 0),
(60, 'Товар 60', 12, NULL, 43, '2026-01-14', 1, NULL, 0),
(61, 'Товар 61', 22, NULL, 254, '2026-01-14', 2, NULL, 0),
(62, 'Товар 62', 6, NULL, 196, '2026-01-14', 1, NULL, 0),
(63, 'Товар 63', 19, NULL, 927, '2026-01-14', 2, NULL, 0),
(64, 'Товар 64', 14, NULL, 838, '2026-01-14', 1, NULL, 0),
(65, 'Товар 65', 20, NULL, 205, '2026-01-14', 2, NULL, 0),
(66, 'Товар 66', 13, NULL, 418, '2026-01-14', 1, NULL, 0),
(67, 'Товар 67', 6, NULL, 126, '2026-01-14', 2, NULL, 0),
(68, 'Товар 68', 11, NULL, 684, '2026-01-14', 1, NULL, 0),
(69, 'Товар 69', 30, NULL, 839, '2026-01-14', 2, NULL, 0),
(70, 'Товар 70', 5, NULL, 339, '2026-01-14', 1, NULL, 0),
(71, 'Товар 71', 10, NULL, 560, '2026-01-14', 2, NULL, 0),
(72, 'Товар 72', 20, NULL, 52, '2026-01-14', 1, NULL, 0),
(73, 'Товар 73', 15, NULL, 979, '2026-01-14', 2, NULL, 0),
(74, 'Товар 74', 29, NULL, 517, '2026-01-14', 1, NULL, 0),
(75, 'Товар 75', 17, NULL, 413, '2026-01-14', 2, NULL, 0),
(76, 'Товар 76', 11, NULL, 270, '2026-01-14', 1, NULL, 0),
(77, 'Товар 77', 24, NULL, 290, '2026-01-14', 2, NULL, 0),
(78, 'Товар 78', 9, NULL, 260, '2026-01-14', 1, NULL, 0),
(79, 'Товар 79', 13, NULL, 901, '2026-01-14', 2, NULL, 0),
(80, 'Товар 80', 6, NULL, 877, '2026-01-14', 1, NULL, 0),
(81, 'Товар 81', 24, NULL, 470, '2026-01-14', 2, NULL, 0),
(82, 'Товар 82', 15, NULL, 694, '2026-01-14', 1, NULL, 0),
(83, 'Товар 83', 30, NULL, 103, '2026-01-14', 2, NULL, 0),
(84, 'Товар 84', 22, NULL, 797, '2026-01-14', 1, NULL, 0),
(85, 'Товар 85', 25, NULL, 332, '2026-01-14', 2, NULL, 0),
(86, 'Товар 86', 27, NULL, 657, '2026-01-14', 1, NULL, 0),
(87, 'Товар 87', 8, NULL, 421, '2026-01-14', 2, NULL, 0),
(88, 'Товар 88', 21, NULL, 586, '2026-01-14', 1, NULL, 0),
(89, 'Товар 89', 18, NULL, 16, '2026-01-14', 2, NULL, 0),
(90, 'Товар 90', 22, NULL, 257, '2026-01-14', 1, NULL, 0),
(91, 'Товар 91', 6, NULL, 487, '2026-01-14', 2, NULL, 0),
(92, 'Товар 92', 29, NULL, 79, '2026-01-14', 1, NULL, 0),
(93, 'Товар 93', 18, NULL, 685, '2026-01-14', 2, NULL, 0),
(94, 'Товар 94', 19, NULL, 218, '2026-01-14', 1, NULL, 0),
(95, 'Товар 95', 9, NULL, 820, '2026-01-14', 2, NULL, 0),
(96, 'Товар 96', 26, NULL, 334, '2026-01-14', 1, NULL, 0),
(97, 'Товар 97', 5, NULL, 922, '2026-01-14', 2, NULL, 0),
(98, 'Товар 98', 11, NULL, 250, '2026-01-14', 1, NULL, 0),
(99, 'Товар 99', 12, NULL, 461, '2026-01-14', 2, NULL, 0),
(100, 'Товар 100', 6, NULL, 425, '2026-01-14', 1, NULL, 0),
(101, 'Товар 101', 28, NULL, 620, '2026-01-14', 2, NULL, 0),
(102, 'Товар 102', 10, NULL, 224, '2026-01-14', 1, NULL, 0),
(103, 'Товар 103', 13, NULL, 36, '2026-01-14', 2, NULL, 0),
(104, 'Товар 104', 6, NULL, 787, '2026-01-14', 1, NULL, 0),
(105, 'Товар 105', 19, NULL, 692, '2026-01-14', 2, NULL, 0),
(106, 'Товар 106', 7, NULL, 990, '2026-01-14', 1, NULL, 0),
(107, 'Товар 107', 6, NULL, 639, '2026-01-14', 2, NULL, 0),
(108, 'Товар 108', 22, NULL, 60, '2026-01-14', 1, NULL, 0),
(109, 'Товар 109', 22, NULL, 726, '2026-01-14', 2, NULL, 0),
(110, 'Товар 110', 8, NULL, 191, '2026-01-14', 1, NULL, 0),
(111, 'Товар 111', 6, NULL, 147, '2026-01-14', 2, NULL, 0),
(112, 'Товар 112', 12, NULL, 375, '2026-01-14', 1, NULL, 0),
(113, 'Товар 113', 6, NULL, 206, '2026-01-14', 2, NULL, 0),
(114, 'Товар 114', 12, NULL, 77, '2026-01-14', 1, NULL, 0),
(115, 'Товар 115', 8, NULL, 146, '2026-01-14', 2, NULL, 0),
(116, 'Товар 116', 18, NULL, 26, '2026-01-14', 1, NULL, 0),
(117, 'Товар 117', 15, NULL, 376, '2026-01-14', 2, NULL, 0),
(118, 'Товар 118', 17, NULL, 69, '2026-01-14', 1, NULL, 0),
(119, 'Товар 119', 6, NULL, 73, '2026-01-14', 2, NULL, 0),
(120, 'Товар 120', 21, NULL, 339, '2026-01-14', 1, NULL, 0),
(121, 'Товар 121', 17, NULL, 52, '2026-01-14', 2, NULL, 0),
(122, 'Товар 122', 15, NULL, 352, '2026-01-14', 1, NULL, 0),
(123, 'Товар 123', 8, NULL, 701, '2026-01-14', 2, NULL, 0),
(124, 'Товар 124', 17, NULL, 803, '2026-01-14', 1, NULL, 0),
(125, 'Товар 125', 9, NULL, 590, '2026-01-14', 2, NULL, 0),
(126, 'Товар 126', 21, NULL, 458, '2026-01-14', 1, NULL, 0),
(127, 'Товар 127', 28, NULL, 256, '2026-01-14', 2, NULL, 0),
(128, 'Товар 128', 8, NULL, 344, '2026-01-14', 1, NULL, 0),
(129, 'Товар 129', 18, NULL, 720, '2026-01-14', 2, NULL, 0),
(130, 'Товар 130', 7, NULL, 551, '2026-01-14', 1, NULL, 0),
(131, 'Товар 131', 26, NULL, 546, '2026-01-14', 2, NULL, 0),
(132, 'Товар 132', 30, NULL, 654, '2026-01-14', 1, NULL, 0),
(133, 'Товар 133', 29, NULL, 155, '2026-01-14', 2, NULL, 0),
(134, 'Товар 134', 27, NULL, 324, '2026-01-14', 1, NULL, 0),
(135, 'Товар 135', 12, NULL, 116, '2026-01-14', 2, NULL, 0),
(136, 'Товар 136', 30, NULL, 532, '2026-01-14', 1, NULL, 0),
(137, 'Товар 137', 10, NULL, 506, '2026-01-14', 2, NULL, 0),
(138, 'Товар 138', 24, NULL, 474, '2026-01-14', 1, NULL, 0),
(139, 'Товар 139', 9, NULL, 888, '2026-01-14', 2, NULL, 0),
(140, 'Товар 140', 16, NULL, 129, '2026-01-14', 1, NULL, 0),
(141, 'Товар 141', 29, NULL, 440, '2026-01-14', 2, NULL, 0),
(142, 'Товар 142', 16, NULL, 16, '2026-01-14', 1, NULL, 0),
(143, 'Товар 143', 26, NULL, 463, '2026-01-14', 2, NULL, 0),
(144, 'Товар 144', 28, NULL, 850, '2026-01-14', 1, NULL, 0),
(145, 'Товар 145', 6, NULL, 315, '2026-01-14', 2, NULL, 0),
(146, 'Товар 146', 25, NULL, 72, '2026-01-14', 1, NULL, 0),
(147, 'Товар 147', 20, NULL, 253, '2026-01-14', 2, NULL, 0),
(148, 'Товар 148', 11, NULL, 929, '2026-01-14', 1, NULL, 0),
(149, 'Товар 149', 18, NULL, 100, '2026-01-14', 2, NULL, 0),
(150, 'Товар 150', 5, NULL, 698, '2026-01-14', 1, NULL, 0),
(151, 'Товар 151', 19, NULL, 813, '2026-01-14', 2, NULL, 0),
(152, 'Товар 152', 23, NULL, 377, '2026-01-14', 1, NULL, 0),
(153, 'Товар 153', 30, NULL, 160, '2026-01-14', 2, NULL, 0),
(154, 'Товар 154', 10, NULL, 375, '2026-01-14', 1, NULL, 0),
(155, 'Товар 155', 26, NULL, 967, '2026-01-14', 2, NULL, 0),
(156, 'Товар 156', 13, NULL, 732, '2026-01-14', 1, NULL, 0),
(157, 'Товар 157', 10, NULL, 268, '2026-01-14', 2, NULL, 0),
(158, 'Товар 158', 20, NULL, 765, '2026-01-14', 1, NULL, 0),
(159, 'Товар 159', 7, NULL, 289, '2026-01-14', 2, NULL, 0),
(160, 'Товар 160', 6, NULL, 270, '2026-01-14', 1, NULL, 0),
(161, 'Товар 161', 29, NULL, 419, '2026-01-14', 2, NULL, 0),
(162, 'Товар 162', 8, NULL, 597, '2026-01-14', 1, NULL, 0),
(163, 'Товар 163', 5, NULL, 527, '2026-01-14', 2, NULL, 0),
(164, 'Товар 164', 16, NULL, 257, '2026-01-14', 1, NULL, 0),
(165, 'Товар 165', 28, NULL, 766, '2026-01-14', 2, NULL, 0),
(166, 'Товар 166', 22, NULL, 802, '2026-01-14', 1, NULL, 0),
(167, 'Товар 167', 25, NULL, 531, '2026-01-14', 2, NULL, 0),
(168, 'Товар 168', 20, NULL, 133, '2026-01-14', 1, NULL, 0),
(169, 'Товар 169', 6, NULL, 221, '2026-01-14', 2, NULL, 0),
(170, 'Товар 170', 10, NULL, 579, '2026-01-14', 1, NULL, 0),
(171, 'Товар 171', 13, NULL, 539, '2026-01-14', 2, NULL, 0),
(172, 'Товар 172', 25, NULL, 981, '2026-01-14', 1, NULL, 0),
(173, 'Товар 173', 20, NULL, 494, '2026-01-14', 2, NULL, 0),
(174, 'Товар 174', 17, NULL, 796, '2026-01-14', 1, NULL, 0),
(175, 'Товар 175', 10, NULL, 155, '2026-01-14', 2, NULL, 0),
(176, 'Товар 176', 16, NULL, 493, '2026-01-14', 1, NULL, 0),
(177, 'Товар 177', 10, NULL, 368, '2026-01-14', 2, NULL, 0),
(178, 'Товар 178', 29, NULL, 166, '2026-01-14', 1, NULL, 0),
(179, 'Товар 179', 24, NULL, 261, '2026-01-14', 2, NULL, 0),
(180, 'Товар 180', 15, NULL, 368, '2026-01-14', 1, NULL, 0),
(181, 'Товар 181', 10, NULL, 751, '2026-01-14', 2, NULL, 0),
(182, 'Товар 182', 14, NULL, 531, '2026-01-14', 1, NULL, 0),
(183, 'Товар 183', 23, NULL, 854, '2026-01-14', 2, NULL, 0),
(184, 'Товар 184', 16, NULL, 679, '2026-01-14', 1, NULL, 0),
(185, 'Товар 185', 5, NULL, 647, '2026-01-14', 2, NULL, 0),
(186, 'Товар 186', 27, NULL, 125, '2026-01-14', 1, NULL, 0),
(187, 'Товар 187', 5, NULL, 418, '2026-01-14', 2, NULL, 0),
(188, 'Товар 188', 18, NULL, 658, '2026-01-14', 1, NULL, 0),
(189, 'Товар 189', 18, NULL, 446, '2026-01-14', 2, NULL, 0),
(190, 'Товар 190', 21, NULL, 953, '2026-01-14', 1, NULL, 0),
(191, 'Товар 191', 20, NULL, 322, '2026-01-14', 2, NULL, 0),
(192, 'Товар 192', 11, NULL, 102, '2026-01-14', 1, NULL, 0),
(193, 'Товар 193', 29, NULL, 592, '2026-01-14', 2, NULL, 0),
(194, 'Товар 194', 18, NULL, 372, '2026-01-14', 1, NULL, 0),
(195, 'Товар 195', 19, NULL, 162, '2026-01-14', 2, NULL, 0),
(196, 'Товар 196', 6, NULL, 562, '2026-01-14', 1, NULL, 0),
(197, 'Товар 197', 5, NULL, 241, '2026-01-14', 2, NULL, 0),
(198, 'Товар 198', 9, NULL, 644, '2026-01-14', 1, NULL, 0),
(199, 'Товар 199', 13, NULL, 413, '2026-01-14', 2, NULL, 0),
(200, 'Товар 200', 20, NULL, 137, '2026-01-14', 1, NULL, 0),
(201, 'Товар 201', 18, NULL, 798, '2026-01-14', 2, NULL, 0),
(202, 'Товар 202', 5, NULL, 531, '2026-01-14', 1, NULL, 0),
(203, 'Товар 203', 11, NULL, 45, '2026-01-14', 2, NULL, 0),
(204, 'Товар 204', 14, NULL, 847, '2026-01-14', 1, NULL, 0),
(205, 'Товар 205', 27, NULL, 229, '2026-01-14', 2, NULL, 0),
(206, 'Товар 206', 13, NULL, 962, '2026-01-14', 1, NULL, 0),
(207, 'Товар 207', 12, NULL, 95, '2026-01-14', 2, NULL, 0),
(208, 'Товар 208', 16, NULL, 334, '2026-01-14', 1, NULL, 0),
(209, 'Товар 209', 6, NULL, 88, '2026-01-14', 2, NULL, 0),
(210, 'Товар 210', 18, NULL, 774, '2026-01-14', 1, NULL, 0),
(211, 'Товар 211', 22, NULL, 787, '2026-01-14', 2, NULL, 0),
(212, 'Товар 212', 12, NULL, 91, '2026-01-14', 1, NULL, 0),
(213, 'Товар 213', 7, NULL, 425, '2026-01-14', 2, NULL, 0),
(214, 'Товар 214', 22, NULL, 810, '2026-01-14', 1, NULL, 0),
(215, 'Товар 215', 7, NULL, 996, '2026-01-14', 2, NULL, 0),
(216, 'Товар 216', 21, NULL, 904, '2026-01-14', 1, NULL, 0),
(217, 'Товар 217', 25, NULL, 580, '2026-01-14', 2, NULL, 0),
(218, 'Товар 218', 7, NULL, 513, '2026-01-14', 1, NULL, 0),
(219, 'Товар 219', 29, NULL, 371, '2026-01-14', 2, NULL, 0),
(220, 'Товар 220', 13, NULL, 23, '2026-01-14', 1, NULL, 0),
(221, 'Товар 221', 15, NULL, 614, '2026-01-14', 2, NULL, 0),
(222, 'Товар 222', 19, NULL, 686, '2026-01-14', 1, NULL, 0),
(223, 'Товар 223', 24, NULL, 980, '2026-01-14', 2, NULL, 0),
(224, 'Товар 224', 30, NULL, 662, '2026-01-14', 1, NULL, 0),
(225, 'Товар 225', 8, NULL, 716, '2026-01-14', 2, NULL, 0),
(226, 'Товар 226', 15, NULL, 433, '2026-01-14', 1, NULL, 0),
(227, 'Товар 227', 17, NULL, 176, '2026-01-14', 2, NULL, 0),
(228, 'Товар 228', 11, NULL, 839, '2026-01-14', 1, NULL, 0),
(229, 'Товар 229', 14, NULL, 834, '2026-01-14', 2, NULL, 0),
(230, 'Товар 230', 14, NULL, 11, '2026-01-14', 1, NULL, 0),
(231, 'Товар 231', 14, NULL, 839, '2026-01-14', 2, NULL, 0),
(232, 'Товар 232', 18, NULL, 152, '2026-01-14', 1, NULL, 0),
(233, 'Товар 233', 15, NULL, 137, '2026-01-14', 2, NULL, 0),
(234, 'Товар 234', 9, NULL, 653, '2026-01-14', 1, NULL, 0),
(235, 'Товар 235', 7, NULL, 248, '2026-01-14', 2, NULL, 0),
(236, 'Товар 236', 11, NULL, 626, '2026-01-14', 1, NULL, 0),
(237, 'Товар 237', 9, NULL, 695, '2026-01-14', 2, NULL, 0),
(238, 'Товар 238', 19, NULL, 151, '2026-01-14', 1, NULL, 0),
(239, 'Товар 239', 25, NULL, 467, '2026-01-14', 2, NULL, 0),
(240, 'Товар 240', 16, NULL, 560, '2026-01-14', 1, NULL, 0),
(241, 'Товар 241', 18, NULL, 36, '2026-01-14', 2, NULL, 0),
(242, 'Товар 242', 17, NULL, 286, '2026-01-14', 1, NULL, 0),
(243, 'Товар 243', 15, NULL, 492, '2026-01-14', 2, NULL, 0),
(244, 'Товар 244', 18, NULL, 507, '2026-01-14', 1, NULL, 0),
(245, 'Товар 245', 28, NULL, 614, '2026-01-14', 2, NULL, 0),
(246, 'Товар 246', 23, NULL, 743, '2026-01-14', 1, NULL, 0),
(247, 'Товар 247', 13, NULL, 212, '2026-01-14', 2, NULL, 0),
(248, 'Товар 248', 28, NULL, 317, '2026-01-14', 1, NULL, 0),
(249, 'Товар 249', 16, NULL, 586, '2026-01-14', 2, NULL, 0),
(250, 'Товар 250', 24, NULL, 365, '2026-01-14', 1, NULL, 0),
(251, 'Товар 251', 24, NULL, 43, '2026-01-14', 2, NULL, 0),
(252, 'Товар 252', 29, NULL, 151, '2026-01-14', 1, NULL, 0),
(253, 'Товар 253', 7, NULL, 718, '2026-01-14', 2, NULL, 0),
(254, 'Товар 254', 25, NULL, 314, '2026-01-14', 1, NULL, 0),
(255, 'Товар 255', 20, NULL, 721, '2026-01-14', 2, NULL, 0),
(256, 'Товар 256', 18, NULL, 763, '2026-01-14', 1, NULL, 0),
(257, 'Товар 257', 30, NULL, 229, '2026-01-14', 2, NULL, 0),
(258, 'Товар 258', 25, NULL, 77, '2026-01-14', 1, NULL, 0),
(259, 'Товар 259', 27, NULL, 625, '2026-01-14', 2, NULL, 0),
(260, 'Товар 260', 17, NULL, 336, '2026-01-14', 1, NULL, 0),
(261, 'Товар 261', 17, NULL, 102, '2026-01-14', 2, NULL, 0),
(262, 'Товар 262', 27, NULL, 325, '2026-01-14', 1, NULL, 0),
(263, 'Товар 263', 23, NULL, 891, '2026-01-14', 2, NULL, 0),
(264, 'Товар 264', 18, NULL, 203, '2026-01-14', 1, NULL, 0),
(265, 'Товар 265', 24, NULL, 644, '2026-01-14', 2, NULL, 0),
(266, 'Товар 266', 19, NULL, 186, '2026-01-14', 1, NULL, 0),
(267, 'Товар 267', 24, NULL, 953, '2026-01-14', 2, NULL, 0),
(268, 'Товар 268', 13, NULL, 483, '2026-01-14', 1, NULL, 0),
(269, 'Товар 269', 15, NULL, 741, '2026-01-14', 2, NULL, 0),
(270, 'Товар 270', 29, NULL, 381, '2026-01-14', 1, NULL, 0),
(271, 'Товар 271', 24, NULL, 972, '2026-01-14', 2, NULL, 0),
(272, 'Товар 272', 10, NULL, 257, '2026-01-14', 1, NULL, 0),
(273, 'Товар 273', 20, NULL, 898, '2026-01-14', 2, NULL, 0),
(274, 'Товар 274', 28, NULL, 259, '2026-01-14', 1, NULL, 0),
(275, 'Товар 275', 27, NULL, 106, '2026-01-14', 2, NULL, 0),
(276, 'Товар 276', 30, NULL, 379, '2026-01-14', 1, NULL, 0),
(277, 'Товар 277', 22, NULL, 758, '2026-01-14', 2, NULL, 0),
(278, 'Товар 278', 16, NULL, 79, '2026-01-14', 1, NULL, 0),
(279, 'Товар 279', 11, NULL, 500, '2026-01-14', 2, NULL, 0),
(280, 'Товар 280', 15, NULL, 261, '2026-01-14', 1, NULL, 0),
(281, 'Товар 281', 15, NULL, 820, '2026-01-14', 2, NULL, 0),
(282, 'Товар 282', 6, NULL, 731, '2026-01-14', 1, NULL, 0),
(283, 'Товар 283', 18, NULL, 456, '2026-01-14', 2, NULL, 0),
(284, 'Товар 284', 15, NULL, 207, '2026-01-14', 1, NULL, 0),
(285, 'Товар 285', 16, NULL, 648, '2026-01-14', 2, NULL, 0),
(286, 'Товар 286', 15, NULL, 371, '2026-01-14', 1, NULL, 0),
(287, 'Товар 287', 13, NULL, 582, '2026-01-14', 2, NULL, 0),
(288, 'Товар 288', 24, NULL, 728, '2026-01-14', 1, NULL, 0),
(289, 'Товар 289', 30, NULL, 230, '2026-01-14', 2, NULL, 0),
(290, 'Товар 290', 5, NULL, 689, '2026-01-14', 1, NULL, 0),
(291, 'Товар 291', 21, NULL, 296, '2026-01-14', 2, NULL, 0),
(292, 'Товар 292', 20, NULL, 222, '2026-01-14', 1, NULL, 0),
(293, 'Товар 293', 24, NULL, 555, '2026-01-14', 2, NULL, 0),
(294, 'Товар 294', 19, NULL, 356, '2026-01-14', 1, NULL, 0),
(295, 'Товар 295', 19, NULL, 914, '2026-01-14', 2, NULL, 0),
(296, 'Товар 296', 28, NULL, 938, '2026-01-14', 1, NULL, 0),
(297, 'Товар 297', 9, NULL, 343, '2026-01-14', 2, NULL, 0),
(298, 'Товар 298', 24, NULL, 883, '2026-01-14', 1, NULL, 0),
(299, 'Товар 299', 26, NULL, 585, '2026-01-14', 2, NULL, 0),
(300, 'Товар 300', 21, NULL, 357, '2026-01-14', 1, NULL, 0),
(301, '123', 20, NULL, 101, '2026-01-14', 2, NULL, 0),
(302, '123', 27, NULL, 43, '2026-01-14', 1, NULL, 0),
(303, '123', 16, NULL, 102, '2026-01-14', 2, NULL, 0),
(304, '123', 10, NULL, 175, '2026-01-14', 1, NULL, 0),
(305, '123', 24, NULL, 756, '2026-01-14', 2, NULL, 0),
(306, '123', 9, NULL, 826, '2026-01-14', 1, NULL, 0),
(307, '123', 21, NULL, 368, '2026-01-14', 2, NULL, 0),
(308, '123', 12, NULL, 130, '2026-01-14', 1, NULL, 0),
(309, '123', 24, NULL, 994, '2026-01-14', 2, NULL, 0),
(310, '123', 17, NULL, 766, '2026-01-14', 1, NULL, 0),
(311, '123', 19, NULL, 945, '2026-01-14', 2, NULL, 0),
(312, '123', 9, NULL, 64, '2026-01-14', 1, NULL, 0),
(313, '123', 28, NULL, 485, '2026-01-14', 2, NULL, 0),
(314, '123', 15, NULL, 284, '2026-01-14', 1, NULL, 0),
(315, '123', 27, NULL, 767, '2026-01-14', 2, NULL, 0),
(316, '123', 27, NULL, 452, '2026-01-14', 1, NULL, 0),
(317, '123', 28, NULL, 163, '2026-01-14', 2, NULL, 0),
(318, '123', 5, NULL, 687, '2026-01-14', 1, NULL, 0),
(319, '123', 9, NULL, 337, '2026-01-14', 2, NULL, 0),
(320, '123', 30, NULL, 258, '2026-01-14', 1, NULL, 0),
(321, '123', 7, NULL, 301, '2026-01-14', 2, NULL, 0),
(322, '123', 23, NULL, 570, '2026-01-14', 1, NULL, 0),
(323, '123', 10, NULL, 594, '2026-01-14', 2, NULL, 0),
(324, '123', 5, NULL, 824, '2026-01-14', 1, NULL, 0),
(325, '123', 19, NULL, 86, '2026-01-14', 2, NULL, 0),
(326, '123', 20, NULL, 146, '2026-01-14', 1, NULL, 0),
(327, '123', 7, NULL, 97, '2026-01-14', 2, NULL, 0),
(328, '123', 6, NULL, 229, '2026-01-14', 1, NULL, 0),
(329, '123', 21, NULL, 431, '2026-01-14', 2, NULL, 0),
(330, '123', 11, NULL, 706, '2026-01-14', 1, NULL, 0),
(331, '123', 22, NULL, 229, '2026-01-14', 2, NULL, 0),
(332, '123', 26, NULL, 541, '2026-01-14', 1, NULL, 0),
(333, '123', 13, NULL, 960, '2026-01-14', 2, NULL, 0),
(334, '123', 12, NULL, 931, '2026-01-14', 1, NULL, 0),
(335, '123', 12, NULL, 811, '2026-01-14', 2, NULL, 0),
(336, '123', 10, NULL, 797, '2026-01-14', 1, NULL, 0),
(337, '123', 11, NULL, 337, '2026-01-14', 2, NULL, 0),
(338, '123', 9, NULL, 505, '2026-01-14', 1, NULL, 0),
(339, '123', 20, NULL, 358, '2026-01-14', 2, NULL, 0),
(340, '123', 8, NULL, 445, '2026-01-14', 1, NULL, 0),
(341, '123', 10, NULL, 224, '2026-01-14', 2, NULL, 0),
(342, '123', 28, NULL, 154, '2026-01-14', 1, NULL, 0),
(343, '123', 17, NULL, 286, '2026-01-14', 2, NULL, 0),
(344, '123', 9, NULL, 120, '2026-01-14', 1, NULL, 0),
(345, '123', 25, NULL, 892, '2026-01-14', 2, NULL, 0),
(346, '123', 12, NULL, 890, '2026-01-14', 1, NULL, 0),
(347, '123', 22, NULL, 74, '2026-01-14', 2, NULL, 0),
(348, '123', 9, NULL, 843, '2026-01-14', 1, NULL, 0),
(349, '123', 26, NULL, 981, '2026-01-14', 2, NULL, 0),
(350, '123', 21, NULL, 483, '2026-01-14', 1, NULL, 0),
(351, '123', 17, NULL, 69, '2026-01-14', 2, NULL, 0),
(352, '123', 19, NULL, 153, '2026-01-14', 1, NULL, 0),
(353, '123', 13, NULL, 825, '2026-01-14', 2, NULL, 0),
(354, '123', 8, NULL, 993, '2026-01-14', 1, NULL, 0),
(355, '123', 23, NULL, 620, '2026-01-14', 2, NULL, 0),
(356, '123', 14, NULL, 672, '2026-01-14', 1, NULL, 0),
(357, '123', 18, NULL, 963, '2026-01-14', 2, NULL, 0),
(358, '123', 28, NULL, 240, '2026-01-14', 1, NULL, 0),
(359, '123', 20, NULL, 540, '2026-01-14', 2, NULL, 0),
(360, '123', 21, NULL, 202, '2026-01-14', 1, NULL, 0),
(361, '123', 12, NULL, 18, '2026-01-14', 2, NULL, 0),
(362, '123', 11, NULL, 621, '2026-01-14', 1, NULL, 0),
(363, '123', 5, NULL, 489, '2026-01-14', 2, NULL, 0),
(364, '123', 6, NULL, 730, '2026-01-14', 1, NULL, 0),
(365, '123', 6, NULL, 683, '2026-01-14', 2, NULL, 0),
(366, '123', 5, NULL, 907, '2026-01-14', 1, NULL, 0),
(367, '123', 23, NULL, 806, '2026-01-14', 2, NULL, 0),
(368, '123', 28, NULL, 42, '2026-01-14', 1, NULL, 0),
(369, '123', 12, NULL, 353, '2026-01-14', 2, NULL, 0),
(370, '123', 6, NULL, 998, '2026-01-14', 1, NULL, 0),
(371, '123', 29, NULL, 859, '2026-01-14', 2, NULL, 0),
(372, '123', 29, NULL, 46, '2026-01-14', 1, NULL, 0),
(373, '123', 11, NULL, 778, '2026-01-14', 2, NULL, 0),
(374, '123', 18, NULL, 901, '2026-01-14', 1, NULL, 0),
(375, '123', 29, NULL, 293, '2026-01-14', 2, NULL, 0),
(376, '123', 21, NULL, 916, '2026-01-14', 1, NULL, 0),
(377, '123', 11, NULL, 228, '2026-01-14', 2, NULL, 0),
(378, '123', 26, NULL, 796, '2026-01-14', 1, NULL, 0),
(379, '123', 20, NULL, 291, '2026-01-14', 2, NULL, 0),
(380, '123', 22, NULL, 685, '2026-01-14', 1, NULL, 0),
(381, '123', 17, NULL, 356, '2026-01-14', 2, NULL, 0),
(382, '123', 26, NULL, 103, '2026-01-14', 1, NULL, 0),
(383, '123', 24, NULL, 626, '2026-01-14', 2, NULL, 0),
(384, '123', 24, NULL, 937, '2026-01-14', 1, NULL, 0),
(385, '123', 22, NULL, 483, '2026-01-14', 2, NULL, 0),
(386, '123', 11, NULL, 604, '2026-01-14', 1, NULL, 0),
(387, '123', 19, NULL, 943, '2026-01-14', 2, NULL, 0);

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
(4, 'Крутое');

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
  ADD KEY `items_id_favorites` (`items_id_favorites`),
  ADD KEY `users_id_favorites` (`users_id_favorites`);

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
  ADD KEY `items_id_items_images` (`items_id_items_images`);

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
  MODIFY `id_attributes` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `baskets`
--
ALTER TABLE `baskets`
  MODIFY `id_baskets` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comments` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id_favorites` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id_items` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=419;

--
-- AUTO_INCREMENT для таблицы `items_images`
--
ALTER TABLE `items_images`
  MODIFY `id_items_images` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `items_properties`
--
ALTER TABLE `items_properties`
  MODIFY `id_items_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `items_type`
--
ALTER TABLE `items_type`
  MODIFY `id_items_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT для таблицы `properties`
--
ALTER TABLE `properties`
  MODIFY `id_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id_roles` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `supports`
--
ALTER TABLE `supports`
  MODIFY `id_supports` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT для таблицы `talks`
--
ALTER TABLE `talks`
  MODIFY `id_talks` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`items_id_favorites`) REFERENCES `items` (`id_items`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`users_id_favorites`) REFERENCES `users` (`id_users`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`items_type_id_items`) REFERENCES `items_type` (`id_items_type`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `items_images`
--
ALTER TABLE `items_images`
  ADD CONSTRAINT `items_images_ibfk_1` FOREIGN KEY (`items_id_items_images`) REFERENCES `items` (`id_items`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
