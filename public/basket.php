<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth()) {
    redirect("auth.php");
}

$stmt = $link->prepare("SELECT
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
");
$stmt->execute([getUserID()]);
extract(getBasketHTML($stmt->fetchAll(PDO::FETCH_ASSOC)));

if (!empty($currentHTML)) {
    $currentHTML = "<h2>Товары в корзине</h2>
        <article class='basket'>$currentHTML</article>
        <button class='button buy'>Оплатить <b>{$currentCost}р</b></button>
    ";
} else {
    $currentHTML = "<h2>У вас нет ничего в корзине</h2>";
}

if (!empty($historyHTML)) {
    $historyHTML = "<h2>История покупок</ah2>
        $historyHTML
    ";
} else {
    $historyHTML = "<h2>У вас не было покупок</h2>";
}

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