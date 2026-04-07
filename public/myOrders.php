<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || !isDeliver()) {
    redirect("auth.php");
}

$basketsCurrentHTML = "";
$basketsHistoryHTML = "";
$baskets = makeSelectQuery("SELECT
    `baskets`.`id_baskets`,
    `baskets`.`count_baskets`,
    `baskets`.`users_id_baskets`,
    `baskets`.`datetime_buy_baskets`,
    `baskets`.`datetime_receipt_baskets`,
    `status`.`id_status`,
    `status`.`name_status`,
    `items`.`id_items`,
    `items`.`name_items`,
    `items`.`image_items`,
    `items`.`cost_items`,
    `orders`.`accept_datetime_orders`
    FROM `baskets`
    JOIN `items` ON `items`.`id_items` = `items_id_baskets`
    JOIN `status` ON `status`.`id_status` = `status_id_baskets`
    JOIN `orders` ON `orders`.`baskets_datetime_orders` = `datetime_buy_baskets`
    WHERE `status_id_baskets` > ? AND `users_id_orders` = ?
    ORDER BY `baskets`.`datetime_buy_baskets` DESC
", [2, getUserID()], false);
if ($baskets == "FAIL") {
    //redirect();
}

$datetimeBuy = null;
$lastIndexBasket = count($baskets) - 1;
foreach ($baskets as $index => $basket) {
    if ($basket["id_status"] < 5) {
        if ($datetimeBuy != $basket["datetime_buy_baskets"]) {
            $basketsCurrentHTML .= "
                <article class='basket'>
                    <h2 class='time-buy'>Время покупки: " . dateformat($basket["datetime_buy_baskets"]) . "</h2>
                    <h2 class='time-accept'>Заказ принят: " . dateformat($basket["accept_datetime_orders"]) . "</h2>
                    <h2 class='name-status'>Статус: <b>$basket[name_status]</b></h2>
                    <div class='items'>
            ";
            $datetimeBuy = $basket["datetime_buy_baskets"];
        }
        $basketsCurrentHTML .= getItemHTML($basket);
        if ($index == $lastIndexBasket || $datetimeBuy != null && $baskets[$index + 1]["datetime_buy_baskets"] != $datetimeBuy) {
            $basketsCurrentHTML .= "
                    </div>
                    <p class='help-text'></p>
                    <button class='button action' data-datetime='$basket[datetime_buy_baskets]' data-id-status='$basket[id_status]'></button>
                </article>
            ";
        }
    } else {
        if ($datetimeBuy != $basket["datetime_buy_baskets"]) {
            $basketsHistoryHTML .= "
                <article class='basket'>
                    <h2 class='time-buy'>Время покупки: " . dateformat($basket["datetime_buy_baskets"]) . "</h2>
                    <h2 class='time-accept'>Заказ принят: " . dateformat($basket["accept_datetime_orders"]) . "</h2>
                    <h2 class='time-receipt'>Заказ получен: " . dateformat($basket["datetime_receipt_baskets"]) . "</h2>
                    <div class='items'>
            ";
            $datetimeBuy = $basket["datetime_buy_baskets"];
        }
        $basketsHistoryHTML .= getItemHTML($basket);
        if ($index == $lastIndexBasket || $datetimeBuy != null && $baskets[$index + 1]["datetime_buy_baskets"] != $datetimeBuy) {
            $basketsHistoryHTML .= "
                    </div>
                </article>
            ";
        }
    }
}

getModalHTML();
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <section class="current-orders">
        <?= $basketsCurrentHTML ?>
    </section>
    <section class="history-orders">
        <h1>История выполненных заказов</h1>
        <?= $basketsHistoryHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>