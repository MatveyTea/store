<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth()) {
    redirect("auth.php");
}

$basket = makeSelectQuery("SELECT
    `baskets`.`id_baskets`,
    `baskets`.`count_baskets`,
    `baskets`.`users_id_baskets`,
    `baskets`.`datetime_buy_baskets`,
    `items`.`id_items`,
    `items`.`name_items`,
    `items`.`image_items`,
    `items`.`cost_items`
    FROM `baskets`
    JOIN `items` ON `items`.`id_items` = `items_id_baskets`
    WHERE `users_id_baskets` = ?
    ORDER BY CASE WHEN `baskets`.`datetime_buy_baskets` IS NULL THEN 0 ELSE 1 END, `baskets`.`datetime_buy_baskets` DESC
", [getUserID()], false);

if ($basket === "FAIL") {
    redirect();
}

extract(getBasketHTML($basket));

if (!empty($currentHTML)) {
    $currentHTML = "<h2>Товары в корзине</h2>
        <article class='basket items'>$currentHTML</article>
        <button class='button buy'>Оплатить <b> {$currentCost}р</b></button>
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
<style>
    .current-basket .buy {
        width: 300px;
        margin: 0 auto;
    }
    .basket {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin: 20px 0;
        color: white;
        background-color: chocolate;
        border-radius: 20px;
        padding: 20px;
    }
</style>