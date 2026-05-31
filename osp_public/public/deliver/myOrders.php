<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isUserAuth() || !isDeliver()) {
    redirect("user/auth.php");
}

$basketsCurrentHTML = "";
$basketsHistoryHTML = "";
makeSelectQuery("SET SESSION SQL_MODE = ''");
$baskets = makeSelectQuery("SELECT
    `id_orders`,
    `id_baskets`,
    `count_baskets`,
    `cost_baskets`,
    `users_id_orders`,
    `datetime_buy_orders`,
    `datetime_end_orders`,
    `datetime_receipt_orders`,
    `id_status`,
    `name_status`,
    `id_items`,
    `discount_baskets`,
    `name_items`,
    `image_items_images`,
    `cost_items`,
    `discount_items`,
    `tel_users`,
    `email_users`
    FROM `baskets`
    JOIN `items` ON `id_items` = `items_id_baskets`
    LEFT JOIN `items_images` ON `items_id_items_images` = `id_items`
    JOIN `orders` ON `id_orders` = `orders_id_baskets`
    JOIN `status` ON `status`.`id_status` = `status_id_orders`
    JOIN `users` ON `users_id_orders` = `id_users`
    WHERE `status_id_orders` > ? AND `users_deliver_orders` = ?
    GROUP BY `id_baskets`
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
                <article class='order'>
                    <h2 class='subtitle'>Заказ нужно доставить до: " . dateformat($basket["datetime_end_orders"]) . "</h2>
                    <h2 class='text name-status'>Статус: $basket[name_status]</h2>
                    <h2 class='text'>Связаться с покупателем: " . ($basket["tel_users"] ?? $basket["email_users"]) . "</h2>
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
                <article class='order'>
                    <h2 class='subtitle'>Заказ получен: " . dateformat($basket["datetime_receipt_orders"]) . "</h2>
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
    <section class="orders">
        <?= $basketsCurrentHTML == "" ? "<h2 class='notfound'>В данный момент у Вас нет заказов.</p>" : "<h2 class='title'>Ваши заказы</h2>$basketsCurrentHTML" ?>
    </section>
    <section class="orders">
        <?= $basketsHistoryHTML == "" ? "<h2 class='notfound'>В данный момент нет ни одного выполненного заказа.</h2>" : "<h2 class='title'>История выполненных заказов</h2>$basketsHistoryHTML" ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>