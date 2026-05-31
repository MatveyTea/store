<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isUserAuth() || empty($_GET["id_order"])) {
    redirect("user/auth.php");
}

$itemsInBasketHTML = "";
makeSelectQuery("SET SESSION SQL_MODE = ''");
$itemsInBasket = makeSelectQuery("SELECT
    `id_orders`,
    `id_baskets`,
    `cost_baskets`,
    `count_baskets`,
    `users_id_orders`,
    `datetime_buy_orders`,
    `id_status`,
    `name_status`,
    `id_items`,
    `name_items`,
    `image_items_images`,
    `cost_items`,
    `discount_baskets`,
    `discount_items`
    FROM `baskets`
    JOIN `orders` ON `id_orders` = `orders_id_baskets`
    JOIN `items` ON `id_items` = `items_id_baskets`
    LEFT JOIN `items_images` ON `id_items` = `items_id_items_images`
    JOIN `status` ON `id_status` = `status_id_orders`
    WHERE `users_id_orders` = ? AND `orders_id_baskets` = ?
    GROUP BY `id_baskets`
", [getUserID(), $_GET["id_order"]], false);

if ($itemsInBasket == "FAIL" || empty($itemsInBasket)) {
    redirect("user/basket.php");
}

foreach ($itemsInBasket as $item) {
    $itemsInBasketHTML .= getItemHTML($item);
}

$allStatusHTML = "";
$allStatus = makeSelectQuery("SELECT * FROM `status`", [], false);
if ($allStatus == "FAIL") {
    redirect("user/basket.php");
}

$currentStatusItemInBasket = $itemsInBasket[0]["id_status"];
foreach ($allStatus as $index => $status) {
    $activeClass = $status["id_status"] <= $currentStatusItemInBasket ? "completed" : "";
    $allStatusHTML .= "<p class='$activeClass'>" . $index + 1 . ". $status[name_status]</p>";
}

if ($currentStatusItemInBasket == 5) {
    $allStatusHTML .= "<button class='button receipt' data-id-order='" . $itemsInBasket[0]["id_orders"] . "'>Я получил товар</button>";
 }

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <section class="status">
        <h2 class="title">Статус получения товаров</h2>
        <?= $allStatusHTML ?>
    </section>
    <section class="items">
        <h2 class="title">Товары</h2>
        <?= $itemsInBasketHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>