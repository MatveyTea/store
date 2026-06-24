<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isUserAuth()) {
    redirect("user/auth.php");
}

$delivers = makeSelectQuery("SELECT COUNT(*) as `count` FROM `users` WHERE `roles_id_users` = ?", [3], true);
if ($delivers == "FAIL") {
    redirect();
}

$currentTime = new DateTime()->modify("+1 hour");
$currentHour = $currentTime->format("H");
$busyTimes = makeSelectQuery("SELECT
    CONCAT(
        YEAR(`datetime_start_orders`),
        '-',
        LPAD(MONTH(`datetime_start_orders`), 2, '0'),
        '-',
        LPAD(DAY(`datetime_start_orders`), 2, '0'),
        ' ',
        LPAD(HOUR(`datetime_start_orders`), 2, '0')
    ) AS `datetime_start_orders`
    FROM `orders`
    WHERE `status_id_orders` > ? AND `status_id_orders` < ? AND `datetime_start_orders` >= ?
    GROUP BY `datetime_start_orders`, `datetime_end_orders`
    HAVING COUNT(*) = ?
", [1, 5, $currentTime->format("Y-m-d H:i:s"), $delivers["count"]], false);

if ($busyTimes == "FAIL") {
    redirect();
}

$arrayBusyTimes = array_column($busyTimes, "datetime_start_orders");
$timesOrders = [];

$endTime = (clone $currentTime)->modify("+1 day")->setTime(0, 0);
while ($currentTime < $endTime) {
    if (!in_array($currentTime->format("Y-m-d H"), $arrayBusyTimes)) {
        $timesOrders[] = "Сегодня, " . $currentTime->format("H") . ":00 - " . str_pad($currentTime->format("H") + 1, 2, "0", STR_PAD_LEFT) . ":00";
    }
    $currentTime->modify("+1 hour");
}

$endTime->setTime($currentHour, 0);
while ($currentTime < $endTime) {
    if (!in_array($currentTime->format("Y-m-d H"), $arrayBusyTimes)) {
        $timesOrders[] = "Завтра, " . $currentTime->format("H") . ":00 - " . str_pad($currentTime->format("H") + 1, 2, "0", STR_PAD_LEFT) . ":00";
    }
    $currentTime->modify("+1 hour");
}

$timesOrdersHTML = "";
foreach ($timesOrders as $time) {
    $timesOrdersHTML .= "<option>$time</option>";
}

makeSelectQuery("SET SESSION SQL_MODE = ''");
$basket = makeSelectQuery("SELECT
    `id_orders`,
    `id_baskets`,
    `count_baskets`,
    `discount_baskets`,
    `cost_baskets`,
    `datetime_buy_orders`,
    `id_items`,
    `name_items`,
    `cost_items`,
    `image_items_images`,
    `discount_items`,
    `name_status`
    FROM `baskets`
    JOIN `items` ON `id_items` = `items_id_baskets`
    LEFT JOIN `items_images` ON `id_items` = `items_id_items_images`
    JOIN `orders` ON `id_orders` = `orders_id_baskets`
    JOIN `status` ON `id_status` = `status_id_orders`
    WHERE `users_id_orders` = ?
    GROUP BY `id_baskets`
    ORDER BY CASE WHEN `datetime_buy_orders` IS NULL THEN 0 ELSE 1 END, `datetime_buy_orders` DESC
", [getUserID()], false);

if ($basket === "FAIL") {
    redirect();
}

$basketsHTML = getBasketHTML($basket);
extract($basketsHTML);

if (!empty($currentHTML)) {
    $currentHTML = "<h2 class='title'>Товары в корзине</h2>
        <article class='order'>
            <div class='items'>$currentHTML</div>
            <button class='button buy'>Оформить заказ на&nbsp;<b>{$currentCost}р</b></button>
        </article>

        <article class='make-order hidden'>
            <form action='/user/basket.php' class='content form' method='POST'>
                <legend class='legend'>Доставка</legend>
                <div class='field'>
                    <label class='label'></label>
                    <input class='input' type='text' data-name='street_address_orders'>
                <span class='error-wrapper'>
                    <p class='error'></p>
                </span>
                </div>
                <div class='field'>
                    <label class='label'></label>
                    <input class='input' type='text' data-name='home_address_orders'>
                <span class='error-wrapper'>
                    <p class='error'></p>
                </span>
                </div>
                <div class='field'>
                    <label class='label'></label>
                    <input class='input' type='text' data-name='number_address_orders'>
                <span class='error-wrapper'>
                    <p class='error'></p>
                </span>
                </div>
                <div class='field'>
                    <label class='label'></label>
                    <select class='input' data-name='datetime_plan_orders'>
                        <option selected disabled value=''>Выбрать</option>
                        $timesOrdersHTML
                    </select>
                <span class='error-wrapper'>
                    <p class='error'></p>
                </span>
                </div>
                <div class='field'>
                    <label class='label'></label>
                    <textarea class='input' data-name='note_orders'></textarea>
                <span class='error-wrapper'>
                    <p class='error'></p>
                </span>
                </div>
                <div class='field'>
                    <button class='button cancel-basket'>Отмена</button>
                    <input type='submit' name='submit_button' class='button' value='Оформить доставку'>
                </div>
            </form>
        </article>
    ";
} else {
    $currentHTML = "<h2 class='notfound'>В данный момент в корзине пусто.</h2>";
}

if (!empty($historyHTML)) {
    $historyHTML = "<h2 class='title'>История покупок</h2>$historyHTML";
} else {
    $historyHTML = "<h2 class='notfound'>У Вас ещё не было ни одной покупки.</h2>";
}

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <section class="orders current">
        <?= $currentHTML ?>
    </section>
    <section class="orders">
        <?= $historyHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>