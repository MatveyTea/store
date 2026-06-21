-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Июн 21 2026 г., 19:45
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
(1, 1, 'Черный'),
(2, 1, 'Белый'),
(3, 1, 'Синий'),
(4, 2, 'Аккумулятор'),
(5, 2, 'Блок питания'),
(6, 3, 'Хлопок'),
(7, 3, 'Полиэстер'),
(8, 4, 'Классический'),
(9, 4, 'Спортивный'),
(10, 5, 'Кроссовки'),
(11, 5, 'Туфли'),
(12, 5, 'Ботинки'),
(13, 6, 'Кожа'),
(14, 6, 'Текстиль'),
(15, 7, 'Холодильник'),
(16, 7, 'Стиральная машина'),
(17, 8, '300 л'),
(18, 8, '7 кг'),
(19, 9, 'A++'),
(20, 9, 'B'),
(21, 10, 'Футбол'),
(22, 10, 'Баскетбол'),
(23, 10, 'Теннис'),
(24, 11, 'Витамины'),
(25, 11, 'Обезболивающие'),
(26, 11, 'Средства для горла'),
(27, 12, '100 мг'),
(28, 12, '500 мг'),
(29, 13, 'Таблетки'),
(30, 13, 'Мази'),
(31, 14, 'Профилактика'),
(32, 14, 'Лечение'),
(33, 15, 'Собака'),
(34, 15, 'Кошка'),
(35, 15, 'Птицы'),
(36, 16, '1 кг'),
(37, 16, '500 г'),
(38, 17, 'Кровать'),
(39, 17, 'Стол'),
(40, 17, 'Шкаф'),
(41, 18, 'Очки'),
(42, 18, 'Часы'),
(43, 18, 'Ремень');

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
(1, 'Смарт-часы Model A', NULL, 50, 3000, 10, 1, 0, '2025-10-01'),
(2, 'Наушники Bluetooth', NULL, 80, 1500, 5, 1, 0, '2025-10-01'),
(3, 'Экшн-камера', NULL, 30, 9000, 15, 1, 0, '2025-10-01'),
(4, 'Портативный аккумулятор', NULL, 60, 2000, 20, 1, 0, '2025-10-01'),
(5, 'Умная колонка', NULL, 70, 4500, 10, 1, 0, '2025-10-01'),
(6, 'Электронная книга', NULL, 40, 6500, 8, 1, 0, '2025-10-01'),
(7, 'Игровая консоль', NULL, 20, 25000, 12, 1, 0, '2025-10-01'),
(8, 'Веб-камера', NULL, 55, 3000, 7, 1, 0, '2025-10-01'),
(9, 'Пылесос робот', NULL, 25, 18000, 20, 1, 0, '2025-10-01'),
(10, 'Беспроводной маршрутизатор', NULL, 45, 3500, 5, 1, 0, '2025-10-01'),
(11, 'Планшет', NULL, 35, 12000, 10, 1, 0, '2025-10-01'),
(12, 'Радио усилитель', NULL, 15, 5000, 15, 1, 0, '2025-10-01'),
(13, 'Магнитола', NULL, 25, 7500, 10, 1, 0, '2025-10-01'),
(14, 'Карта памяти 128GB', NULL, 100, 1200, 8, 1, 0, '2025-10-01'),
(15, 'Внешний жесткий диск 2TB', NULL, 50, 6000, 5, 1, 0, '2025-10-01'),
(16, 'USB-хаб', NULL, 65, 900, 3, 1, 0, '2025-10-01'),
(17, 'Bluetooth адаптер', NULL, 55, 1500, 4, 1, 0, '2025-10-01'),
(18, 'Настольный микрофон', NULL, 30, 4200, 6, 1, 1, '2025-10-01'),
(19, 'Wi-Fi адаптер', NULL, 40, 2000, 4, 1, 0, '2025-10-01'),
(20, 'Футболка Casual', NULL, 100, 1500, 5, 2, 0, '2025-10-01'),
(21, 'Джинсы Skinny', NULL, 80, 3500, 10, 2, 0, '2025-10-01'),
(22, 'Куртка пуховая', NULL, 40, 7000, 15, 2, 0, '2025-10-01'),
(23, 'Пиджак классический', NULL, 30, 8200, 0, 2, 0, '2025-10-01'),
(24, 'Платье вечернее', NULL, 20, 6200, 12, 2, 0, '2025-10-01'),
(25, 'Рубашка мужская', NULL, 60, 2000, 8, 2, 0, '2025-10-01'),
(26, 'Лосины утепленные', NULL, 70, 1800, 5, 2, 0, '2025-10-01'),
(27, 'Шорты летние', NULL, 50, 1200, 5, 2, 0, '2025-10-01'),
(28, 'Костюм спортивный', NULL, 30, 5600, 10, 2, 0, '2025-10-01'),
(29, 'Туфли кожаные', NULL, 25, 4500, 5, 2, 0, '2025-10-01'),
(30, 'Кроссовки спорт', NULL, 55, 5200, 7, 2, 0, '2025-10-01'),
(31, 'Шляпа летняя', NULL, 40, 800, 3, 2, 0, '2025-10-01'),
(32, 'Гольфы утеплённые', NULL, 65, 1100, 4, 2, 0, '2025-10-01'),
(33, 'Шарф шерстяной', NULL, 35, 1500, 6, 2, 0, '2025-10-01'),
(34, 'Пальто шерстяное', NULL, 15, 9500, 10, 2, 0, '2025-10-01'),
(35, 'Косоворотка', NULL, 45, 2100, 2, 2, 0, '2025-10-01'),
(36, 'Блузка летняя', NULL, 65, 2800, 4, 2, 0, '2025-10-01'),
(37, 'Трикотажные штаны', NULL, 55, 3300, 6, 2, 0, '2025-10-01'),
(38, 'Носки шерстяные', NULL, 150, 200, 1, 2, 0, '2025-10-01'),
(39, 'Кроссовки Nike', NULL, 60, 5200, 8, 3, 0, '2025-10-01'),
(40, 'Летние сандалии', NULL, 40, 1500, 5, 3, 0, '2025-10-01'),
(41, 'Ботинки зимние', NULL, 30, 9000, 12, 3, 0, '2025-10-01'),
(42, 'Туфли классические', NULL, 35, 7000, 10, 3, 0, '2025-10-01'),
(43, 'Эспадрильи для женщин', NULL, 25, 3000, 6, 3, 0, '2025-10-01'),
(44, 'Кеды Converse', NULL, 55, 4000, 4, 3, 0, '2025-10-01'),
(45, 'Мокасины кожаные', NULL, 20, 6500, 8, 3, 0, '2025-10-01'),
(46, 'Ботинки высокие', NULL, 25, 9500, 15, 3, 0, '2025-10-01'),
(47, 'Галоши резиновые', NULL, 70, 900, 3, 3, 0, '2025-10-01'),
(48, 'Топсайдеры', NULL, 50, 7200, 6, 3, 0, '2025-10-01'),
(49, 'Босоножки летние', NULL, 40, 3500, 4, 3, 0, '2025-10-01'),
(50, 'Мокасины для отдыха', NULL, 30, 6000, 5, 3, 0, '2025-10-01'),
(51, 'Кроссы для бега', NULL, 45, 5800, 7, 3, 0, '2025-10-01'),
(52, 'Туфли лакированные', NULL, 20, 9500, 9, 3, 0, '2025-10-01'),
(53, 'Обувь для тренировок', NULL, 55, 4800, 4, 3, 0, '2025-10-01'),
(54, 'Высокие сапоги', NULL, 15, 12000, 10, 3, 0, '2025-10-01'),
(55, 'Балетки кожаные', NULL, 45, 4200, 3, 3, 0, '2025-10-01'),
(56, 'Летние сабо', NULL, 40, 2500, 2, 3, 0, '2025-10-01'),
(57, 'Холодильник', NULL, 10, 30000, 10, 4, 0, '2025-10-01'),
(58, 'Микроволновая печь', NULL, 20, 9500, 8, 4, 0, '2025-10-01'),
(59, 'Праска', NULL, 30, 3200, 5, 4, 0, '2025-10-01'),
(60, 'Пылесос', NULL, 25, 8500, 7, 4, 0, '2025-10-01'),
(61, 'Кухонный комбайн', NULL, 15, 15000, 12, 4, 0, '2025-10-01'),
(62, 'Тостер', NULL, 35, 4200, 4, 4, 0, '2025-10-01'),
(63, 'Электрическая плита', NULL, 8, 22000, 15, 4, 0, '2025-10-01'),
(64, 'Стайлер для волос', NULL, 45, 3200, 6, 4, 0, '2025-10-01'),
(65, 'Кондиционер', NULL, 12, 25000, 10, 4, 0, '2025-10-01'),
(66, 'Фен', NULL, 50, 1800, 3, 4, 0, '2025-10-01'),
(67, 'Электрический чайник', NULL, 70, 2000, 1, 4, 0, '2025-10-01'),
(68, 'Мультиварка', NULL, 25, 9000, 8, 4, 0, '2025-10-01'),
(69, 'Блендер', NULL, 60, 2500, 5, 4, 0, '2025-10-01'),
(70, 'Сушилка для овощей', NULL, 10, 8500, 14, 4, 0, '2025-10-01'),
(71, 'Кофемашина', NULL, 12, 15000, 6, 4, 0, '2025-10-01'),
(72, 'Обогреватель', NULL, 20, 6000, 13, 4, 0, '2025-10-01'),
(73, 'Гантели 10 кг', NULL, 35, 2500, 10, 5, 0, '2025-10-01'),
(74, 'Коврик для йоги', NULL, 50, 1200, 5, 5, 0, '2025-10-01'),
(75, 'Беговая дорожка', NULL, 8, 45000, 12, 5, 0, '2025-10-01'),
(76, 'Велосипед городской', NULL, 15, 22000, 15, 5, 0, '2025-10-01'),
(77, 'Ракетка для тенниса', NULL, 25, 3000, 8, 5, 0, '2025-10-01'),
(78, 'Мяч футбольный', NULL, 80, 1500, 3, 5, 0, '2025-10-01'),
(79, 'Эспандер', NULL, 60, 850, 2, 5, 0, '2025-10-01'),
(80, 'Шлем велосипедный', NULL, 20, 3500, 4, 5, 0, '2025-10-01'),
(81, 'Бутылка для воды', NULL, 70, 300, 1, 5, 0, '2025-10-01'),
(82, 'Пояс для похудения', NULL, 40, 2500, 7, 5, 0, '2025-10-01'),
(83, 'Защитные налокотники', NULL, 35, 1500, 2, 5, 0, '2025-10-01'),
(84, 'Ватрушка для зимних игр', NULL, 12, 4000, 5, 5, 0, '2025-10-01'),
(85, 'Гири 20 кг', NULL, 18, 5000, 6, 5, 0, '2025-10-01'),
(86, 'Банджи для прыжков', NULL, 10, 3000, 4, 5, 0, '2025-10-01'),
(87, 'Мяч для волейбола', NULL, 55, 1800, 3, 5, 0, '2025-10-01'),
(88, 'Экипировка для фитнеса', NULL, 25, 6000, 9, 5, 0, '2025-10-01'),
(89, 'Одежда для плавания', NULL, 45, 2000, 2, 5, 0, '2025-10-01'),
(90, 'Наколенники для велосипеда', NULL, 30, 2200, 3, 5, 0, '2025-10-01'),
(91, 'Массажный ролл', NULL, 40, 1500, 2, 5, 0, '2025-10-01'),
(92, 'Витамины C', 'Пищевая добавка с высоким содержанием витамина C.', 50, 200, 10, 6, 0, '2025-10-01'),
(93, 'Обезболивающее Парацетамол', 'Лекарство для снижения температуры и боли.', 30, 50, 5, 6, 0, '2025-10-01'),
(94, 'Леденцы от кашля', 'Средство для облегчения симптомов кашля.', 100, 150, 15, 6, 0, '2025-10-01'),
(95, 'Мазь от ожогов', 'Средство для быстрого заживления ожогов.', 20, 250, 20, 6, 0, '2025-10-01'),
(96, 'Назальные капли', 'Средство для устранения заложенности носа.', 40, 180, 10, 6, 0, '2025-10-01'),
(97, 'Противогрибковое крем', 'Лечение грибковых инфекций кожи.', 25, 300, 15, 6, 0, '2025-10-01'),
(98, 'Глазные капли для глаз', 'Успокаивающее средство для глаз.', 35, 220, 12, 6, 0, '2025-10-01'),
(99, 'Средство от простуды', 'Таблетки для симптоматического лечения простуды.', 60, 80, 8, 6, 0, '2025-10-01'),
(100, 'Бальзам для горла', 'Облегчение боли в горле.', 45, 130, 10, 6, 0, '2025-10-01'),
(101, 'Противопаразитарные капли', 'Средство против паразитов.', 15, 350, 25, 6, 0, '2025-10-01'),
(102, 'Крем от дерматита', 'Лечение кожных воспалений.', 30, 270, 15, 6, 0, '2025-10-01'),
(103, 'Витамины для детей', 'Омега-кислоты и витамины для малышей.', 40, 400, 20, 6, 0, '2025-10-01'),
(104, 'Спиртовые тампоны', 'Для обработки раны.', 50, 30, 5, 6, 0, '2025-10-01'),
(105, 'Средство для носа', 'Увлажняющий спрей для носа.', 55, 210, 10, 6, 0, '2025-10-01'),
(106, 'Мазь для суставов', 'Обезболивающее средство для суставов.', 20, 330, 18, 6, 0, '2025-10-01'),
(107, 'Сухой корм для собак', 'Питание для взрослых собак.', 70, 5000, 10, 7, 0, '2025-10-01'),
(108, 'Корм для кошек', 'Высококачественный корм для кошек.', 60, 6000, 12, 7, 0, '2025-10-01'),
(109, 'Игрушка для собак', 'Игрушка из прочного материала.', 80, 200, 8, 7, 0, '2025-10-01'),
(110, 'Средство против блох', 'Защита от паразитов.', 40, 350, 15, 7, 0, '2025-10-01'),
(111, 'Когтерез для котов', 'Удобный инструмент для ухода.', 15, 150, 5, 7, 0, '2025-10-01'),
(112, 'Лакомство для собак', 'Вкусные косточки и награды.', 100, 120, 10, 7, 0, '2025-10-01'),
(113, 'Средство для чистки ушей', 'Уход за ушами питомца.', 25, 180, 10, 7, 0, '2025-10-01'),
(114, 'Аппликация для глистов', 'Средство для профилактики глистов.', 30, 250, 20, 7, 0, '2025-10-01'),
(115, 'Туалет для кошек', 'Удобное лотко с наполнителем.', 45, 400, 15, 7, 0, '2025-10-01'),
(116, 'Мыло для питомцев', 'Гипоаллергенное средство.', 20, 130, 5, 7, 0, '2025-10-01'),
(117, 'Шлейка для собак', 'Надежная и удобная.', 50, 350, 12, 7, 0, '2025-10-01'),
(118, 'Кормушка для птиц', 'Для домашних птиц.', 60, 80, 7, 7, 0, '2025-10-01'),
(119, 'Средство для ухода за шерстью', 'Шампунь и кондиционер.', 35, 220, 10, 7, 0, '2025-10-01'),
(120, 'Аптечка для животных', 'Основные медикаменты для питомца.', 10, 1000, 25, 7, 0, '2025-10-01'),
(121, 'Игрушка для кроликов', 'Забавная для мелких грызунов.', 75, 90, 8, 7, 0, '2025-10-01'),
(122, 'Кровать двуспальная', 'Удобная кровать с мягким матрасом.', 10, 5000, 15, 8, 0, '2025-10-01'),
(123, 'Стол офисный', 'Для работы и учебы.', 20, 3000, 10, 8, 0, '2025-10-01'),
(124, 'Шкаф-купе', 'Для хранения одежды.', 5, 15000, 20, 8, 0, '2025-10-01'),
(125, 'Комод', 'Для хранения одежды и мелочей.', 15, 4000, 12, 8, 0, '2025-10-01'),
(126, 'Стул компьютерный', 'Эргономичный и удобный.', 25, 1500, 8, 8, 0, '2025-10-01'),
(127, 'Тумба под ТВ', 'Для размещения техники.', 12, 3500, 10, 8, 0, '2025-10-01'),
(128, 'Кухонный стол', 'Для семейных обедов.', 8, 2500, 10, 8, 0, '2025-10-01'),
(129, 'Диван угловой', 'Для гостиной.', 4, 20000, 18, 8, 0, '2025-10-01'),
(130, 'Кресло мягкое', 'Комфортное для отдыха.', 10, 7000, 15, 8, 0, '2025-10-01'),
(131, 'Полка настенная', 'Для книг и декора.', 18, 800, 10, 8, 0, '2025-10-01'),
(132, 'Зеркало настенное', 'Вписывается в любой интерьер.', 14, 2500, 10, 8, 0, '2025-10-01'),
(133, 'Стеллаж книжный', 'Для хранения книг.', 9, 4000, 12, 8, 0, '2025-10-01'),
(134, 'Кухонная тумба', 'Дополнительное пространство хранения.', 13, 6000, 10, 8, 0, '2025-10-01'),
(135, 'Трансформер кровать', 'Многофункциональный спальный комплекс.', 6, 15000, 17, 8, 0, '2025-10-01'),
(136, 'Письменный стол', 'Для учебы и работы.', 20, 3500, 10, 8, 0, '2025-10-01'),
(137, 'Часы наручные', 'Стильные и современные.', 40, 3000, 15, 9, 0, '2025-10-01'),
(138, 'Очки солнцезащитные', 'Для защиты глаз.', 50, 2500, 10, 9, 0, '2025-10-01'),
(139, 'Ремень кожаный', 'Универсальный аксессуар.', 35, 2000, 8, 9, 0, '2025-10-01'),
(140, 'Ключница', 'Для хранения ключей.', 60, 500, 5, 9, 0, '2025-10-01'),
(141, 'Сумка женская', 'Стильная и вместительная.', 45, 4500, 12, 9, 0, '2025-10-01'),
(142, 'Шляпа панама', 'Для солнечных дней.', 20, 1500, 10, 9, 0, '2025-10-01'),
(143, 'Перчатки кожаные', 'Стильные и теплые.', 30, 2000, 8, 9, 0, '2025-10-01'),
(144, 'Кошелек мужской', 'Для денег и карт.', 55, 2500, 10, 9, 0, '2025-10-01'),
(145, 'Браслет', 'Модный и стильный.', 70, 1800, 10, 9, 0, '2025-10-01'),
(146, 'Обложка для паспорта', 'Защитит и украсит.', 25, 600, 5, 9, 0, '2025-10-01'),
(147, 'Наручный браслет', 'Для украшения.', 80, 1500, 7, 9, 0, '2025-10-01'),
(148, 'Аксессуары для волос', 'Шпильки, резинки.', 90, 300, 5, 9, 0, '2025-10-01'),
(149, 'Стильный шарф', 'Для прохладной погоды.', 35, 1200, 8, 9, 0, '2025-10-01'),
(150, 'Головной убор', 'Для спортзала или прогулки.', 40, 1000, 5, 9, 0, '2025-10-01'),
(151, 'Значок', 'Стильный маленький аксессуар.', 100, 250, 3, 9, 0, '2025-10-01'),
(152, 'Увлажнитель для посуды', 'Быстро и качественно моет.', 30, 150, 10, 10, 0, '2025-10-01'),
(153, 'Средство для мытья окон', 'Не оставляет разводов.', 40, 200, 12, 10, 0, '2025-10-01'),
(154, 'Полироль для мебели', 'Придает блеск и защиту.', 25, 300, 15, 10, 0, '2025-10-01'),
(155, 'Отбеливатель', 'Для белого белья.', 50, 100, 8, 10, 0, '2025-10-01'),
(156, 'Средство для чистки ванн', 'От накипи и грязи.', 20, 180, 10, 10, 0, '2025-10-01'),
(157, 'Освежитель воздуха', 'Свежий аромат.', 60, 120, 10, 10, 0, '2025-10-01'),
(158, 'Средство для чистки кухни', 'Удаляет жир и грязь.', 35, 250, 12, 10, 0, '2025-10-01'),
(159, 'Средство для стирки', 'Для деликатных тканей.', 45, 350, 15, 10, 0, '2025-10-01'),
(160, 'Средство для ковров', 'Эффективная чистка.', 25, 400, 20, 10, 0, '2025-10-01'),
(161, 'Средство для унитаза', 'Обеззараживающее.', 30, 150, 8, 10, 0, '2025-10-01'),
(162, 'Емкость для хранения', 'Удобная емкость для бытовых средств.', 40, 300, 10, 10, 0, '2025-10-01'),
(163, 'Средство для удаления налета', 'Для чайников и кофеварок.', 15, 200, 12, 10, 0, '2025-10-01'),
(164, 'Гель для чистки сантехники', 'Быстро устраняет загрязнения.', 25, 180, 10, 10, 0, '2025-10-01'),
(165, 'Средство для чистки зеркал', 'Без разводов.', 30, 220, 11, 10, 0, '2025-10-01'),
(166, 'Очиститель для духовки', 'Удаляет жир и пригоревшую пищу.', 20, 400, 20, 10, 0, '2025-10-01');

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
(1, 19, '202606092305310.jpg'),
(2, 18, '202606092306040.jpg'),
(3, 38, '202606092306580.jpg'),
(4, 17, '202606092310250.jpg'),
(5, 15, '202606092310550.jpg'),
(6, 37, '202606092315200.jpg'),
(7, 36, '202606092315400.webp'),
(8, 72, '202606092320140.webp'),
(9, 91, '202606092321030.jpg'),
(10, 16, '202606151914370.jpg'),
(11, 14, '202606151915150.jpg'),
(12, 35, '202606151916180.jpg'),
(13, 33, '202606151917260.jpg'),
(14, 55, '202606151920320.jpg'),
(15, 54, '202606151923580.jpg'),
(16, 71, '202606151924280.jpg'),
(17, 69, '202606151925380.jpg'),
(18, 67, '202606151926360.jpg'),
(19, 90, '202606151927450.jpg'),
(20, 89, '202606151928450.jpg'),
(21, 88, '202606151929270.webp'),
(22, 87, '202606151929520.jpg'),
(23, 53, '202606152055550.webp'),
(24, 86, '202606152056110.jpeg'),
(25, 52, '202606152103250.jpg'),
(26, 51, '202606152103480.jpg'),
(27, 106, '202606152113100.jpg'),
(28, 105, '202606152114210.jpeg'),
(29, 104, '202606152114460.jpg'),
(30, 166, '202606152115110.webp'),
(31, 163, '202606152116070.jpg'),
(32, 162, '202606152116370.jpeg'),
(33, 161, '202606152117450.jpg'),
(34, 151, '202606152118290.jpg'),
(35, 103, '202606152118510.jpg'),
(36, 120, '202606152120080.jpg'),
(37, 119, '202606152120310.jpg'),
(38, 118, '202606152120580.jpg'),
(39, 148, '202606152121250.jpg'),
(40, 117, '202606152122120.jpg'),
(41, 149, '202606152122390.jpg'),
(42, 150, '202606152123310.jpg'),
(43, 136, '202606152124050.jpg'),
(44, 135, '202606152125140.jpg'),
(45, 102, '202606152125550.jpeg'),
(46, 147, '202606152126160.jpg'),
(47, 134, '202606152127040.webp'),
(48, 132, '202606152127260.jpg'),
(49, 101, '202606152128000.jpg'),
(50, 131, '202606152128140.jpg'),
(51, 133, '202606152128500.jpg'),
(52, 56, '202606152131050.jpg'),
(53, 165, '202606152337340.jpg'),
(54, 146, '202606211826550.jpg'),
(55, 116, '202606211829080.jpg'),
(56, 165, '202606211830090.jpg'),
(57, 121, '202606211830580.jpg'),
(58, 164, '202606211833510.jpg'),
(59, 34, '202606211834460.jpeg'),
(60, 68, '202606211837490.webp'),
(61, 70, '202606211845310.jpg');

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
  `name_items_type` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `items_type`
