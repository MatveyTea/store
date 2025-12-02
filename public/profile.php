<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth()) {
    redirect("auth.php");
}

$userItems = [];
$userItems = $link->prepare("SELECT `baskets`.`id_baskets`, `baskets`.`items_id_baskets`, `baskets`.`status_id_baskets`, `baskets`.`count_baskets`, `baskets`.`users_id_baskets`, `baskets`.`datetime_baskets`, `items`.`name_items`, `items`.`image_items`, `items`.`cost_items` FROM `baskets` JOIN `items` ON `items`.`id_items` = `items_id_baskets` WHERE `users_id_baskets` = ? ORDER BY CASE WHEN `baskets`.`datetime_baskets` IS NULL THEN 0 ELSE 1 END, `baskets`.`datetime_baskets` DESC");
$userItems->execute([$_SESSION["id_user"]]);
$userItems = $userItems->fetchAll(PDO::FETCH_ASSOC);

$historyBasket = "";
$currentBasket = "";
$resultCurrentBasket = 0;
$resultHistoryBasket = 0;

$date = null;
$lastUserItem = end($userItems);

foreach ($userItems as $item) {
    if ($item["datetime_baskets"] == null) {
        $currentBasket .= getItemHTML($item);
        $resultCurrentBasket += $item["cost_items"] * $item["count_baskets"];
    } else {
        if ($date != $item["datetime_baskets"]) {
            if ($date != null) {
                $historyBasket .= "<p>Всего: {$resultHistoryBasket}р</p></article>";
                $resultHistoryBasket = 0;
            }
            $historyBasket .= "<article class='basket'><h2>Время покупки: " . dateformat($item["datetime_baskets"]) . "</h2>";
            $date = $item["datetime_baskets"];
        }
        $historyBasket .= getItemHTML($item);
        $resultHistoryBasket += $item["cost_items"] * $item["count_baskets"];
        if ($item["id_baskets"] == $lastUserItem["id_baskets"]) {
            $historyBasket .= "<p>Всего: {$resultHistoryBasket}р</p></article>";
        }
    }
}
include_once __DIR__ . "/header.php";
?>
<script src="js/profile.js" defer></script>
<link rel="stylesheet" href="css/profile.css">
<section class="current-basket content">
    <h1><?= $currentBasket == "" ? "Пусто" : "Текущая корзина" ?></h1>
    <?= $currentBasket == "" ? "" : "<article class='basket'>$currentBasket</article>" ?>
    <?= $currentBasket == "" ? "" : "<button class='buy'>Оплатить <b>{$resultCurrentBasket}р</b></button>" ?>
</section>
<section class="history-basket">
    <h2><?=  $historyBasket == "" ? "Пусто" : "История покупок" ?></h2>
    <?= $historyBasket ?>
</section>