<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action="/" method="POST" class="search form">
        <legend class="legend">Поиск</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="search" data-name="name_search_items" data-is-insert-server="0">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="search" data-name="min_cost_items" data-is-insert-server="0">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="search" data-name="max_cost_items" data-is-insert-server="0">
            <p class="error"></p>
        </div>
        <div class="field">
            <p class="error server-error"></p>
            <input class="button input" type="submit" value="Найти" id="search_button" name="submit_button">
        </div>
    </form>
    <section class="items">
        <?= getItems(50, 0) ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>