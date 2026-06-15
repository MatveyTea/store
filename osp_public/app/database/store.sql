-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Июн 15 2026 г., 22:42
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
(252, 4, 'Черный'),
(253, 4, 'Белый'),
(254, 4, 'Синий'),
(255, 5, 'Аккумулятор'),
(256, 5, 'Блок питания'),
(263, 8, 'Хлопок'),
(264, 8, 'Полиэстер'),
(265, 9, 'Классический'),
(266, 9, 'Спортивный'),
(273, 12, 'Кроссовки'),
(274, 12, 'Туфли'),
(275, 12, 'Ботинки'),
(276, 13, 'Кожа'),
(277, 13, 'Текстиль'),
(282, 16, 'Холодильник'),
(283, 16, 'Стиральная машина'),
(284, 17, '300 л'),
(285, 17, '7 кг'),
(288, 19, 'A++'),
(289, 19, 'B'),
(290, 20, 'Футбол'),
(291, 20, 'Баскетбол'),
(292, 20, 'Теннис'),
(299, 24, 'Витамины'),
(300, 24, 'Обезболивающие'),
(301, 24, 'Средства для горла'),
(302, 25, '100 мг'),
(303, 25, '500 мг'),
(304, 26, 'Таблетки'),
(305, 26, 'Мази'),
(308, 28, 'Профилактика'),
(309, 28, 'Лечение'),
(310, 29, 'Собака'),
(311, 29, 'Кошка'),
(312, 29, 'Птицы'),
(315, 31, '1 кг'),
(316, 31, '500 г'),
(321, 34, 'Кровать'),
(322, 34, 'Стол'),
(323, 34, 'Шкаф'),
(333, 39, 'Очки'),
(334, 39, 'Часы'),
(335, 39, 'Ремень');

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
(308, 'Смарт-часы Model A', NULL, 50, 3000, 10, 1, 0, '2026-06-09'),
(309, 'Наушники Bluetooth', NULL, 80, 1500, 5, 1, 0, '2026-06-09'),
(310, 'Экшн-камера', NULL, 30, 9000, 15, 1, 0, '2026-06-09'),
(311, 'Портативный аккумулятор', NULL, 60, 2000, 20, 1, 0, '2026-06-09'),
(312, 'Умная колонка', NULL, 70, 4500, 10, 1, 0, '2026-06-09'),
(313, 'Электронная книга', NULL, 40, 6500, 8, 1, 0, '2026-06-09'),
(314, 'Игровая консоль', NULL, 20, 25000, 12, 1, 0, '2026-06-09'),
(315, 'Веб-камера', NULL, 55, 3000, 7, 1, 0, '2026-06-09'),
(316, 'Пылесос робот', NULL, 25, 18000, 20, 1, 0, '2026-06-09'),
(317, 'Беспроводной маршрутизатор', NULL, 45, 3500, 5, 1, 0, '2026-06-09'),
(318, 'Планшет', NULL, 35, 12000, 10, 1, 0, '2026-06-09'),
(319, 'Радио усилитель', NULL, 15, 5000, 15, 1, 0, '2026-06-09'),
(320, 'Магнитола', NULL, 25, 7500, 10, 1, 0, '2026-06-09'),
(321, 'Карта памяти 128GB', NULL, 100, 1200, 8, 1, 0, '2026-06-09'),
(322, 'Внешний жесткий диск 2TB', NULL, 50, 6000, 5, 1, 0, '2026-06-09'),
(323, 'USB-хаб', NULL, 65, 900, 3, 1, 0, '2026-06-09'),
(324, 'Bluetooth адаптер', NULL, 55, 1500, 4, 1, 0, '2026-06-09'),
(325, 'Настольный микрофон', NULL, 30, 4200, 6, 1, 0, '2026-06-09'),
(326, 'Wi-Fi адаптер', NULL, 40, 2000, 4, 1, 0, '2026-06-09'),
(327, 'Футболка Casual', NULL, 100, 1500, 5, 2, 0, '2026-06-09'),
(328, 'Джинсы Skinny', NULL, 80, 3500, 10, 2, 0, '2026-06-09'),
(329, 'Куртка пуховая', NULL, 40, 7000, 15, 2, 0, '2026-06-09'),
(330, 'Пиджак классический', NULL, 30, 8200, 0, 2, 0, '2026-06-09'),
(331, 'Платье вечернее', NULL, 20, 6200, 12, 2, 0, '2026-06-09'),
(332, 'Рубашка мужская', NULL, 60, 2000, 8, 2, 0, '2026-06-09'),
(333, 'Лосины утепленные', NULL, 70, 1800, 5, 2, 0, '2026-06-09'),
(334, 'Шорты летние', NULL, 50, 1200, 5, 2, 0, '2026-06-09'),
(335, 'Костюм спортивный', NULL, 30, 5600, 10, 2, 0, '2026-06-09'),
(336, 'Туфли кожаные', NULL, 25, 4500, 5, 2, 0, '2026-06-09'),
(337, 'Кроссовки спорт', NULL, 55, 5200, 7, 2, 0, '2026-06-09'),
(338, 'Шляпа летняя', NULL, 40, 800, 3, 2, 0, '2026-06-09'),
(339, 'Гольфы утеплённые', NULL, 65, 1100, 4, 2, 0, '2026-06-09'),
(340, 'Шарф шерстяной', NULL, 35, 1500, 6, 2, 0, '2026-06-09'),
(341, 'Пальто шерстяное', NULL, 15, 9500, 10, 2, 1, '2026-06-09'),
(342, 'Косоворотка', NULL, 45, 2100, 2, 2, 1, '2026-06-09'),
(344, 'Блузка летняя', NULL, 65, 2800, 4, 2, 0, '2026-06-09'),
(345, 'Трикотажные штаны', NULL, 55, 3300, 6, 2, 0, '2026-06-09'),
(346, 'Носки шерстяные', NULL, 150, 200, 1, 2, 0, '2026-06-09'),
(347, 'Кроссовки Nike', NULL, 60, 5200, 8, 3, 0, '2026-06-09'),
(348, 'Летние сандалии', NULL, 40, 1500, 5, 3, 0, '2026-06-09'),
(349, 'Ботинки зимние', NULL, 30, 9000, 12, 3, 0, '2026-06-09'),
(350, 'Туфли классические', NULL, 35, 7000, 10, 3, 0, '2026-06-09'),
(351, 'Эспадрильи для женщин', NULL, 25, 3000, 6, 3, 0, '2026-06-09'),
(352, 'Кеды Converse', NULL, 55, 4000, 4, 3, 0, '2026-06-09'),
(353, 'Мокасины кожаные', NULL, 20, 6500, 8, 3, 0, '2026-06-09'),
(354, 'Ботинки высокие', NULL, 25, 9500, 15, 3, 0, '2026-06-09'),
(355, 'Галоши резиновые', NULL, 70, 900, 3, 3, 0, '2026-06-09'),
(356, 'Топсайдеры', NULL, 50, 7200, 6, 3, 0, '2026-06-09'),
(357, 'Босоножки летние', NULL, 40, 3500, 4, 3, 0, '2026-06-09'),
(358, 'Мокасины для отдыха', NULL, 30, 6000, 5, 3, 0, '2026-06-09'),
(359, 'Кроссы для бега', NULL, 45, 5800, 7, 3, 0, '2026-06-09'),
(360, 'Туфли лакированные', NULL, 20, 9500, 9, 3, 0, '2026-06-09'),
(361, 'Обувь для тренировок', NULL, 55, 4800, 4, 3, 0, '2026-06-09'),
(362, 'Высокие сапоги', NULL, 15, 12000, 10, 3, 0, '2026-06-09'),
(363, 'Балетки кожаные', NULL, 45, 4200, 3, 3, 0, '2026-06-09'),
(364, 'Летние сабо', NULL, 40, 2500, 2, 3, 0, '2026-06-09'),
(365, 'Холодильник', NULL, 10, 30000, 10, 4, 0, '2026-06-09'),
(366, 'Микроволновая печь', NULL, 20, 9500, 8, 4, 0, '2026-06-09'),
(367, 'Праска', NULL, 30, 3200, 5, 4, 0, '2026-06-09'),
(368, 'Пылесос', NULL, 25, 8500, 7, 4, 0, '2026-06-09'),
(369, 'Кухонный комбайн', NULL, 15, 15000, 12, 4, 0, '2026-06-09'),
(370, 'Тостер', NULL, 35, 4200, 4, 4, 0, '2026-06-09'),
(371, 'Электрическая плита', NULL, 8, 22000, 15, 4, 0, '2026-06-09'),
(372, 'Стайлер для волос', NULL, 45, 3200, 6, 4, 0, '2026-06-09'),
(373, 'Кондиционер', NULL, 12, 25000, 10, 4, 0, '2026-06-09'),
(374, 'Фен', NULL, 50, 1800, 3, 4, 0, '2026-06-09'),
(375, 'Электрический чайник', NULL, 70, 2000, 1, 4, 0, '2026-06-09'),
(376, 'Мультиварка', NULL, 25, 9000, 8, 4, 0, '2026-06-09'),
(377, 'Блендер', NULL, 60, 2500, 5, 4, 0, '2026-06-09'),
(378, 'Сушилка для овощей', NULL, 10, 8500, 14, 4, 0, '2026-06-09'),
(379, 'Кофемашина', NULL, 12, 15000, 6, 4, 0, '2026-06-09'),
(380, 'Обогреватель', NULL, 20, 6000, 13, 4, 0, '2026-06-09'),
(381, 'Гантели 10 кг', NULL, 35, 2500, 10, 5, 0, '2026-06-09'),
(382, 'Коврик для йоги', NULL, 50, 1200, 5, 5, 0, '2026-06-09'),
(383, 'Беговая дорожка', NULL, 8, 45000, 12, 5, 0, '2026-06-09'),
(384, 'Велосипед городской', NULL, 15, 22000, 15, 5, 0, '2026-06-09'),
(385, 'Ракетка для тенниса', NULL, 25, 3000, 8, 5, 0, '2026-06-09'),
(386, 'Мяч футбольный', NULL, 80, 1500, 3, 5, 0, '2026-06-09'),
(387, 'Эспандер', NULL, 60, 850, 2, 5, 0, '2026-06-09'),
(388, 'Шлем велосипедный', NULL, 20, 3500, 4, 5, 0, '2026-06-09'),
(389, 'Бутылка для воды', NULL, 70, 300, 1, 5, 0, '2026-06-09'),
(390, 'Пояс для похудения', NULL, 40, 2500, 7, 5, 0, '2026-06-09'),
(391, 'Защитные налокотники', NULL, 35, 1500, 2, 5, 0, '2026-06-09'),
(392, 'Ватрушка для зимних игр', NULL, 12, 4000, 5, 5, 0, '2026-06-09'),
(393, 'Гири 20 кг', NULL, 18, 5000, 6, 5, 0, '2026-06-09'),
(394, 'Банджи для прыжков', NULL, 10, 3000, 4, 5, 0, '2026-06-09'),
(395, 'Мяч для волейбола', NULL, 55, 1800, 3, 5, 0, '2026-06-09'),
(396, 'Экипировка для фитнеса', NULL, 25, 6000, 9, 5, 0, '2026-06-09'),
(397, 'Одежда для плавания', NULL, 45, 2000, 2, 5, 0, '2026-06-09'),
(398, 'Наколенники для велосипеда', NULL, 30, 2200, 3, 5, 0, '2026-06-09'),
(399, 'Массажный ролл', NULL, 40, 1500, 2, 5, 0, '2026-06-09'),
(529, 'Витамины C', 'Пищевая добавка с высоким содержанием витамина C.', 50, 200, 10, 7, 0, '2026-06-15'),
(530, 'Обезболивающее Парацетамол', 'Лекарство для снижения температуры и боли.', 30, 50, 5, 7, 0, '2026-06-15'),
(531, 'Леденцы от кашля', 'Средство для облегчения симптомов кашля.', 100, 150, 15, 7, 0, '2026-06-15'),
(532, 'Мазь от ожогов', 'Средство для быстрого заживления ожогов.', 20, 250, 20, 7, 0, '2026-06-15'),
(533, 'Назальные капли', 'Средство для устранения заложенности носа.', 40, 180, 10, 7, 0, '2026-06-15'),
(534, 'Противогрибковое крем', 'Лечение грибковых инфекций кожи.', 25, 300, 15, 7, 0, '2026-06-15'),
(535, 'Глазные капли для глаз', 'Успокаивающее средство для глаз.', 35, 220, 12, 7, 0, '2026-06-15'),
(536, 'Средство от простуды', 'Таблетки для симптоматического лечения простуды.', 60, 80, 8, 7, 0, '2026-06-15'),
(537, 'Бальзам для горла', 'Облегчение боли в горле.', 45, 130, 10, 7, 0, '2026-06-15'),
(538, 'Противопаразитарные капли', 'Средство против паразитов.', 15, 350, 25, 7, 0, '2026-06-15'),
(539, 'Крем от дерматита', 'Лечение кожных воспалений.', 30, 270, 15, 7, 0, '2026-06-15'),
(540, 'Витамины для детей', 'Омега-кислоты и витамины для малышей.', 40, 400, 20, 7, 0, '2026-06-15'),
(541, 'Спиртовые тампоны', 'Для обработки раны.', 50, 30, 5, 7, 0, '2026-06-15'),
(542, 'Средство для носа', 'Увлажняющий спрей для носа.', 55, 210, 10, 7, 0, '2026-06-15'),
(543, 'Мазь для суставов', 'Обезболивающее средство для суставов.', 20, 330, 18, 7, 0, '2026-06-15'),
(544, 'Сухой корм для собак', 'Питание для взрослых собак.', 70, 5000, 10, 8, 0, '2026-06-15'),
(545, 'Корм для кошек', 'Высококачественный корм для кошек.', 60, 6000, 12, 8, 0, '2026-06-15'),
(546, 'Игрушка для собак', 'Игрушка из прочного материала.', 80, 200, 8, 8, 0, '2026-06-15'),
(547, 'Средство против блох', 'Защита от паразитов.', 40, 350, 15, 8, 0, '2026-06-15'),
(548, 'Когтерез для котов', 'Удобный инструмент для ухода.', 15, 150, 5, 8, 0, '2026-06-15'),
(549, 'Лакомство для собак', 'Вкусные косточки и награды.', 100, 120, 10, 8, 0, '2026-06-15'),
(550, 'Средство для чистки ушей', 'Уход за ушами питомца.', 25, 180, 10, 8, 0, '2026-06-15'),
(551, 'Аппликация для глистов', 'Средство для профилактики глистов.', 30, 250, 20, 8, 0, '2026-06-15'),
(552, 'Туалет для кошек', 'Удобное лотко с наполнителем.', 45, 400, 15, 8, 0, '2026-06-15'),
(553, 'Мыло для питомцев', 'Гипоаллергенное средство.', 20, 130, 5, 8, 0, '2026-06-15'),
(554, 'Шлейка для собак', 'Надежная и удобная.', 50, 350, 12, 8, 0, '2026-06-15'),
(555, 'Кормушка для птиц', 'Для домашних птиц.', 60, 80, 7, 8, 0, '2026-06-15'),
(556, 'Средство для ухода за шерстью', 'Шампунь и кондиционер.', 35, 220, 10, 8, 0, '2026-06-15'),
(557, 'Аптечка для животных', 'Основные медикаменты для питомца.', 10, 1000, 25, 8, 0, '2026-06-15'),
(558, 'Игрушка для кроликов', 'Забавная для мелких грызунов.', 75, 90, 8, 8, 0, '2026-06-15'),
(559, 'Кровать двуспальная', 'Удобная кровать с мягким матрасом.', 10, 5000, 15, 10, 0, '2026-06-15'),
(560, 'Стол офисный', 'Для работы и учебы.', 20, 3000, 10, 10, 0, '2026-06-15'),
(561, 'Шкаф-купе', 'Для хранения одежды.', 5, 15000, 20, 10, 0, '2026-06-15'),
(562, 'Комод', 'Для хранения одежды и мелочей.', 15, 4000, 12, 10, 0, '2026-06-15'),
(563, 'Стул компьютерный', 'Эргономичный и удобный.', 25, 1500, 8, 10, 0, '2026-06-15'),
(564, 'Тумба под ТВ', 'Для размещения техники.', 12, 3500, 10, 10, 0, '2026-06-15'),
(565, 'Кухонный стол', 'Для семейных обедов.', 8, 2500, 10, 10, 0, '2026-06-15'),
(566, 'Диван угловой', 'Для гостиной.', 4, 20000, 18, 10, 0, '2026-06-15'),
(567, 'Кресло мягкое', 'Комфортное для отдыха.', 10, 7000, 15, 10, 0, '2026-06-15'),
(568, 'Полка настенная', 'Для книг и декора.', 18, 800, 10, 10, 0, '2026-06-15'),
(569, 'Зеркало настенное', 'Вписывается в любой интерьер.', 14, 2500, 10, 10, 0, '2026-06-15'),
(570, 'Стеллаж книжный', 'Для хранения книг.', 9, 4000, 12, 10, 0, '2026-06-15'),
(571, 'Кухонная тумба', 'Дополнительное пространство хранения.', 13, 6000, 10, 10, 0, '2026-06-15'),
(572, 'Трансформер кровать', 'Многофункциональный спальный комплекс.', 6, 15000, 17, 10, 0, '2026-06-15'),
(573, 'Письменный стол', 'Для учебы и работы.', 20, 3500, 10, 10, 0, '2026-06-15'),
(574, 'Часы наручные', 'Стильные и современные.', 40, 3000, 15, 12, 0, '2026-06-15'),
(575, 'Очки солнцезащитные', 'Для защиты глаз.', 50, 2500, 10, 12, 0, '2026-06-15'),
(576, 'Ремень кожаный', 'Универсальный аксессуар.', 35, 2000, 8, 12, 0, '2026-06-15'),
(577, 'Ключница', 'Для хранения ключей.', 60, 500, 5, 12, 0, '2026-06-15'),
(578, 'Сумка женская', 'Стильная и вместительная.', 45, 4500, 12, 12, 0, '2026-06-15'),
(579, 'Шляпа панама', 'Для солнечных дней.', 20, 1500, 10, 12, 0, '2026-06-15'),
(580, 'Перчатки кожаные', 'Стильные и теплые.', 30, 2000, 8, 12, 0, '2026-06-15'),
(581, 'Кошелек мужской', 'Для денег и карт.', 55, 2500, 10, 12, 0, '2026-06-15'),
(582, 'Браслет', 'Модный и стильный.', 70, 1800, 10, 12, 0, '2026-06-15'),
(583, 'Обложка для паспорта', 'Защитит и украсит.', 25, 600, 5, 12, 0, '2026-06-15'),
(584, 'Наручный браслет', 'Для украшения.', 80, 1500, 7, 12, 0, '2026-06-15'),
(585, 'Аксессуары для волос', 'Шпильки, резинки.', 90, 300, 5, 12, 0, '2026-06-15'),
(586, 'Стильный шарф', 'Для прохладной погоды.', 35, 1200, 8, 12, 0, '2026-06-15'),
(587, 'Головной убор', 'Для спортзала или прогулки.', 40, 1000, 5, 12, 0, '2026-06-15'),
(588, 'Значок', 'Стильный маленький аксессуар.', 100, 250, 3, 12, 0, '2026-06-15'),
(589, 'Увлажнитель для посуды', 'Быстро и качественно моет.', 30, 150, 10, 15, 0, '2026-06-15'),
(590, 'Средство для мытья окон', 'Не оставляет разводов.', 40, 200, 12, 15, 0, '2026-06-15'),
(591, 'Полироль для мебели', 'Придает блеск и защиту.', 25, 300, 15, 15, 0, '2026-06-15'),
(592, 'Отбеливатель', 'Для белого белья.', 50, 100, 8, 15, 0, '2026-06-15'),
(593, 'Средство для чистки ванн', 'От накипи и грязи.', 20, 180, 10, 15, 0, '2026-06-15'),
(594, 'Освежитель воздуха', 'Свежий аромат.', 60, 120, 10, 15, 0, '2026-06-15'),
(595, 'Средство для чистки кухни', 'Удаляет жир и грязь.', 35, 250, 12, 15, 0, '2026-06-15'),
(596, 'Средство для стирки', 'Для деликатных тканей.', 45, 350, 15, 15, 0, '2026-06-15'),
(597, 'Средство для ковров', 'Эффективная чистка.', 25, 400, 20, 15, 0, '2026-06-15'),
(598, 'Средство для унитаза', 'Обеззараживающее.', 30, 150, 8, 15, 0, '2026-06-15'),
(599, 'Емкость для хранения', 'Удобная емкость для бытовых средств.', 40, 300, 10, 15, 0, '2026-06-15'),
(600, 'Средство для удаления налета', 'Для чайников и кофеварок.', 15, 200, 12, 15, 0, '2026-06-15'),
(601, 'Гель для чистки сантехники', 'Быстро устраняет загрязнения.', 25, 180, 10, 15, 0, '2026-06-15'),
(602, 'Средство для чистки зеркал', 'Без разводов.', 30, 220, 11, 15, 0, '2026-06-15'),
(603, 'Очиститель для духовки', 'Удаляет жир и пригоревшую пищу.', 20, 400, 20, 15, 0, '2026-06-15');

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
(1, 326, '202606092305310.jpg'),
(2, 325, '202606092306040.jpg'),
(3, 346, '202606092306580.jpg'),
(10, 324, '202606092310250.jpg'),
(11, 322, '202606092310550.jpg'),
(12, 345, '202606092315200.jpg'),
(13, 344, '202606092315400.webp'),
(14, 380, '202606092320140.webp'),
(15, 399, '202606092321030.jpg'),
(24, 323, '202606151914370.jpg'),
(25, 321, '202606151915150.jpg'),
(27, 342, '202606151916180.jpg'),
(28, 341, '202606151916490.jpg'),
(29, 340, '202606151917260.jpg'),
(30, 363, '202606151920320.jpg'),
(31, 362, '202606151923580.jpg'),
(32, 379, '202606151924280.jpg'),
(33, 378, '202606151925190.webp'),
(34, 377, '202606151925380.jpg'),
(35, 376, '202606151926010.jpg'),
(36, 375, '202606151926360.jpg'),
(37, 398, '202606151927450.jpg'),
(38, 397, '202606151928450.jpg'),
(39, 396, '202606151929270.webp'),
(40, 395, '202606151929520.jpg'),
(41, 361, '202606152055550.webp'),
(42, 394, '202606152056110.jpeg'),
(44, 360, '202606152103250.jpg'),
(45, 359, '202606152103480.jpg'),
(46, 543, '202606152113100.jpg'),
(47, 542, '202606152114210.jpeg'),
(48, 541, '202606152114460.jpg'),
(49, 603, '202606152115110.webp'),
(50, 602, '202606152115290.webp'),
(51, 601, '202606152115460.jpg'),
(52, 600, '202606152116070.jpg'),
(53, 599, '202606152116370.jpeg'),
(54, 598, '202606152117450.jpg'),
(55, 588, '202606152118290.jpg'),
(56, 540, '202606152118510.jpg'),
(57, 558, '202606152119320.jpg'),
(58, 557, '202606152120080.jpg'),
(59, 556, '202606152120310.jpg'),
(60, 555, '202606152120580.jpg'),
(61, 585, '202606152121250.jpg'),
(62, 583, '202606152121550.jpg'),
(63, 554, '202606152122120.jpg'),
(64, 586, '202606152122390.jpg'),
(65, 553, '202606152122580.jpg'),
(66, 587, '202606152123310.jpg'),
(67, 573, '202606152124050.jpg'),
(68, 572, '202606152125140.jpg'),
(69, 539, '202606152125550.jpeg'),
(70, 584, '202606152126160.jpg'),
(71, 571, '202606152127040.webp'),
(72, 569, '202606152127260.jpg'),
(73, 538, '202606152128000.jpg'),
(74, 568, '202606152128140.jpg'),
(75, 570, '202606152128500.jpg'),
(76, 364, '202606152131050.jpg');

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
(7, 'Аптека'),
(8, 'Товары для животных'),
(10, 'Мебель'),
(12, 'Аксессуары'),
(15, 'Бытовая химия');

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
(4, 'Цвет'),
(5, 'Тип питания'),
(8, 'Материал'),
(9, 'Стиль'),
(12, 'Тип обуви'),
(13, 'Материал верха'),
(16, 'Тип техники'),
(17, 'Объем'),
(19, 'Энергопотребление'),
(20, 'Тип спорта'),
(24, 'Тип продукта'),
(25, 'Дозировка'),
(26, 'Форма выпуска'),
(28, 'Назначение'),
(29, 'Вид животного'),
(31, 'Вес'),
(34, 'Тип мебели'),
(39, 'Тип аксессуара');

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
(1, 'admin@admin.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Администратор', NULL, NULL, '2025-11-30', 0, 1),
(2, 'user@user.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Пользователь', NULL, NULL, '2025-11-30', 0, 2),
(3, 'ban@ban.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Заблокированный', NULL, NULL, '2025-11-30', 1, 2),
(4, 'deliverOne@deliver.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Доставщик один', NULL, NULL, '2025-11-30', 0, 3),
(5, 'deliverTwp@deliver.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Доставщик два', NULL, NULL, '2025-11-30', 0, 3),
(6, 'support@support.com', '$2y$12$lJbCbUGrxndLxrkAiZmazus4gXdSlN3dM3huUAt4j5g7Co68nsyb6', 'Поддрежка', NULL, NULL, '2025-11-30', 0, 4);

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
  MODIFY `id_attributes` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `baskets`
--
ALTER TABLE `baskets`
  MODIFY `id_baskets` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comments` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id_favorites` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id_items` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=604;

--
-- AUTO_INCREMENT для таблицы `items_images`
--
ALTER TABLE `items_images`
  MODIFY `id_items_images` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT для таблицы `items_properties`
--
ALTER TABLE `items_properties`
  MODIFY `id_items_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `items_type`
--
ALTER TABLE `items_type`
  MODIFY `id_items_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `properties`
--
ALTER TABLE `properties`
  MODIFY `id_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id_supports` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `talks`
--
ALTER TABLE `talks`
  MODIFY `id_talks` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

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
