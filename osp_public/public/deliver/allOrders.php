<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isUserAuth() || !isDeliver()) {
    redirect("user/auth.php");
}

$basketsHTML = "";
makeSelectQuery("SET SESSION SQL_MODE = ''");
$baskets = makeSelectQuery("SELECT
    `id_orders`,
    `id_baskets`,
    `count_baskets`,
    `cost_baskets`,
    `discount_baskets`,
    `image_items_images`,
    `users_id_orders`,
    `datetime_buy_orders`,
    `id_status`,
    `name_status`,
    `id_items`,
    `name_items`,
    `cost_items`,
    `discount_items`
    FROM `baskets`
    JOIN `items` ON `id_items` = `items_id_baskets`
    LEFT JOIN `items_images` ON `items_id_items_images` = `id_items`
    JOIN `orders` ON `id_orders` = `orders_id_baskets`
    JOIN `status` ON `id_status` = `status_id_orders`
    WHERE `status_id_orders` = ?
    GROUP BY `id_baskets`
    ORDER BY `datetime_buy_orders` DESC
", [2], false);
if ($baskets == "FAIL") {
    redirect();
}


$datetimeBuy = null;
$lastIndexBasket = count($baskets) - 1;
foreach ($baskets as $index => $basket) {
    if ($datetimeBuy != $basket["datetime_buy_orders"]) {
        $basketsHTML .= "
            <article class='order'>
                <h2>Время покупки: " . dateformat($basket["datetime_buy_orders"]) . "</h2>
                <div class='items'>
        ";
        $datetimeBuy = $basket["datetime_buy_orders"];
    }
    $basketsHTML .= getItemHTML($basket);
    if ($index == $lastIndexBasket || $datetimeBuy != null && $baskets[$index + 1]["datetime_buy_orders"] != $datetimeBuy) {
        $basketsHTML .= "
                </div>
                <button class='button' data-id-order='$basket[id_orders]'>Принять</button>
            </article>
        ";
    }
}

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <section class="orders">
        <?= $basketsHTML === "" ? "<h2 class='notfound'>В данный момент нет заказов.</h2>" : "<h2 class='title'>Доступные заказы</h2>$basketsHTML" ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>