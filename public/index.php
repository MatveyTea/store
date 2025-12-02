<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

$items = $link->query("SELECT `id_items`, `name_items`, `count_items`, `image_items`, `cost_items` FROM `items` LIMIT 20 OFFSET 0");

$itemsHTML = "";
$userItems = [];
if (isUserAuth()) {
    try {
        $userItems = $link->prepare("SELECT `items_id_baskets`, `count_baskets` FROM `baskets` WHERE `users_id_baskets` = ?");
        $userItems->execute([$_SESSION["id_user"]]);
        $userItems = $userItems->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {}
}

foreach ($items as $item) {
    $img = checkImage($item["image_items"]);
    $basket = "";

    foreach ($userItems as $userItem) {
        if ($userItem["items_id_baskets"] == $item["id_items"]) {
            $basket = "<button class='basket' data-type='remove'>Убрать из корзины</button>
            <span class=''>
                <button class='minus'>-</button>
                <p>В корзине: <b>$userItem[count_baskets]</b></p>
                <button class='plus'>+</button>
            </span>";
            break;
        }
    }

    if ($basket == "" && isUserAuth()) {
        $basket = "<button class='basket' data-type='add'>Добавить в корзину</button>
        <span class='hidden'>
            <button class='minus'>-</button>
            <p>В корзине: <b>0</b></p>
            <button class='plus'>+</button>
        </span>";
    }

    $itemsHTML .= "<div data-id='$item[id_items]' data-count='$item[count_items]'>
        <img src='$img'>
        <p>$item[name_items]</p>
        <p>Количество: $item[count_items]</p>
        <p>Стоимость: $item[cost_items]р</p>
        $basket
    </div>";
}

include_once __DIR__ . "/header.php";
?>
<script src="js/index.js" defer></script>
<link rel="stylesheet" href="css/index.css">
<section class="content">
    <?= $itemsHTML ?>
</section>