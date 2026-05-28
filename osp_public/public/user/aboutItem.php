<?php
include_once __DIR__ . "/../../app/server/function.php";

if (empty($_GET["id_item"])) {
    redirect();
}

$item = makeSelectQuery("SELECT
    `id_items`,
    `name_items`,
    `count_items`,
    `image_items`,
    `cost_items`,
    `date_add_items`,
    `description_items`,
    `items_type_id_items`,
    `items_type`.`name_items_type`,
    `attributes`.`properties_id_attributes`
    FROM `items`
    LEFT JOIN `items_type` ON `items_type`.`id_items_type` = `items_type_id_items`
    LEFT JOIN `items_properties` ON `id_items_properties` = `id_items`
    LEFT JOIN `attributes` ON `id_attributes` = `attributes_id_items_properties`
    LEFT JOIN `properties` ON `properties_id_attributes` = `id_properties`
    WHERE `id_items` = ?
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

$imagesItem = makeSelectQuery("SELECT
    `image_items_images`
    FROM `items_images`
    WHERE `items_id_items_images` = ?
", [$_GET["id_item"]], false);
if ($imagesItem === "FAIL") {
    redirect();
}

$imageItemHTML = "";
foreach ($imagesItem as $img) {
    $imageItemHTML .= "<img class='image' src='" . getValidImage("items/$img[image_items_images]") ."'>";
}

$itemHTML = "
    <article class='about-top'>
        <h1 class='about-name'>$item[name_items]</h1>
    " . getSliderImagesItemHTML($imageItemHTML) . "
";

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

$itemHTML .= "<div class='about-attributes'>
    <span class='attribute'>
        <p class='attribute-name'>Стоимость</p>
        <p class='attribute-value'>$item[cost_items] p</p>
    </span>
    <span  class='attribute'>
        <p class='attribute-name'>Тип товара</p>
        <p class='attribute-value'>$item[name_items_type]</p>
    </span>
    <span class='attribute'>
        <p class='attribute-name'>Рейтинг</p>
        <p class='attribute-value rating'>" . getRatingItem($_GET["id_item"]) . "</p>
    </span>
";

$similarItemsSQL = [];
$similarItemsParams = [];
$currentAttributeID = null;
foreach  ($attributes as $index => $attribute) {
    if ($currentAttributeID != $attribute["id_properties"]) {
        $similarItemsSQL[] = "?";
        $similarItemsParams[] = $attribute["id_properties"];
        if ($currentAttributeID != null) {
            $itemHTML .= "</p></span>";
        } 
        $itemHTML .= "<span class='attribute'>
            <p class='attribute-name'>$attribute[name_properties]</p>
            <p class='attribute-value'>
        ";
        $currentAttributeID = $attribute["id_properties"];
    }
    $itemHTML .= $attribute["value_attributes"];
    if ($index + 1 < count($attributes) && $attribute["id_properties"] == $attributes[$index + 1]["id_properties"]) {
        $itemHTML .= ", ";
    }
}
$itemHTML .= "
    </p></span></div></article>
    <article class='about-bottom'>
        <p class='about-description'>" . ($item["description_items"] ?? "Товар без описания") . "</p>

";

if (isUserAuth()) {
    $itemBasket = makeSelectQuery("SELECT
        `items_id_baskets`,
        `count_baskets`
        FROM `baskets`
        LEFT JOIN `orders` ON `id_orders` = `orders_id_baskets`
        WHERE `items_id_baskets` = ? AND `users_id_orders` = ? AND `status_id_orders` = ?
    ", [$_GET["id_item"], getUserID(), 1], true);
    if ($itemBasket === "FAIL") redirect();

    $favoritesItems = makeSelectQuery("SELECT `items_id_favorites` FROM `favorites` WHERE `users_id_favorites` = ?", [getUserID()], false);
    if ($favoritesItems == "FAIL") redirect();
    
    $userItems = count($itemBasket) == 0 ? [] : [$itemBasket];
    $itemHTML .= "<span class='basket' data-id='$_GET[id_item]' data-count='$item[count_items]'>" . getBuyBasketHTML($userItems, $item, $favoritesItems) . "</span>";
}

$itemHTML .= "</article>";

$editItemHTML = "";
if (isAdmin()) {
    $editItemHTML .="<a href='/admin/editItem.php?id_item=$_GET[id_item]' class='button'>Изменить товар</a>";
}

$star = "<svg width='30' height='30' fill='#FFD700' class='inactive'>
    <use xlink:href='/assets/img/star.svg#star'></use>
</svg>";
$starsHTML = str_repeat($star, 5);

$commentForm = "";
if (isUserAuth()) { 
    $commentForm .= "<form action='/user/aboutItem.php' class='form add-comment' method='POST' data-id='$_GET[id_item]'>
        <legend class='title'>Оставить отзыв</legend>
        <div class='field'>
            <label class='label'></label>
            <textarea class='input textarea' data-name='text_comments' data-is-server-insert='1'></textarea>
            <span class='error-wrapper'>
                <p class='error'></p>
            </span>
        </div>
        <div class='field'>
            <label class='label'></label>
            <span class='input' data-name='rating_comments' data-is-server-insert='1'>
                $starsHTML
            </span>
            <span class='error-wrapper'>
                <p class='error'></p>
            </span>
        </div>
        <div class='field'>
            $editItemHTML
            <input type='submit' name='submit_button' class='button'>
        </div>
    </form>";
}

$commentsHTML = getCommentsHTML($comments);
if ($commentsHTML == "") {
    $commentsHTML = "<h2 class='notfound'>В данный момент нет отзывов.</h2> <h2 class='title hidden'>Отзывы</h2>";
} else {
    $commentsHTML = "<h2 class='notfound hidden'>В данный момент нет отзывов.</h2> <h2 class='title'>Отзывы</h2> $commentsHTML";
}

if ($similarItemsSQL != "" && !empty($similarItemsParams)) {
    $similarItemsSQL = "OR `properties_id_attributes` IN (" . join(",", $similarItemsSQL) .")";
} else {
    $similarItemsSQL = "";
}

$similarItemsHTML = getItems(0, "WHERE (`items_type_id_items` = ? $similarItemsSQL) AND `id_items` != ?", [$item["items_type_id_items"], ...$similarItemsParams, $_GET["id_item"]], false, 20);

if ($similarItemsHTML == "") {
    $similarItemsHTML = "<h2 class='notfound'>В данный момент нет похожих товаров.</h2>";
} else {
    $similarItemsHTML = "<h2 class='title'>Похожие товары</h2> $similarItemsHTML";
}

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <section class="about">
        <?= $itemHTML ?>
    </section>
    <?= $commentForm ?>
    <section class="comments">
        <?= $commentsHTML ?>
    </section>
    <section class="content items">
        <?= $similarItemsHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>