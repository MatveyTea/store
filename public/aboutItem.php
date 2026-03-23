<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (empty($_GET["id_item"])) {
    redirect();
}

$item = makeSelectQuery("SELECT
    `items`.`name_items`,
    `items`.`count_items`,
    `items`.`image_items`,
    `items`.`cost_items`,
    `items`.`date_add_items`,
    `items`.`description_items`,
    `items`.`items_type_id_items`,
    `items_type`.`name_items_type`,
    `attributes`.`properties_id_attributes`
    FROM `items`
    JOIN `items_type` ON `items_type`.`id_items_type` = `items`.`items_type_id_items`
    LEFT JOIN `items_properties` ON `id_items_properties` = `id_items`
    LEFT JOIN `attributes` ON `id_attributes` = `attributes_id_items_properties`
    LEFT JOIN `properties` ON `properties_id_attributes` = `id_properties`
    WHERE `items`.`id_items` = ?
", [$_GET["id_item"]], true);

if ($item === "FAIL" || empty($item)) {
    redirect();
}

$comments = makeSelectQuery("SELECT
    `comments`.`users_id_comments`,
    `comments`.`id_comments`,
    `comments`.`text_comments`,
    `comments`.`rating_comments`,
    `comments`.`date_add_comments`,
    `users`.`name_users`,
    `users`.`avatar_users`
    FROM `comments`
    JOIN `users` ON `comments`.`users_id_comments` = `users`.`id_users`
    WHERE `comments`.`items_id_comments` = ?
    ORDER BY `comments`.`date_add_comments` DESC
", [$_GET["id_item"]], false);

if ($comments === "FAIL") {
    redirect();
}


$itemHTML = "
    <h1>$item[name_items], $item[name_items_type], $item[cost_items] p</h1>
    <h2>Рейтинг: <b>" . getRatingItem($_GET["id_item"]) . "</b></h2>
    <img src='" . getValidImage(FOLDER_UPLOAD . "/" . FOLDER_ITEMS, $item["image_items"]) ."'>
    <p>" . ($item["description_items"] ?? "Товар без описания") . "</p>
";

if (isUserAuth()) {
    $basket = makeSelectQuery("SELECT `count_baskets` FROM `baskets`
        WHERE `status_id_baskets` = ? AND `items_id_baskets` = ? AND `users_id_baskets` = ?
        ", [1, $_GET["id_item"], getUserID()], true
    );
    if ($basket === "FAIL") {
        redirect();
    } else if ($basket == []) {
        $itemHTML .= "<span class='item' data-id='$_GET[id_item]' data-count='$item[count_items]'>
            <button class='item-basket button' data-type='add'>Добавить в корзину</button>
            <span class='invisible item-counter-container'>
                <button class='item-counter-minus button'>-</button>
                <p>В корзине: <b class='item-counter-text'>0</b></p>
                <button class='item-counter-plus button'>+</button>
            </span>
        </span>";
    } else {
        $itemHTML .= "<span class='item' data-id='$_GET[id_item]' data-count='$item[count_items]'>
            <button class='item-basket button' data-type='remove'>Убрать из корзины</button>
            <span class='item-counter-container'>
                <button class='item-counter-minus button'>-</button>
                <p>В корзине: <b class='item-counter-text'>$basket[count_baskets]</b></p>
                <button class='item-counter-plus button'>+</button>
            </span>
        </span>";
    }
}

$attributes = makeSelectQuery("SELECT 
    `attributes`.`id_attributes`,
    `attributes`.`value_attributes`,
    `properties`.`id_properties`,
    `properties`.`name_properties`
    FROM `items_properties`
    LEFT JOIN `attributes` ON `id_attributes` = `attributes_id_items_properties`
    LEFT JOIN `properties` ON `properties_id_attributes` = `id_properties`
    WHERE `items_id_items_properties` = ?
    ORDER BY `properties`.`id_properties`
    ", [$_GET["id_item"]], false
);

if ($attributes == "FAIL") redirect();

$currentAttributeID = null;
foreach  ($attributes as $index => $attribute) {
    if ($currentAttributeID != $attribute["id_properties"]) {
        if ($currentAttributeID != null) {
            $itemHTML .= "</p>";
        } 
        $itemHTML .= "<p>$attribute[name_properties] | ";
        $currentAttributeID = $attribute["id_properties"];
    }
    $itemHTML .= $attribute["value_attributes"];
    if ($index + 1 < count($attributes) && $attribute["id_properties"] == $attributes[$index + 1]["id_properties"]) {
        $itemHTML .= ", ";
    }
}
$itemHTML .= "</p>";
if (isAdmin()) {
    $itemHTML .="<a href='adminEditItem.php?id_item=$_GET[id_item]' class='button'>Изменить товар</a>";
}

getModalHTML();
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <section class="about">
        <?= $itemHTML ?>
    </section>
    <section class="comments">
        <?php if (isUserAuth()) { ?>
        <form class="form add-comment" method="POST" data-id="<?= $_GET["id_item"] ?>">
            <div class="field">
                <label class="label"></label>
                <textarea class="input textarea" data-name="text_comments" data-is-server-insert="1"></textarea>
                <p class="error"></p>
            </div>
            <div class="field">
                <label class="label"></label>
                <input type="number" class="input" data-name="rating_comments" data-is-server-insert="1">
                <p class="error"></p>
            </div>
            <div class="field">
                <input type="submit" class="input button">
            </div>
        </form>
        <?php } echo getCommentsHTML($comments) ?>
    </section>
    <section class="content items">
        <h2>Похожие товары</h2>
        <?= getItems(0, "WHERE (`items_type_id_items` = ? OR `properties_id_attributes` = ?) AND `id_items` != ?", [$item["items_type_id_items"], 1, $_GET["id_item"]], false, 20) ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>