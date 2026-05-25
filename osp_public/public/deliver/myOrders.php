<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isUserAuth() || !isDeliver()) {
    redirect("user/auth.php");
}

$basketsCurrentHTML = "";
$basketsHistoryHTML = "";
$baskets = makeSelectQuery("SELECT
    `id_orders`,
    `id_baskets`,
    `count_baskets`,
    `users_id_orders`,
    `datetime_buy_orders`,
    `datetime_end_orders`,
    `datetime_receipt_orders`,
    `id_status`,
    `name_status`,
    `id_items`,
    `name_items`,
    `image_items`,
    `cost_items`
    FROM `baskets`
    JOIN `items` ON `id_items` = `items_id_baskets`
    JOIN `orders` ON `id_orders` = `orders_id_baskets`
    JOIN `status` ON `status`.`id_status` = `status_id_orders`
    WHERE `status_id_orders` > ? AND `users_deliver_orders` = ?
    ORDER BY `datetime_start_deliver_orders` DESC
", [2, getUserID()], false);
if ($baskets == "FAIL") {
    redirect();
}

$datetimeBuy = null;
$lastIndexBasket = count($baskets) - 1;
foreach ($baskets as $index => $basket) {
    if ($basket["id_status"] <= 5) {
        if ($datetimeBuy != $basket["datetime_buy_orders"]) {
            $basketsCurrentHTML .= "
                <article class='basket'>
                    <h2 class='time-buy'>Время покупки: " . dateformat($basket["datetime_buy_orders"]) . "</h2>
                    <h2 class='time-accept'>Заказ нужно доставить до: " . dateformat($basket["datetime_end_orders"]) . "</h2>
                    <h2 class='name-status'>Статус: $basket[name_status]</h2>
                    <div class='items'>
            ";
            $datetimeBuy = $basket["datetime_buy_orders"];
        }

        $basketsCurrentHTML .= getItemHTML($basket);
        if ($index == $lastIndexBasket || $datetimeBuy != null && $baskets[$index + 1]["datetime_buy_orders"] != $datetimeBuy) {
            $basketsCurrentHTML .= "
                    </div>
                    <button class='button action' data-id-order='$basket[id_orders]' data-id-status='$basket[id_status]'></button>
                </article>
            ";
        }
    } else {
        if ($datetimeBuy != $basket["datetime_buy_orders"]) {
            $basketsHistoryHTML .= "
                <article class='basket'>
                    <h2 class='time-buy'>Время покупки: " . dateformat($basket["datetime_buy_orders"]) . "</h2>
                    <h2 class='time-receipt'>Заказ получен: " . dateformat($basket["datetime_receipt_orders"]) . "</h2>
                    <div class='items'>
            ";
            $datetimeBuy = $basket["datetime_buy_orders"];
        }
        $basketsHistoryHTML .= getItemHTML($basket);
        if ($index == $lastIndexBasket || $datetimeBuy != null && $baskets[$index + 1]["datetime_buy_orders"] != $datetimeBuy) {
            $basketsHistoryHTML .= "
                    </div>
                </article>
            ";
        }
    }
}

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <section class="current-orders">
        <?= $basketsCurrentHTML == "" ? "<p class='notfound'>У вас нет заказов</p>" : "<h1>Ваши заказы</h1>$basketsCurrentHTML" ?>
    </section>
    <section class="history-orders">
        <?= $basketsHistoryHTML == "" ? "<h1>Нет выполненных заказов</h1>" : "<h1>История выполненных заказов</h1>$basketsHistoryHTML" ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>