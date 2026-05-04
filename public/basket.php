<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth()) {
    redirect("auth.php");
}

$basket = makeSelectQuery("SELECT
    `id_orders`,
    `id_baskets`,
    `count_baskets`,
    `datetime_buy_orders`,
    `id_items`,
    `name_items`,
    `image_items`,
    `cost_items`,
    `name_status`
    FROM `baskets`
    JOIN `items` ON `id_items` = `items_id_baskets`
    JOIN `orders` ON `id_orders` = `orders_id_baskets`
    JOIN `status` ON `id_status` = `status_id_orders`
    WHERE `users_id_orders` = ?
    ORDER BY CASE WHEN `datetime_buy_orders` IS NULL THEN 0 ELSE 1 END, `datetime_buy_orders` DESC
", [getUserID()], false);

if ($basket === "FAIL") {
    redirect();
}

$basketsHTML = getBasketHTML($basket);
extract($basketsHTML);

// $freeDeliver = makeSelectQuery("SELECT
//     `datetime_start_orders`,
//     `datetime_end_orders`
//     FROM `orders`
//     WHERE `datetime_start_orders` IS NOT NULL AND `status_id_orders` != 5 AND 
// ", [], false);
// print_r($freeDeliver);

// if ($freeDeliver == "FAIL") {
//     // redirect();
// }

// $timeOrders = []; // 10:00 - 11:00
// $startHour = date("H") + 1;
// for ($i = $startHour; $i < 24; $i++) {
//     "SELECT
//     `datetime_start_orders`,
//     `datetime_end_orders`
//     FROM `orders`
//     WHERE `datetime_start_orders` >= $i AND `status_id_orders` != 5 AND 
// ";
//     // if (true) {
//     //     $timeOrders[] = "$i:00 - " . $i + 1 . ":00";
//     // }
// }

// print_r($timeOrders);


$timeOrdersHTML = "";
foreach (["10:00 - 11:00", "11:00 - 12:00"] as $time) {
    $timeOrdersHTML .= "<option>$time</option>";
}

if (!empty($currentHTML)) {
    $currentHTML = "<h2>Товары в корзине</h2>
        <article class='basket'><div class='items'>$currentHTML</div></article>
        <button class='button buy'>Оформить заказ на <b>{$currentCost}р</b></button>

        <article class='make-order hidden'>
            <form action='' class='content form' method='POST'>
                <legend class='legend'>Доставка</legend>
                <div class='field'>
                    <label class='label'></label>
                    <input class='input' type='text' data-name='street_address_orders' data-is-insert-server='1'>
                    <p class='error'></p>
                </div>
                <div class='field'>
                    <label class='label'></label>
                    <input class='input' type='text' data-name='home_address_orders' data-is-insert-server='1'>
                    <p class='error'></p>
                </div>
                <div class='field'>
                    <label class='label'></label>
                    <input class='input' type='text' data-name='number_address_orders' data-is-insert-server='1'>
                    <p class='error'></p>
                </div>
                <div class='field'>
                    <label class='label'></label>
                    <select class='input' data-name='datetime_plan_orders' data-is-insert-server='1'>
                        <option selected disabled value=''>Выбрать</option>
                        $timeOrdersHTML
                    </select>
                    <p class='error'></p>
                </div>
                <div class='field'>
                    <label class='label'></label>
                    <textarea class='input' data-name='note_orders' data-is-insert-server='1'></textarea>
                    <p class='error'></p>
                </div>
                <div class='field'>
                    <input type='submit' name='submit_button' class='button' value='Оформить доставку'>
                </div>
                <div class='field'>
                    <button class='button cancel-basket'>Отмена</button>
                </div>
            </form>
        </article>
    ";
} else {
    $currentHTML = "<h2>У вас нет ничего в корзине</h2>";
}

if (!empty($historyHTML)) {
    $historyHTML = "<h2>История покупок</h2>$historyHTML";
} else {
    $historyHTML = "<h2>У вас не было покупок</h2>";
}

getModalHTML();
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <section class="current-basket">
        <?= $currentHTML ?>
    </section>
    <section class="history-basket">
        <?= $historyHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>