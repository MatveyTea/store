<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || empty($_GET["id_order"])) {
    redirect("auth.php");
}

$itemsInBasketHTML = "";
$itemsInBasket = makeSelectQuery("SELECT
    `id_orders`,
    `id_baskets`,
    `count_baskets`,
    `users_id_orders`,
    `datetime_buy_orders`,
    `id_status`,
    `name_status`,
    `id_items`,
    `name_items`,
    `image_items`,
    `cost_items`
    FROM `baskets`
    JOIN `orders` ON `id_orders` = `orders_id_baskets`
    JOIN `items` ON `id_items` = `items_id_baskets`
    JOIN `status` ON `id_status` = `status_id_orders`
    WHERE `users_id_orders` = ? AND `orders_id_baskets` = ?
", [getUserID(), $_GET["id_order"]], false);

if ($itemsInBasket == "FAIL" || empty($itemsInBasket)) {
    redirect("basket.php");
}

foreach ($itemsInBasket as $item) {
    $itemsInBasketHTML .= getItemHTML($item);
}

$allStatusHTML = "";
$allStatus = makeSelectQuery("SELECT * FROM `status`", [], false);
if ($allStatus == "FAIL") {
    redirect("basket.php");
}

$currentStatusItemInBasket = $itemsInBasket[0]["id_status"];
foreach ($allStatus as $index => $status) {
    $activeClass = $status["id_status"] <= $currentStatusItemInBasket ? "completed" : "";
    $allStatusHTML .= "<p class='$activeClass'>$status[name_status]</p>";
    if ($index != count($allStatus) - 1) {
        $allStatusHTML .= "<img src='img/main/statusArrow.png'>";
    }
}

if ($currentStatusItemInBasket == 5) {
    $allStatusHTML .= "<button class='button receipt' data-id-order='" . $itemsInBasket[0]["id_orders"] . "'>Я получил товар</button>";
 }

getModalHTML();
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <section class="status">
        <h2>Статус получения товаров</h2>
        <?= $allStatusHTML ?>
    </section>
    <section class="items">
        <?= $itemsInBasketHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>