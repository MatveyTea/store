<?php
include_once __DIR__ . "/header.php";

$items = $link->query("SELECT `id_items`, `name_items`, `count_items`, `image_items` FROM `items`");

$itemsHTML = "";

foreach ($items as $item) {
    $img = checkImage($item["image_items"]);
    $itemsHTML .= "<div data-id='$item[id_items]' data-count='$item[count_items]'>
        <p>$item[name_items]</p>
        <p>Количество: $item[count_items]</p>
        <img src='$img'>
        <button class='basket' data-type='add'>Добавить в корзину</button>
        <span class='hidden'>
            <button class='minus'>-</button>
            <p>Количество: <b>0</b></p>
            <button class='plus'>+</button>
        </span>
    </div>";
}

?>

<section>
    <?= $itemsHTML ?>
</section>
<script src="js/index.js"></script>
<style>
    section {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }
    section div[data-id] {
        width: 25%;
        display: flex;
        flex-direction: column;
    }
    section div[data-id] span {
        display: flex;
        justify-content: space-between;
    }
</style>