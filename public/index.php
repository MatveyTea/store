<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";
include_once __DIR__ . "/header.php";
?>
<script src="js/index.js" defer></script>
<link rel="stylesheet" href="css/index.css">
<main class="content">
    <form action="/" method="POST" class="search form">
        <legend>Поиск</legend>
        <div class="field">
            <label class="label" for="name_search_items">Имя товара</label>
            <input class="input" type="search" placeholder="Введите имя" id="name_search_items" name="name_search_items">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label" for="min_cost_items">Цена</label>
            <span>
                <input class="input" type="search" placeholder="Введите цену" id="min_cost_items" name="min_cost_items">
                <p class="error"></p>
                <input class="input" type="search" placeholder="Введите цену" id="max_cost_items" name="max_cost_items">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <input class="button input" type="submit" value="Найти" id="search_button" name="search_button">
            <p class="error server-error"></p>
        </div>
    </form>
    <section class="items">
        <?= getItems(50, 0) ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>
