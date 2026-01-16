<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";
include_once __DIR__ . "/header.php";

?>
<script src="js/index.js" defer></script>
<link rel="stylesheet" href="css/index.css">
<form action="/" method="POST" class="search form">
    <div>
        <label for="name_item">Имя товара</label>
        <input type="search" placeholder="Введите имя" id="name_item" name="name_item">
        <p class="error hidden"></p>
    </div>
    <div>
        <label for="min_cost_item">Цена</label>
        <span>
            <input type="search" placeholder="Введите цену" id="min_cost_item" name="min_cost_item">
            <input type="search" placeholder="Введите цену" id="max_cost_item" name="max_cost_item">
        </span>
        <p class="error hidden"></p>
    </div>
        <div>
        <label for="min_cost_item">Дата</label>
        <span>
            <input type="date" id="min_date_item" name="min_date_item">
            <input type="date" id="max_date_item" name="max_date_item">
        </span>
        <p class="error hidden"></p>
    </div>
    <div>
        <input type="submit" value="Найти" id="search_button" name="search_button">
    </div>
</form>
<h1 class="hidden">Поиск по</h1>
<section class="content">
    <?= getItems(20, 0) ?>
</section>