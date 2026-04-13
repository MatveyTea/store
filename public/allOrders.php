<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || !isDeliver()) {
    redirect("auth.php");
}

$basketsHTML = "";
$baskets = makeSelectQuery("SELECT
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
    JOIN `items` ON `id_items` = `items_id_baskets`
    JOIN `orders` ON `id_orders` = `orders_id_baskets`
    JOIN `status` ON `id_status` = `status_id_orders`
    WHERE `status_id_orders` = ?
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
            <article class='basket'>
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
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <section>
        <?= $basketsHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>