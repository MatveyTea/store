<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || !isDeliver()) {
    redirect("auth.php");
}

$basketsHTML = "";
$baskets = makeSelectQuery("SELECT
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
    WHERE `status_id_baskets` = ?
    ORDER BY `baskets`.`datetime_buy_baskets` DESC
", [2], false);
if ($baskets == "FAIL") {
    redirect();
}


$datetimeBuy = null;
$lastIndexBasket = count($baskets) - 1;
foreach ($baskets as $index => $basket) {
    if ($datetimeBuy != $basket["datetime_buy_baskets"]) {
        $basketsHTML .= "
            <article class='basket'>
                <h2>Время покупки: " . dateformat($basket["datetime_buy_baskets"]) . "</h2>
                <div class='items'>
        ";
        $datetimeBuy = $basket["datetime_buy_baskets"];
    }
    $basketsHTML .= getItemHTML($basket);
    if ($index == $lastIndexBasket || $datetimeBuy != null && $baskets[$index + 1]["datetime_buy_baskets"] != $datetimeBuy) {
        $basketsHTML .= "
                </div>
                <button class='button' data-datetime='$basket[datetime_buy_baskets]'>Принять</button>
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