--

INSERT INTO `items_type` (`id_items_type`, `name_items_type`) VALUES
(1, 'Электроника'),
(2, 'Одежда'),
(3, 'Обувь'),
(4, 'Бытовая техника'),
(5, 'Спорт'),
(6, 'Аптека'),
(7, 'Товары для животных'),
(8, 'Мебель'),
(9, 'Аксессуары'),
(10, 'Бытовая химия');

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
(2, 'Тип питания'),
(3, 'Материал'),
(4, 'Стиль'),
(5, 'Тип обуви'),
(6, 'Материал верха'),
(7, 'Тип техники'),
(8, 'Объем'),
(9, 'Энергопотребление'),
(10, 'Тип спорта'),
(11, 'Тип продукта'),
(12, 'Дозировка'),
(13, 'Форма выпуска'),
(14, 'Назначение'),
(15, 'Вид животного'),
(16, 'Вес'),
(17, 'Тип мебели'),
(18, 'Тип аксессуара');

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
(1, 'admin@admin.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Арсений', NULL, NULL, '2025-10-01', 0, 1),
(2, 'user@user.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Михаил', NULL, NULL, '2025-10-01', 0, 2),
(3, 'ban@ban.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Егор', NULL, NULL, '2025-10-01', 1, 2),
(4, 'deliver@deliver.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Никита', NULL, NULL, '2025-10-01', 0, 3),
(5, 'support@support.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Фёдор', NULL, NULL, '2025-10-01', 0, 4);

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
  MODIFY `id_attributes` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
  MODIFY `id_items` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT для таблицы `items_images`
