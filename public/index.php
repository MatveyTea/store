<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";
include_once __DIR__ . "/header.php";

$types = makeSelectQuery("SELECT * FROM `items_type`", [], false);

if ($types == "FAIL") redirect();

$typesHTML = "";
foreach ($types as $type) {
    $typesHTML .= "<input  value='$type[id_items_type]'>$type[name_items_type]</p>";
}
?>

<main class="content">
    <form action="/" method="POST" class="form">
        <legend class="legend">Поиск</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="search" data-name="name_search_items" data-is-insert-server="0">
            <p class="error"></p>
        </div>
        <!-- <div class="field">
            <label class="label"></label>
            <span class="input" data-name="items_type_id_search_items" data-is-insert-server="0">
            </span>
            <p class="error"></p>
        </div> -->
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
            <label class="label"></label>
            <input class="input" type="search" data-name="min_count_items" data-is-insert-server="0">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="search" data-name="max_count_items" data-is-insert-server="0">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input strict" type="checkbox" data-name="strict_search" data-is-insert-server="0">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input popular" type="checkbox" data-name="popular_items" data-is-insert-server="0">
            <p class="error"></p>
        </div>
        <div class="field">
            <input class="button input" type="submit" value="Найти" id="search_button" name="submit_button">
        </div>
    </form>
    <section class="items">
        <?= getItems(0) ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>