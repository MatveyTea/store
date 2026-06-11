-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Июн 11 2026 г., 20:08
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
(1, 1, 'Розовый'),
(2, 1, 'Синий'),
(3, 1, 'Зеленый'),
(4, 1, 'Белый'),
(5, 1, 'Черный'),
(6, 2, 'Дерево'),
(7, 2, 'Пластик'),
(8, 2, 'Металл'),
(9, 2, 'Стекло'),
(10, 2, 'Ткань'),
(11, 3, 'Маленький'),
(12, 3, 'Средний'),
(13, 3, 'Большой'),
(14, 4, 'Легкий'),
(15, 4, 'Средний'),
(16, 4, 'Тяжелый'),
(20, 6, 'Россия'),
(21, 6, 'Китай'),
(22, 6, 'Германия'),
(23, 6, 'Италия'),
(25, 7, 'Нейтральная'),
(26, 7, 'Яркая'),
(27, 7, 'Пастельная'),
(28, 8, '250 мл'),
(29, 8, '500 мл'),
(30, 8, '1 л'),
(31, 8, '2 л'),
(32, 9, 'Кухня'),
(33, 9, 'Гостиная'),
(34, 9, 'Спальня'),
(35, 9, 'Ванная'),
(36, 10, 'Высокая'),
(37, 10, 'Средняя'),
(38, 10, 'Низкая'),
(39, 11, 'Высокая'),
(40, 11, 'Средняя'),
(41, 11, 'Низкая'),
(42, 12, 'Тихо'),
(43, 12, 'Средне'),
(44, 12, 'Шумно'),
(48, 14, 'Компания А'),
(49, 14, 'Компания Б'),
(50, 14, 'Индивидуальный бренд'),
(51, 15, 'Кронштейн'),
(52, 15, 'Клей'),
(53, 15, 'Шурупы'),
(54, 15, 'Зажимы'),
(57, 7, 'Холодная');

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
(341, 'Пальто шерстяное', NULL, 15, 9500, 10, 2, 0, '2026-06-09'),
(342, 'Косоворотка', NULL, 45, 2100, 2, 2, 0, '2026-06-09'),
(343, 'Кошачьи тапки', NULL, 70, 900, 1, 2, 0, '2026-06-09'),
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
(400, 'Тайм-менеджмент для всех', NULL, 45, 800, 5, 6, 0, '2026-06-09'),
(401, 'Книга о программировании', NULL, 60, 1200, 10, 6, 0, '2026-06-09'),
(402, 'Блокнот Микс', NULL, 100, 150, 3, 6, 0, '2026-06-09'),
(403, 'Ручка автоматическая', NULL, 200, 300, 1, 6, 0, '2026-06-09'),
(404, 'Папка для документов', NULL, 70, 400, 2, 6, 0, '2026-06-09'),
(405, 'Карандаши цветные', NULL, 150, 250, 3, 6, 0, '2026-06-09'),
(406, 'Линейка 30 см', NULL, 110, 150, 1, 6, 0, '2026-06-09'),
(407, 'Настольная лампа', NULL, 35, 2200, 4, 6, 0, '2026-06-09'),
(408, 'Подставка для книг', NULL, 50, 1200, 2, 6, 0, '2026-06-09'),
(409, 'Тетрадь ASH', NULL, 80, 150, 1, 6, 0, '2026-06-09'),
(410, 'Калькулятор настольный', NULL, 20, 3000, 5, 6, 0, '2026-06-09'),
(411, 'Цветные маркеры', NULL, 90, 600, 2, 6, 0, '2026-06-09'),
(412, 'Повербанк', NULL, 45, 4500, 4, 6, 0, '2026-06-09'),
(413, 'Папка-простакан', NULL, 55, 850, 1, 6, 0, '2026-06-09'),
(414, 'Планинг ежедневник', NULL, 65, 950, 3, 6, 0, '2026-06-09'),
(415, 'Декоративные скрепки', NULL, 120, 200, 1, 6, 0, '2026-06-09'),
(416, 'Скрапбукинг набор', NULL, 25, 550, 2, 6, 0, '2026-06-09'),
(417, 'Бумага для принтера', NULL, 95, 600, 2, 6, 0, '2026-06-09'),
(418, 'Клей-карандаш', NULL, 70, 300, 1, 6, 0, '2026-06-09'),
(419, 'Мягкий плюшевый медведь', NULL, 60, 1500, 5, 7, 0, '2026-06-09'),
(420, 'Конструктор LEGO', NULL, 40, 3500, 10, 7, 0, '2026-06-09'),
(421, 'Кукла Baby Doll', NULL, 50, 2500, 7, 7, 0, '2026-06-09'),
(422, 'Игровой набор ДПС', NULL, 30, 4000, 5, 7, 0, '2026-06-09'),
(423, 'Машинка радиоуправляемая', NULL, 25, 6000, 8, 7, 0, '2026-06-09'),
(424, 'Пазлы 1000 элементов', NULL, 55, 1200, 3, 7, 0, '2026-06-09'),
(425, 'Кахле для малышей', NULL, 45, 2200, 4, 7, 0, '2026-06-09'),
(426, 'Пирамидка деревянная', NULL, 70, 800, 2, 7, 0, '2026-06-09'),
(427, 'Настольная игра', NULL, 65, 1500, 4, 7, 0, '2026-06-09'),
(428, 'Мячи для детей', NULL, 80, 800, 1, 7, 0, '2026-06-09'),
(429, 'Интерактивная книга', NULL, 35, 1700, 5, 7, 0, '2026-06-09'),
(430, 'Ролики для малышей', NULL, 30, 3500, 6, 7, 0, '2026-06-09'),
(431, 'Пальчиковые краски', NULL, 90, 600, 2, 7, 0, '2026-06-09'),
(432, 'Мягкий уголок', NULL, 45, 6000, 9, 7, 0, '2026-06-09'),
(433, 'Набор для творчества', NULL, 55, 1700, 3, 7, 0, '2026-06-09'),
(434, 'Модель автомобиля из пластмассы', NULL, 50, 1200, 2, 7, 0, '2026-06-09'),
(435, 'Палитра для рисования', NULL, 65, 900, 1, 7, 0, '2026-06-09'),
(436, 'Касса для игрушек', NULL, 40, 2400, 4, 7, 0, '2026-06-09'),
(437, 'Погремушка', NULL, 80, 700, 2, 7, 0, '2026-06-09'),
(438, 'Серебряное кольцо', NULL, 20, 3500, 10, 8, 0, '2026-06-09'),
(439, 'Шерстяной платок', NULL, 65, 2200, 5, 8, 0, '2026-06-09'),
(440, 'Гарнитура серег', NULL, 50, 1800, 7, 8, 0, '2026-06-09'),
(441, 'Очки солнцезащитные', NULL, 70, 2500, 8, 8, 0, '2026-06-09'),
(442, 'Часы наручные', NULL, 30, 12000, 5, 8, 0, '2026-06-09'),
(443, 'Бижутерия набор', NULL, 40, 2000, 4, 8, 0, '2026-06-09'),
(444, 'Лента для волос', NULL, 80, 900, 2, 8, 0, '2026-06-09'),
(445, 'Браслет кожаный', NULL, 55, 2500, 6, 8, 0, '2026-06-09'),
(446, 'Кошелек кожаный', NULL, 25, 5500, 9, 8, 0, '2026-06-09'),
(447, 'Заколки декоративные', NULL, 90, 700, 2, 8, 0, '2026-06-09'),
(448, 'Брелок кличка', NULL, 45, 900, 1, 8, 0, '2026-06-09'),
(449, 'Эспандер для запястья', NULL, 35, 1500, 3, 8, 0, '2026-06-09'),
(450, 'Ободок украшенный', NULL, 60, 1400, 4, 8, 0, '2026-06-09'),
(451, 'Перстень с камнем', NULL, 20, 7500, 11, 8, 0, '2026-06-09'),
(452, 'Смартфон XPhone', NULL, 15, 30000, 10, 9, 0, '2026-06-09'),
(453, 'Наушники беспроводные', NULL, 40, 5000, 14, 9, 0, '2026-06-09'),
(454, 'Планшет 10\"', NULL, 20, 20000, 15, 9, 0, '2026-06-09'),
(455, 'Флешка 128 ГБ', NULL, 100, 600, 8, 9, 0, '2026-06-09'),
(456, 'Умные часы', NULL, 25, 15000, 13, 9, 0, '2026-06-09'),
(457, 'Портативная колонка', NULL, 35, 8000, 12, 9, 0, '2026-06-09'),
(458, 'Веб-камера HD', NULL, 45, 3500, 10, 9, 0, '2026-06-09'),
(459, 'Блок питания 65W', NULL, 30, 4000, 11, 9, 0, '2026-06-09'),
(460, 'Электронная книга', NULL, 22, 9000, 9, 9, 0, '2026-06-09'),
(461, 'Мышь беспроводная', NULL, 33, 2500, 7, 9, 0, '2026-06-09'),
(462, 'Кардридер USB', NULL, 70, 1500, 5, 9, 0, '2026-06-09'),
(463, 'Кабель USB-C', NULL, 100, 800, 3, 9, 0, '2026-06-09'),
(464, 'Камера видеонаблюдения', NULL, 18, 12000, 10, 9, 0, '2026-06-09'),
(465, 'Экшн-камера', NULL, 15, 25000, 12, 9, 0, '2026-06-09'),
(466, 'Powerbank 20000 мАч', NULL, 50, 4000, 9, 9, 0, '2026-06-09'),
(467, 'Джойстик для ПК', NULL, 28, 3500, 8, 9, 0, '2026-06-09'),
(468, 'Кейс для ноутбука', NULL, 40, 3200, 7, 9, 0, '2026-06-09'),
(469, 'Внешний жесткий диск 1 ТВ', NULL, 16, 4500, 10, 9, 0, '2026-06-09'),
(470, 'USB-хаб на 4 порта', NULL, 60, 1800, 6, 9, 0, '2026-06-09'),
(471, 'Настенные часы', NULL, 25, 3500, 10, 10, 0, '2026-06-09'),
(472, 'Коврик для ванной', NULL, 40, 1200, 8, 10, 0, '2026-06-09'),
(473, 'Плед шерстяной', NULL, 30, 4200, 12, 10, 0, '2026-06-09'),
(474, 'Комплект постельного белья', NULL, 50, 3500, 15, 10, 0, '2026-06-09'),
(475, 'Декоративная подушка', NULL, 55, 1500, 9, 10, 0, '2026-06-09'),
(476, 'Настольная лампа', NULL, 35, 2200, 11, 10, 0, '2026-06-09'),
(477, 'Рамка для фото', NULL, 70, 900, 7, 10, 0, '2026-06-09'),
(478, 'Ароматическая свеча', NULL, 80, 600, 5, 10, 0, '2026-06-09'),
(479, 'Шторы blackout', NULL, 20, 6000, 13, 10, 0, '2026-06-09'),
(480, 'Текстиль для кухни', NULL, 65, 2500, 10, 10, 0, '2026-06-09'),
(481, 'Зонтик комнатный', NULL, 18, 2000, 6, 10, 0, '2026-06-09'),
(482, 'Органайзер для белья', NULL, 45, 1400, 9, 10, 0, '2026-06-09'),
(483, 'Ваза керамическая', NULL, 22, 2400, 8, 10, 0, '2026-06-09'),
(484, 'Ковровая дорожка', NULL, 30, 3500, 10, 10, 0, '2026-06-09'),
(485, 'Постер на стену', NULL, 90, 700, 4, 10, 0, '2026-06-09'),
(486, 'Подхват для штор', NULL, 85, 600, 5, 10, 0, '2026-06-09'),
(487, 'Кухонный набор', NULL, 50, 4000, 9, 10, 0, '2026-06-09'),
(488, 'Электрический обогреватель', NULL, 12, 8500, 12, 10, 0, '2026-06-09'),
(489, 'Беспроводной пылесос', NULL, 22, 28000, 15, 10, 0, '2026-06-09'),
(490, 'Увлажнитель воздуха', NULL, 20, 5500, 10, 11, 0, '2026-06-09'),
(491, 'Электрическая щетка для зубов', NULL, 35, 7000, 14, 11, 0, '2026-06-09'),
(492, 'Массажер для лица', NULL, 25, 6500, 12, 11, 0, '2026-06-09'),
(493, 'Косметический набор', NULL, 50, 3000, 11, 11, 0, '2026-06-09'),
(494, 'Маска для лица', NULL, 70, 1500, 9, 11, 0, '2026-06-09'),
(495, 'Паровые брашинг', NULL, 15, 12000, 13, 11, 0, '2026-06-09'),
(496, 'Набор для маникюра', NULL, 40, 3500, 10, 11, 0, '2026-06-09'),
(497, 'Био-сыворотка для кожи', NULL, 30, 9500, 12, 11, 0, '2026-06-09'),
(498, 'Гигиеническая помада', NULL, 85, 600, 7, 11, 0, '2026-06-09'),
(499, 'Гитара акустическая', NULL, 10, 15000, 15, 13, 0, '2026-06-09'),
(500, 'Фитнес-коврик', NULL, 45, 2500, 10, 13, 0, '2026-06-09'),
(501, 'Беговая дорожка', NULL, 5, 35000, 20, 13, 0, '2026-06-09'),
(502, 'Сноуборд', NULL, 8, 25000, 25, 13, 0, '2026-06-09'),
(503, 'Набор для тенниса', NULL, 30, 7000, 12, 13, 0, '2026-06-09'),
(504, 'Теннисные ракетки', NULL, 20, 12000, 18, 13, 0, '2026-06-09'),
(505, 'Планшет для велосипеда', NULL, 12, 10000, 14, 13, 0, '2026-06-09'),
(506, 'Ролики', NULL, 25, 8500, 16, 13, 0, '2026-06-09'),
(507, 'Защитный шлем', NULL, 18, 5000, 8, 13, 0, '2026-06-09'),
(508, 'Комплект для йоги', NULL, 50, 3000, 5, 11, 0, '2026-06-09'),
(509, 'Детская коляска', NULL, 7, 25000, 20, 14, 0, '2026-06-09'),
(510, 'Игровой набор конструктора', NULL, 35, 4500, 15, 14, 0, '2026-06-09'),
(511, 'Мягкая игрушка', NULL, 80, 800, 10, 14, 0, '2026-06-09'),
(512, 'Детский кровать', NULL, 6, 15000, 18, 14, 0, '2026-06-09'),
(513, 'Развивающие книги', NULL, 60, 2000, 12, 14, 0, '2026-06-09'),
(514, 'Парка зимняя для малыша', NULL, 12, 7000, 17, 14, 0, '2026-06-09'),
(515, 'Детская кухня', NULL, 8, 12000, 14, 14, 0, '2026-06-09'),
(516, 'Игрушка-мобиль', NULL, 50, 2500, 9, 14, 0, '2026-06-09'),
(517, 'Пазлы для детей', NULL, 40, 1500, 11, 14, 0, '2026-06-09'),
(518, 'Термос для детей', NULL, 30, 1800, 13, 14, 0, '2026-06-09'),
(519, 'Набор ножей', NULL, 15, 4000, 20, 15, 0, '2026-06-09'),
(520, 'Миска металлическая', NULL, 50, 500, 10, 15, 0, '2026-06-09'),
(521, 'Чайник электрический', NULL, 20, 3500, 15, 15, 0, '2026-06-09'),
(522, 'Кухонная доска', NULL, 70, 1200, 12, 15, 0, '2026-06-09'),
(523, 'Терка для овощей', NULL, 80, 900, 8, 15, 0, '2026-06-09'),
(524, 'Миксер ручной', NULL, 25, 2500, 14, 15, 0, '2026-06-09'),
(525, 'Сито металлическое', NULL, 60, 800, 9, 15, 0, '2026-06-09'),
(526, 'Формы для выпечки', NULL, 45, 1500, 11, 15, 0, '2026-06-09'),
(527, 'Кастрюля', NULL, 12, 6000, 13, 15, 0, '2026-06-09'),
(528, 'Термос', NULL, 20, 1800, 17, 15, 0, '2026-06-09');

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
(4, 528, '202606092307300.jpg'),
(5, 527, '202606092307490.jpg'),
(6, 526, '202606092308120.jpg'),
(7, 525, '202606092308340.webp'),
(8, 524, '202606092308550.jpg'),
(9, 523, '202606092309290.jpg'),
(10, 324, '202606092310250.jpg'),
(11, 322, '202606092310550.jpg'),
(12, 345, '202606092315200.jpg'),
(13, 344, '202606092315400.webp'),
(14, 380, '202606092320140.webp'),
(15, 399, '202606092321030.jpg'),
(16, 437, '202606092321240.jpg'),
(17, 364, '202606092322520.jpg'),
(18, 498, '202606092323300.jpg'),
(19, 484, '202606092325330.webp'),
(20, 418, '202606092326530.jpg'),
(21, 508, '202606092328320.jpg'),
(22, 503, '202606092329270.jpg');

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
(5, 'Спорт и отдых'),
(6, 'Продукты питания'),
(7, 'Аптека'),
(8, 'Товары для животных'),
(9, 'Книги'),
(10, 'Мебель'),
(11, 'Хобби и творчество'),
(12, 'Аксессуары'),
(13, 'Канцелярские товары'),
(14, 'Антиквариат и коллекционирование'),
(15, 'Бытовая химия и гигиена');

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
(2, 'Материал'),
(3, 'Размер'),
(4, 'Вес'),
(5, 'Цена'),
(6, 'Страна производства'),
(7, 'Цветовая гамма'),
(8, 'Объем'),
(9, 'Применение'),
(10, 'Степень влагостойкости'),
(11, 'Степень износостойкости'),
(12, 'Уровень шума'),
(13, 'Качество'),
(14, 'Производитель'),
(15, 'Тип крепления');

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
  MODIFY `id_attributes` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

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
  MODIFY `id_items` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=529;

--
-- AUTO_INCREMENT для таблицы `items_images`
--
ALTER TABLE `items_images`
  MODIFY `id_items_images` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  MODIFY `id_properties` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