--
ALTER TABLE `items_images`
  MODIFY `id_items_images` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT для таблицы `items_properties`
--
ALTER TABLE `items_properties`
  MODIFY `id_items_properties` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `items_type`
--
ALTER TABLE `items_type`
  MODIFY `id_items_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `properties`
--
ALTER TABLE `properties`
  MODIFY `id_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id_users` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `attributes`
--
ALTER TABLE `attributes`
  ADD CONSTRAINT `attributes_ibfk_1` FOREIGN KEY (`properties_id_attributes`) REFERENCES `properties` (`id_properties`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `baskets`
--
ALTER TABLE `baskets`
  ADD CONSTRAINT `baskets_ibfk_1` FOREIGN KEY (`items_id_baskets`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `baskets_ibfk_2` FOREIGN KEY (`orders_id_baskets`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`users_id_comments`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`items_id_comments`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`items_id_favorites`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`users_id_favorites`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`items_type_id_items`) REFERENCES `items_type` (`id_items_type`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `items_images`
--
ALTER TABLE `items_images`
  ADD CONSTRAINT `items_images_ibfk_1` FOREIGN KEY (`items_id_items_images`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `items_properties`
--
ALTER TABLE `items_properties`
  ADD CONSTRAINT `items_properties_ibfk_1` FOREIGN KEY (`items_id_items_properties`) REFERENCES `items` (`id_items`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_properties_ibfk_2` FOREIGN KEY (`attributes_id_items_properties`) REFERENCES `attributes` (`id_attributes`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`status_id_orders`) REFERENCES `status` (`id_status`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`users_id_orders`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`users_deliver_orders`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `supports`
--
ALTER TABLE `supports`
  ADD CONSTRAINT `supports_ibfk_1` FOREIGN KEY (`talks_id_supports`) REFERENCES `talks` (`id_talks`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supports_ibfk_2` FOREIGN KEY (`users_write_supports`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `talks`
--
ALTER TABLE `talks`
  ADD CONSTRAINT `talks_ibfk_1` FOREIGN KEY (`users_id_talks`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `talks_ibfk_2` FOREIGN KEY (`users_support_talks`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roles_id_users`) REFERENCES `roles` (`id_roles`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
