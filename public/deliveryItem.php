<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || empty($_GET["datetime"])) {
    redirect("auth.php");
}

$itemsInBasketHTML = "";
$itemsInBasket = makeSelectQuery("SELECT
    `baskets`.`id_baskets`,
    `baskets`.`count_baskets`,
    `baskets`.`users_id_baskets`,
    `baskets`.`datetime_buy_baskets`,
    `status`.`id_status`,
    `status`.`name_status`,
    `items`.`id_items`,
    `items`.`name_items`,
    `items`.`image_items`,
    `items`.`cost_items`
    FROM `baskets`
    JOIN `items` ON `items`.`id_items` = `items_id_baskets`
    JOIN `status` ON `status`.`id_status` = `status_id_baskets`
    WHERE `users_id_baskets` = ? AND `datetime_buy_baskets` = ?
", [getUserID(), $_GET["datetime"]], false);

if ($itemsInBasket == "FAIL" || empty($itemsInBasket)) {
    redirect("basket.php");
}

foreach ($itemsInBasket as $item) {
    $itemsInBasketHTML .= getItemHTML($item);
}

$allStatusHTML = "";
$allStatus = makeSelectQuery("SELECT * FROM `status` LIMIT 99999999 OFFSET 1", [], false);
if ($allStatus == "FAIL") {
   
}

$currentStatusItemInBasket = $itemsInBasket[0]["id_status"];
foreach ($allStatus as $index => $status) {
    $activeClass = $status["id_status"] <= $currentStatusItemInBasket ? "competed" : "";
    $allStatusHTML .= "<p class='$activeClass'>$status[name_status]</p>";
    if ($index != count($allStatus) - 1) {
        $allStatusHTML .= "<img src='img/main/statusArrow.png'>";
    }
}

if ($currentStatusItemInBasket == 5) {
    $allStatusHTML .= "<button class='button receipt' data-datetime='" . $itemsInBasket[0]["datetime_buy_baskets"] . "'>Я получил товар</button>";
 }

getModalHTML();
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <section class="status">
        <h2>Статус получения товаров</h2>
        <?= $allStatusHTML ?>
    </section>
    <section class="items">
        <?= $itemsInBasketHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>