-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Июн 09 2026 г., 21:09
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
  `value_attributes` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `attributes`
--

INSERT INTO `attributes` (`id_attributes`, `properties_id_attributes`, `value_attributes`) VALUES
(1, 1, 'Красный'),
(2, 1, 'Белый'),
(3, 1, 'Розовый'),
(4, 2, 'Большой'),
(5, 2, 'Маленький'),
(6, 2, 'Средний'),
(7, 3, 'Крутое'),
(8, 3, 'Низкое'),
(9, 3, 'Среднее');

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
  `text_comments` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `rating_comments` tinyint NOT NULL,
  `date_add_comments` datetime NOT NULL,
  `items_id_comments` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id_comments`, `users_id_comments`, `text_comments`, `rating_comments`, `date_add_comments`, `items_id_comments`) VALUES
(2, 1, 'Крутой товар Очень нравится', 5, '2026-06-09 18:31:52', 305);

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id_favorites` int NOT NULL,
  `items_id_favorites` int NOT NULL,
  `users_id_favorites` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `favorites`
--

INSERT INTO `favorites` (`id_favorites`, `items_id_favorites`, `users_id_favorites`) VALUES
(5, 307, 1),
(6, 306, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE `items` (
  `id_items` int NOT NULL,
  `name_items` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `description_items` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `count_items` int NOT NULL,
  `cost_items` int NOT NULL,
  `discount_items` tinyint DEFAULT NULL,
  `items_type_id_items` int NOT NULL,
  `views_items` int NOT NULL DEFAULT '0',
  `date_add_items` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`id_items`, `name_items`, `description_items`, `count_items`, `cost_items`, `discount_items`, `items_type_id_items`, `views_items`, `date_add_items`) VALUES
(1, 'Товар 1', NULL, 19, 872, NULL, 2, 0, '2026-01-13'),
(2, 'Товар 2', NULL, 14, 682, NULL, 1, 0, '2026-01-14'),
(3, 'Товар 3', NULL, 15, 740, NULL, 2, 0, '2026-01-14'),
(4, 'Товар 4', NULL, 18, 435, NULL, 1, 0, '2026-01-14'),
(5, 'Товар 5', NULL, 25, 366, NULL, 2, 0, '2026-01-14'),
(6, 'Товар 6', NULL, 15, 168, NULL, 1, 0, '2026-01-14'),
(7, 'Товар 7', NULL, 16, 85, NULL, 2, 0, '2026-01-14'),
(8, 'Товар 8', NULL, 26, 921, NULL, 1, 0, '2026-01-14'),
(9, 'Товар 9', NULL, 26, 790, NULL, 2, 0, '2026-01-14'),
(10, 'Товар 10', NULL, 9, 316, NULL, 1, 0, '2026-01-14'),
(11, 'Товар 11', NULL, 16, 164, NULL, 2, 0, '2026-01-14'),
(12, 'Товар 12', NULL, 9, 150, NULL, 1, 0, '2026-01-14'),
(13, 'Товар 13', NULL, 17, 472, NULL, 2, 0, '2026-01-14'),
(14, 'Товар 14', NULL, 11, 461, NULL, 1, 0, '2026-01-14'),
(15, 'Товар 15', NULL, 13, 330, NULL, 2, 0, '2026-01-14'),
(16, 'Товар 16', NULL, 15, 778, NULL, 1, 0, '2026-01-14'),
(17, 'Товар 17', NULL, 21, 448, NULL, 2, 0, '2026-01-14'),
(18, 'Товар 18', NULL, 14, 104, NULL, 1, 0, '2026-01-14'),
(19, 'Товар 19', NULL, 12, 910, NULL, 2, 0, '2026-01-14'),
(20, 'Товар 20', NULL, 9, 860, NULL, 1, 0, '2026-01-14'),
(21, 'Товар 21', NULL, 25, 937, NULL, 2, 0, '2026-01-14'),
(22, 'Товар 22', NULL, 12, 988, NULL, 1, 0, '2026-01-14'),
(23, 'Товар 23', NULL, 20, 495, NULL, 2, 0, '2026-01-14'),
(24, 'Товар 24', NULL, 27, 495, NULL, 1, 0, '2026-01-14'),
(25, 'Товар 25', NULL, 6, 190, NULL, 2, 0, '2026-01-14'),
(26, 'Товар 26', NULL, 15, 38, NULL, 1, 0, '2026-01-14'),
(27, 'Товар 27', NULL, 21, 963, NULL, 2, 0, '2026-01-14'),
(28, 'Товар 28', NULL, 19, 947, NULL, 1, 0, '2026-01-14'),
(29, 'Товар 29', NULL, 19, 764, NULL, 2, 0, '2026-01-14'),
(30, 'Товар 30', NULL, 6, 437, NULL, 1, 0, '2026-01-14'),
(31, 'Товар 31', NULL, 8, 765, NULL, 2, 0, '2026-01-14'),
(32, 'Товар 32', NULL, 7, 938, NULL, 1, 0, '2026-01-14'),
(33, 'Товар 33', NULL, 19, 358, NULL, 2, 0, '2026-01-14'),
(34, 'Товар 34', NULL, 25, 915, NULL, 1, 0, '2026-01-14'),
(35, 'Товар 35', NULL, 8, 470, NULL, 2, 0, '2026-01-14'),
(36, 'Товар 36', NULL, 25, 119, NULL, 1, 0, '2026-01-14'),
(37, 'Товар 37', NULL, 22, 215, NULL, 2, 0, '2026-01-14'),
(38, 'Товар 38', NULL, 11, 754, NULL, 1, 0, '2026-01-14'),
(39, 'Товар 39', NULL, 6, 523, NULL, 2, 0, '2026-01-14'),
(40, 'Товар 40', NULL, 17, 293, NULL, 1, 0, '2026-01-14'),
(41, 'Товар 41', NULL, 7, 243, NULL, 2, 0, '2026-01-14'),
(42, 'Товар 42', NULL, 21, 907, NULL, 1, 0, '2026-01-14'),
(43, 'Товар 43', NULL, 22, 346, NULL, 2, 0, '2026-01-14'),
(44, 'Товар 44', NULL, 15, 817, NULL, 1, 0, '2026-01-14'),
(45, 'Товар 45', NULL, 21, 439, NULL, 2, 0, '2026-01-14'),
(46, 'Товар 46', NULL, 24, 169, NULL, 1, 0, '2026-01-14'),
(47, 'Товар 47', NULL, 18, 659, NULL, 2, 0, '2026-01-14'),
(48, 'Товар 48', NULL, 17, 930, NULL, 1, 0, '2026-01-14'),
(49, 'Товар 49', NULL, 30, 188, NULL, 2, 0, '2026-01-14'),
(50, 'Товар 50', NULL, 7, 742, NULL, 1, 0, '2026-01-14'),
(51, 'Товар 51', NULL, 18, 452, NULL, 2, 0, '2026-01-14'),
(52, 'Товар 52', NULL, 24, 110, NULL, 1, 0, '2026-01-14'),
(53, 'Товар 53', NULL, 7, 125, NULL, 2, 0, '2026-01-14'),
(54, 'Товар 54', NULL, 17, 626, NULL, 1, 0, '2026-01-14'),
(55, 'Товар 55', NULL, 16, 652, NULL, 2, 0, '2026-01-14'),
(56, 'Товар 56', NULL, 11, 633, NULL, 1, 0, '2026-01-14'),
(57, 'Товар 57', NULL, 13, 596, NULL, 2, 0, '2026-01-14'),
(58, 'Товар 58', NULL, 28, 645, NULL, 1, 0, '2026-01-14'),
(59, 'Товар 59', NULL, 10, 399, NULL, 2, 0, '2026-01-14'),
(60, 'Товар 60', NULL, 12, 43, NULL, 1, 0, '2026-01-14'),
(61, 'Товар 61', NULL, 22, 254, NULL, 2, 0, '2026-01-14'),
(62, 'Товар 62', NULL, 6, 196, NULL, 1, 0, '2026-01-14'),
(63, 'Товар 63', NULL, 19, 927, NULL, 2, 0, '2026-01-14'),
(64, 'Товар 64', NULL, 14, 838, NULL, 1, 0, '2026-01-14'),
(65, 'Товар 65', NULL, 20, 205, NULL, 2, 0, '2026-01-14'),
(66, 'Товар 66', NULL, 13, 418, NULL, 1, 0, '2026-01-14'),
(67, 'Товар 67', NULL, 6, 126, NULL, 2, 0, '2026-01-14'),
(68, 'Товар 68', NULL, 11, 684, NULL, 1, 0, '2026-01-14'),
(69, 'Товар 69', NULL, 30, 839, NULL, 2, 0, '2026-01-14'),
(70, 'Товар 70', NULL, 5, 339, NULL, 1, 0, '2026-01-14'),
(71, 'Товар 71', NULL, 10, 560, NULL, 2, 0, '2026-01-14'),
(72, 'Товар 72', NULL, 20, 52, NULL, 1, 0, '2026-01-14'),
(73, 'Товар 73', NULL, 15, 979, NULL, 2, 0, '2026-01-14'),
(74, 'Товар 74', NULL, 29, 517, NULL, 1, 0, '2026-01-14'),
(75, 'Товар 75', NULL, 17, 413, NULL, 2, 0, '2026-01-14'),
(76, 'Товар 76', NULL, 11, 270, NULL, 1, 0, '2026-01-14'),
(77, 'Товар 77', NULL, 24, 290, NULL, 2, 0, '2026-01-14'),
(78, 'Товар 78', NULL, 9, 260, NULL, 1, 0, '2026-01-14'),
(79, 'Товар 79', NULL, 13, 901, NULL, 2, 0, '2026-01-14'),
(80, 'Товар 80', NULL, 6, 877, NULL, 1, 0, '2026-01-14'),
(81, 'Товар 81', NULL, 24, 470, NULL, 2, 0, '2026-01-14'),
(82, 'Товар 82', NULL, 15, 694, NULL, 1, 0, '2026-01-14'),
(83, 'Товар 83', NULL, 30, 103, NULL, 2, 0, '2026-01-14'),
(84, 'Товар 84', NULL, 22, 797, NULL, 1, 0, '2026-01-14'),
(85, 'Товар 85', NULL, 25, 332, NULL, 2, 0, '2026-01-14'),
(86, 'Товар 86', NULL, 27, 657, NULL, 1, 0, '2026-01-14'),
(87, 'Товар 87', NULL, 8, 421, NULL, 2, 0, '2026-01-14'),
(88, 'Товар 88', NULL, 21, 586, NULL, 1, 0, '2026-01-14'),
(89, 'Товар 89', NULL, 18, 16, NULL, 2, 0, '2026-01-14'),
(90, 'Товар 90', NULL, 22, 257, NULL, 1, 0, '2026-01-14'),
(91, 'Товар 91', NULL, 6, 487, NULL, 2, 0, '2026-01-14'),
(92, 'Товар 92', NULL, 29, 79, NULL, 1, 0, '2026-01-14'),
(93, 'Товар 93', NULL, 18, 685, NULL, 2, 0, '2026-01-14'),
(94, 'Товар 94', NULL, 19, 218, NULL, 1, 0, '2026-01-14'),
(95, 'Товар 95', NULL, 9, 820, NULL, 2, 0, '2026-01-14'),
(96, 'Товар 96', NULL, 26, 334, NULL, 1, 0, '2026-01-14'),
(97, 'Товар 97', NULL, 5, 922, NULL, 2, 0, '2026-01-14'),
(98, 'Товар 98', NULL, 11, 250, NULL, 1, 0, '2026-01-14'),
(99, 'Товар 99', NULL, 12, 461, NULL, 2, 0, '2026-01-14'),
(100, 'Товар 100', NULL, 6, 425, NULL, 1, 0, '2026-01-14'),
(101, 'Товар 101', NULL, 28, 620, NULL, 2, 0, '2026-01-14'),
(102, 'Товар 102', NULL, 10, 224, NULL, 1, 0, '2026-01-14'),
(103, 'Товар 103', NULL, 13, 36, NULL, 2, 0, '2026-01-14'),
(104, 'Товар 104', NULL, 6, 787, NULL, 1, 0, '2026-01-14'),
(105, 'Товар 105', NULL, 19, 692, NULL, 2, 0, '2026-01-14'),
(106, 'Товар 106', NULL, 7, 990, NULL, 1, 0, '2026-01-14'),
(107, 'Товар 107', NULL, 6, 639, NULL, 2, 0, '2026-01-14'),
(108, 'Товар 108', NULL, 22, 60, NULL, 1, 0, '2026-01-14'),
(109, 'Товар 109', NULL, 22, 726, NULL, 2, 0, '2026-01-14'),
(110, 'Товар 110', NULL, 8, 191, NULL, 1, 0, '2026-01-14'),
(111, 'Товар 111', NULL, 6, 147, NULL, 2, 0, '2026-01-14'),
(112, 'Товар 112', NULL, 12, 375, NULL, 1, 0, '2026-01-14'),
(113, 'Товар 113', NULL, 6, 206, NULL, 2, 0, '2026-01-14'),
(114, 'Товар 114', NULL, 12, 77, NULL, 1, 0, '2026-01-14'),
(115, 'Товар 115', NULL, 8, 146, NULL, 2, 0, '2026-01-14'),
(116, 'Товар 116', NULL, 18, 26, NULL, 1, 0, '2026-01-14'),
(117, 'Товар 117', NULL, 15, 376, NULL, 2, 0, '2026-01-14'),
(118, 'Товар 118', NULL, 17, 69, NULL, 1, 0, '2026-01-14'),
(119, 'Товар 119', NULL, 6, 73, NULL, 2, 0, '2026-01-14'),
(120, 'Товар 120', NULL, 21, 339, NULL, 1, 0, '2026-01-14'),
(121, 'Товар 121', NULL, 17, 52, NULL, 2, 0, '2026-01-14'),
(122, 'Товар 122', NULL, 15, 352, NULL, 1, 0, '2026-01-14'),
(123, 'Товар 123', NULL, 8, 701, NULL, 2, 0, '2026-01-14'),
(124, 'Товар 124', NULL, 17, 803, NULL, 1, 0, '2026-01-14'),
(125, 'Товар 125', NULL, 9, 590, NULL, 2, 0, '2026-01-14'),
(126, 'Товар 126', NULL, 21, 458, NULL, 1, 0, '2026-01-14'),
(127, 'Товар 127', NULL, 28, 256, NULL, 2, 0, '2026-01-14'),
(128, 'Товар 128', NULL, 8, 344, NULL, 1, 0, '2026-01-14'),
(129, 'Товар 129', NULL, 18, 720, NULL, 2, 0, '2026-01-14'),
(130, 'Товар 130', NULL, 7, 551, NULL, 1, 0, '2026-01-14'),
(131, 'Товар 131', NULL, 26, 546, NULL, 2, 0, '2026-01-14'),
(132, 'Товар 132', NULL, 30, 654, NULL, 1, 0, '2026-01-14'),
(133, 'Товар 133', NULL, 29, 155, NULL, 2, 0, '2026-01-14'),
(134, 'Товар 134', NULL, 27, 324, NULL, 1, 0, '2026-01-14'),
(135, 'Товар 135', NULL, 12, 116, NULL, 2, 0, '2026-01-14'),
(136, 'Товар 136', NULL, 30, 532, NULL, 1, 0, '2026-01-14'),
(137, 'Товар 137', NULL, 10, 506, NULL, 2, 0, '2026-01-14'),
(138, 'Товар 138', NULL, 24, 474, NULL, 1, 0, '2026-01-14'),
(139, 'Товар 139', NULL, 9, 888, NULL, 2, 0, '2026-01-14'),
(140, 'Товар 140', NULL, 16, 129, NULL, 1, 0, '2026-01-14'),
(141, 'Товар 141', NULL, 29, 440, NULL, 2, 0, '2026-01-14'),
(142, 'Товар 142', NULL, 16, 16, NULL, 1, 0, '2026-01-14'),
(143, 'Товар 143', NULL, 26, 463, NULL, 2, 0, '2026-01-14'),
(144, 'Товар 144', NULL, 28, 850, NULL, 1, 0, '2026-01-14'),
(145, 'Товар 145', NULL, 6, 315, NULL, 2, 0, '2026-01-14'),
(146, 'Товар 146', NULL, 25, 72, NULL, 1, 0, '2026-01-14'),
(147, 'Товар 147', NULL, 20, 253, NULL, 2, 0, '2026-01-14'),
(148, 'Товар 148', NULL, 11, 929, NULL, 1, 0, '2026-01-14'),
(149, 'Товар 149', NULL, 18, 100, NULL, 2, 0, '2026-01-14'),
(150, 'Товар 150', NULL, 5, 698, NULL, 1, 0, '2026-01-14'),
(151, 'Товар 151', NULL, 19, 813, NULL, 2, 0, '2026-01-14'),
(152, 'Товар 152', NULL, 23, 377, NULL, 1, 0, '2026-01-14'),
(153, 'Товар 153', NULL, 30, 160, NULL, 2, 0, '2026-01-14'),
(154, 'Товар 154', NULL, 10, 375, NULL, 1, 0, '2026-01-14'),
(155, 'Товар 155', NULL, 26, 967, NULL, 2, 0, '2026-01-14'),
(156, 'Товар 156', NULL, 13, 732, NULL, 1, 0, '2026-01-14'),
(157, 'Товар 157', NULL, 10, 268, NULL, 2, 0, '2026-01-14'),
(158, 'Товар 158', NULL, 20, 765, NULL, 1, 0, '2026-01-14'),
(159, 'Товар 159', NULL, 7, 289, NULL, 2, 0, '2026-01-14'),
(160, 'Товар 160', NULL, 6, 270, NULL, 1, 0, '2026-01-14'),
(161, 'Товар 161', NULL, 29, 419, NULL, 2, 0, '2026-01-14'),
(162, 'Товар 162', NULL, 8, 597, NULL, 1, 0, '2026-01-14'),
(163, 'Товар 163', NULL, 5, 527, NULL, 2, 0, '2026-01-14'),
(164, 'Товар 164', NULL, 16, 257, NULL, 1, 0, '2026-01-14'),
(165, 'Товар 165', NULL, 28, 766, NULL, 2, 0, '2026-01-14'),
(166, 'Товар 166', NULL, 22, 802, NULL, 1, 0, '2026-01-14'),
(167, 'Товар 167', NULL, 25, 531, NULL, 2, 0, '2026-01-14'),
(168, 'Товар 168', NULL, 20, 133, NULL, 1, 0, '2026-01-14'),
(169, 'Товар 169', NULL, 6, 221, NULL, 2, 0, '2026-01-14'),
(170, 'Товар 170', NULL, 10, 579, NULL, 1, 0, '2026-01-14'),
(171, 'Товар 171', NULL, 13, 539, NULL, 2, 0, '2026-01-14'),
(172, 'Товар 172', NULL, 25, 981, NULL, 1, 0, '2026-01-14'),
(173, 'Товар 173', NULL, 20, 494, NULL, 2, 0, '2026-01-14'),
(174, 'Товар 174', NULL, 17, 796, NULL, 1, 0, '2026-01-14'),
(175, 'Товар 175', NULL, 10, 155, NULL, 2, 0, '2026-01-14'),
(176, 'Товар 176', NULL, 16, 493, NULL, 1, 0, '2026-01-14'),
(177, 'Товар 177', NULL, 10, 368, NULL, 2, 0, '2026-01-14'),
(178, 'Товар 178', NULL, 29, 166, NULL, 1, 0, '2026-01-14'),
(179, 'Товар 179', NULL, 24, 261, NULL, 2, 0, '2026-01-14'),
(180, 'Товар 180', NULL, 15, 368, NULL, 1, 0, '2026-01-14'),
(181, 'Товар 181', NULL, 10, 751, NULL, 2, 0, '2026-01-14'),
(182, 'Товар 182', NULL, 14, 531, NULL, 1, 0, '2026-01-14'),
(183, 'Товар 183', NULL, 23, 854, NULL, 2, 0, '2026-01-14'),
(184, 'Товар 184', NULL, 16, 679, NULL, 1, 0, '2026-01-14'),
(185, 'Товар 185', NULL, 5, 647, NULL, 2, 0, '2026-01-14'),
(186, 'Товар 186', NULL, 27, 125, NULL, 1, 0, '2026-01-14'),
(187, 'Товар 187', NULL, 5, 418, NULL, 2, 0, '2026-01-14'),
(188, 'Товар 188', NULL, 18, 658, NULL, 1, 0, '2026-01-14'),
(189, 'Товар 189', NULL, 18, 446, NULL, 2, 0, '2026-01-14'),
(190, 'Товар 190', NULL, 21, 953, NULL, 1, 0, '2026-01-14'),
(191, 'Товар 191', NULL, 20, 322, NULL, 2, 0, '2026-01-14'),
(192, 'Товар 192', NULL, 11, 102, NULL, 1, 0, '2026-01-14'),
(193, 'Товар 193', NULL, 29, 592, NULL, 2, 0, '2026-01-14'),
(194, 'Товар 194', NULL, 18, 372, NULL, 1, 0, '2026-01-14'),
(195, 'Товар 195', NULL, 19, 162, NULL, 2, 0, '2026-01-14'),
(196, 'Товар 196', NULL, 6, 562, NULL, 1, 0, '2026-01-14'),
(197, 'Товар 197', NULL, 5, 241, NULL, 2, 0, '2026-01-14'),
(198, 'Товар 198', NULL, 9, 644, NULL, 1, 0, '2026-01-14'),
(199, 'Товар 199', NULL, 13, 413, NULL, 2, 0, '2026-01-14'),
(200, 'Товар 200', NULL, 20, 137, NULL, 1, 0, '2026-01-14'),
(201, 'Товар 201', NULL, 18, 798, NULL, 2, 0, '2026-01-14'),
(202, 'Товар 202', NULL, 5, 531, NULL, 1, 0, '2026-01-14'),
(203, 'Товар 203', NULL, 11, 45, NULL, 2, 0, '2026-01-14'),
(204, 'Товар 204', NULL, 14, 847, NULL, 1, 0, '2026-01-14'),
(205, 'Товар 205', NULL, 27, 229, NULL, 2, 0, '2026-01-14'),
(206, 'Товар 206', NULL, 13, 962, NULL, 1, 0, '2026-01-14'),
(207, 'Товар 207', NULL, 12, 95, NULL, 2, 0, '2026-01-14'),
(208, 'Товар 208', NULL, 16, 334, NULL, 1, 0, '2026-01-14'),
(209, 'Товар 209', NULL, 6, 88, NULL, 2, 0, '2026-01-14'),
(210, 'Товар 210', NULL, 18, 774, NULL, 1, 0, '2026-01-14'),
(211, 'Товар 211', NULL, 22, 787, NULL, 2, 0, '2026-01-14'),
(212, 'Товар 212', NULL, 12, 91, NULL, 1, 0, '2026-01-14'),
(213, 'Товар 213', NULL, 7, 425, NULL, 2, 0, '2026-01-14'),
(214, 'Товар 214', NULL, 22, 810, NULL, 1, 0, '2026-01-14'),
(215, 'Товар 215', NULL, 7, 996, NULL, 2, 0, '2026-01-14'),
(216, 'Товар 216', NULL, 21, 904, NULL, 1, 0, '2026-01-14'),
(217, 'Товар 217', NULL, 25, 580, NULL, 2, 0, '2026-01-14'),
(218, 'Товар 218', NULL, 7, 513, NULL, 1, 0, '2026-01-14'),
(219, 'Товар 219', NULL, 29, 371, NULL, 2, 0, '2026-01-14'),
(220, 'Товар 220', NULL, 13, 23, NULL, 1, 0, '2026-01-14'),
(221, 'Товар 221', NULL, 15, 614, NULL, 2, 0, '2026-01-14'),
(222, 'Товар 222', NULL, 19, 686, NULL, 1, 0, '2026-01-14'),
(223, 'Товар 223', NULL, 24, 980, NULL, 2, 0, '2026-01-14'),
(224, 'Товар 224', NULL, 30, 662, NULL, 1, 0, '2026-01-14'),
(225, 'Товар 225', NULL, 8, 716, NULL, 2, 0, '2026-01-14'),
(226, 'Товар 226', NULL, 15, 433, NULL, 1, 0, '2026-01-14'),
(227, 'Товар 227', NULL, 17, 176, NULL, 2, 0, '2026-01-14'),
(228, 'Товар 228', NULL, 11, 839, NULL, 1, 0, '2026-01-14'),
(229, 'Товар 229', NULL, 14, 834, NULL, 2, 0, '2026-01-14'),
(230, 'Товар 230', NULL, 14, 11, NULL, 1, 0, '2026-01-14'),
(231, 'Товар 231', NULL, 14, 839, NULL, 2, 0, '2026-01-14'),
(232, 'Товар 232', NULL, 18, 152, NULL, 1, 0, '2026-01-14'),
(233, 'Товар 233', NULL, 15, 137, NULL, 2, 0, '2026-01-14'),
(234, 'Товар 234', NULL, 9, 653, NULL, 1, 0, '2026-01-14'),
(235, 'Товар 235', NULL, 7, 248, NULL, 2, 0, '2026-01-14'),
(236, 'Товар 236', NULL, 11, 626, NULL, 1, 0, '2026-01-14'),
(237, 'Товар 237', NULL, 9, 695, NULL, 2, 0, '2026-01-14'),
(238, 'Товар 238', NULL, 19, 151, NULL, 1, 0, '2026-01-14'),
(239, 'Товар 239', NULL, 25, 467, NULL, 2, 0, '2026-01-14'),
(240, 'Товар 240', NULL, 16, 560, NULL, 1, 0, '2026-01-14'),
(241, 'Товар 241', NULL, 18, 36, NULL, 2, 0, '2026-01-14'),
(242, 'Товар 242', NULL, 17, 286, NULL, 1, 0, '2026-01-14'),
(243, 'Товар 243', NULL, 15, 492, NULL, 2, 0, '2026-01-14'),
(244, 'Товар 244', NULL, 18, 507, NULL, 1, 0, '2026-01-14'),
(245, 'Товар 245', NULL, 28, 614, NULL, 2, 0, '2026-01-14'),
(246, 'Товар 246', NULL, 23, 743, NULL, 1, 0, '2026-01-14'),
(247, 'Товар 247', NULL, 13, 212, NULL, 2, 0, '2026-01-14'),
(248, 'Товар 248', NULL, 28, 317, NULL, 1, 0, '2026-01-14'),
(249, 'Товар 249', NULL, 16, 586, NULL, 2, 0, '2026-01-14'),
(250, 'Товар 250', NULL, 24, 365, NULL, 1, 0, '2026-01-14'),
(251, 'Товар 251', NULL, 24, 43, NULL, 2, 0, '2026-01-14'),
(252, 'Товар 252', NULL, 29, 151, NULL, 1, 0, '2026-01-14'),
(253, 'Товар 253', NULL, 7, 718, NULL, 2, 0, '2026-01-14'),
(254, 'Товар 254', NULL, 25, 314, NULL, 1, 0, '2026-01-14'),
(255, 'Товар 255', NULL, 20, 721, NULL, 2, 0, '2026-01-14'),
(256, 'Товар 256', NULL, 18, 763, NULL, 1, 0, '2026-01-14'),
(257, 'Товар 257', NULL, 30, 229, NULL, 2, 0, '2026-01-14'),
(258, 'Товар 258', NULL, 25, 77, NULL, 1, 0, '2026-01-14'),
(259, 'Товар 259', NULL, 27, 625, NULL, 2, 0, '2026-01-14'),
(260, 'Товар 260', NULL, 17, 336, NULL, 1, 0, '2026-01-14'),
(261, 'Товар 261', NULL, 17, 102, NULL, 2, 0, '2026-01-14'),
(262, 'Товар 262', NULL, 27, 325, NULL, 1, 0, '2026-01-14'),
(263, 'Товар 263', NULL, 23, 891, NULL, 2, 0, '2026-01-14'),
(264, 'Товар 264', NULL, 18, 203, NULL, 1, 0, '2026-01-14'),
(265, 'Товар 265', NULL, 24, 644, NULL, 2, 0, '2026-01-14'),
(266, 'Товар 266', NULL, 19, 186, NULL, 1, 0, '2026-01-14'),
(267, 'Товар 267', NULL, 24, 953, NULL, 2, 0, '2026-01-14'),
(268, 'Товар 268', NULL, 13, 483, NULL, 1, 0, '2026-01-14'),
(269, 'Товар 269', NULL, 15, 741, NULL, 2, 0, '2026-01-14'),
(270, 'Товар 270', NULL, 29, 381, NULL, 1, 0, '2026-01-14'),
(271, 'Товар 271', NULL, 24, 972, NULL, 2, 0, '2026-01-14'),
(272, 'Товар 272', NULL, 10, 257, NULL, 1, 0, '2026-01-14'),
(273, 'Товар 273', NULL, 20, 898, NULL, 2, 0, '2026-01-14'),
(274, 'Товар 274', NULL, 28, 259, NULL, 1, 0, '2026-01-14'),
(275, 'Товар 275', NULL, 27, 106, NULL, 2, 0, '2026-01-14'),
(276, 'Товар 276', NULL, 30, 379, NULL, 1, 0, '2026-01-14'),
(277, 'Товар 277', NULL, 22, 758, NULL, 2, 0, '2026-01-14'),
(278, 'Товар 278', NULL, 16, 79, NULL, 1, 0, '2026-01-14'),
(279, 'Товар 279', NULL, 11, 500, NULL, 2, 0, '2026-01-14'),
(280, 'Товар 280', NULL, 15, 261, NULL, 1, 0, '2026-01-14'),
(281, 'Товар 281', NULL, 15, 820, NULL, 2, 0, '2026-01-14'),
(282, 'Товар 282', NULL, 6, 731, NULL, 1, 0, '2026-01-14'),
(283, 'Товар 283', NULL, 18, 456, NULL, 2, 0, '2026-01-14'),
(284, 'Товар 284', NULL, 15, 207, NULL, 1, 0, '2026-01-14'),
(285, 'Товар 285', NULL, 16, 648, NULL, 2, 0, '2026-01-14'),
(286, 'Товар 286', NULL, 15, 371, NULL, 1, 0, '2026-01-14'),
(287, 'Товар 287', NULL, 13, 582, NULL, 2, 0, '2026-01-14'),
(288, 'Товар 288', NULL, 24, 728, NULL, 1, 0, '2026-01-14'),
(289, 'Товар 289', NULL, 30, 230, NULL, 2, 0, '2026-01-14'),
(290, 'Товар 290', NULL, 5, 689, NULL, 1, 0, '2026-01-14'),
(291, 'Товар 291', NULL, 21, 296, NULL, 2, 0, '2026-01-14'),
(292, 'Товар 292', NULL, 20, 222, NULL, 1, 0, '2026-01-14'),
(293, 'Товар 293', NULL, 24, 555, NULL, 2, 0, '2026-01-14'),
(294, 'Товар 294', NULL, 19, 356, NULL, 1, 0, '2026-01-14'),
(295, 'Товар 295', NULL, 19, 914, NULL, 2, 0, '2026-01-14'),
(296, 'Товар 296', NULL, 28, 938, NULL, 1, 0, '2026-01-14'),
(297, 'Товар 297', NULL, 9, 343, NULL, 2, 0, '2026-01-14'),
(298, 'Подушка', NULL, 24, 883, NULL, 1, 1, '2026-01-14'),
(299, 'Полотенце', NULL, 26, 585, NULL, 2, 1, '2026-01-14'),
(300, 'Щетка', NULL, 21, 357, NULL, 1, 2, '2026-01-14'),
(305, 'Кастрюля', NULL, 400, 5500, 10, 1, 5, '2026-06-09'),
(306, 'Ложка', 'Ложка', 100, 200, NULL, 2, 2, '2026-06-09'),
(307, 'Тостер', 'ляляляля', 345, 500, 34, 3, 3, '2026-06-09');

-- --------------------------------------------------------

--
-- Структура таблицы `items_images`
--

CREATE TABLE `items_images` (
  `id_items_images` int NOT NULL,
  `items_id_items_images` int NOT NULL,
  `image_items_images` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `items_images`
--

INSERT INTO `items_images` (`id_items_images`, `items_id_items_images`, `image_items_images`) VALUES
(9, 305, '202606091827270.png'),
(11, 306, '202606091831080.webp'),
(17, 307, '202606092023130.jpg'),
(18, 300, '202606092027270.jpg'),
(19, 299, '202606092027560.jpg'),
(20, 298, '202606092028250.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `items_properties`
--

CREATE TABLE `items_properties` (
  `id_items_properties` int NOT NULL,
  `items_id_items_properties` int NOT NULL,
  `attributes_id_items_properties` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `items_properties`
--

INSERT INTO `items_properties` (`id_items_properties`, `items_id_items_properties`, `attributes_id_items_properties`) VALUES
(3, 307, 7),
(4, 307, 9),
(5, 307, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `items_type`
--

CREATE TABLE `items_type` (
  `id_items_type` int NOT NULL,
  `name_items_type` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
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
  `name_properties` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `properties`
--

INSERT INTO `properties` (`id_properties`, `name_properties`) VALUES
(1, 'Цвет'),
(2, 'Размер'),
(3, 'Качество');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id_roles` int NOT NULL,
  `name_roles` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
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
  `name_status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
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
  `users_write_supports` int NOT NULL,
  `text_supports` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `image_supports` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `datetime_supports` datetime NOT NULL
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
  `title_talks` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
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
  `email_users` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password_users` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name_users` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `avatar_users` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `tel_users` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date_create_users` date NOT NULL,
  `is_banned_users` tinyint(1) NOT NULL DEFAULT '0',
  `roles_id_users` int NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_users`, `email_users`, `password_users`, `name_users`, `avatar_users`, `tel_users`, `date_create_users`, `is_banned_users`, `roles_id_users`) VALUES
(1, 'admin@admin.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Админ', NULL, NULL, '2025-11-30', 0, 1),
(2, 'user@user.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'пользователь', NULL, NULL, '2025-11-30', 0, 2),
(3, 'test@test.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Тест', NULL, NULL, '2026-03-17', 1, 2),
(4, 'del1@g.g', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'ДоставщикОдин', NULL, NULL, '2026-04-06', 0, 3),
(5, 'del2@g.g', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'ДоставщикДва', NULL, NULL, '2026-04-06', 0, 3),
(6, 'support@support.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'ПоддрежкаОдин', NULL, NULL, '2026-05-16', 0, 4);

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
  MODIFY `id_attributes` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `baskets`
--
ALTER TABLE `baskets`
  MODIFY `id_baskets` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comments` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id_favorites` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id_items` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT для таблицы `items_images`
--
ALTER TABLE `items_images`
  MODIFY `id_items_images` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `items_properties`
--
ALTER TABLE `items_properties`
  MODIFY `id_items_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `items_type`
--
ALTER TABLE `items_type`
  MODIFY `id_items_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `properties`
--
ALTER TABLE `properties`
  MODIFY `id_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id_supports` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `talks`
--
ALTER TABLE `talks`
  MODIFY `id_talks` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
