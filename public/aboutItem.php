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
    `items_type`.`name_items_type`
    FROM `items`
    JOIN `items_type` ON `items_type`.`id_items_type` = `items`.`items_type_id_items`
    WHERE `items`.`id_items` = ?
", [$_GET["id_item"]], true);

$itemProperties = makeSelectQuery("SELECT
    `items_properties`.`description_items_properties`,
    `properties`.`name_properties`
    FROM `items_properties`
    JOIN `properties` ON `properties`.`id_properties` = `items_properties`.`properties_id_items_properties`
    WHERE `items_properties`.`items_id_items_properties` = ?
", [$_GET["id_item"]], false);

if (empty($item)) {
    redirect();
}

$comments = makeSelectQuery("SELECT
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

$itemHTML = "
    <h1>$item[name_items], $item[name_items_type], $item[cost_items] p</h1>
    <h2>Рейтинг: <b>" . getRatingItem($_GET["id_item"]) . "</b></h2>
    <img src='" . getValidImage(FOLDER_INDEX, $item["image_items"]) ."'>
    <p>" . ($item["description_items"] ?? "Товар без описания") . "</p>
";

if (isUserAuth()) {
    $basket = makeSelectQuery("SELECT
    *
    FROM `baskets`
    WHERE `status_id_baskets` = ? AND `items_id_baskets` = ? AND `users_id_baskets` = ?", [1, $_GET["id_item"], getUserID()], true);
    if ($basket == []) {
        $itemHTML .= "<span class='item' data-id='$_GET[id_item]' data-count='$item[count_items]'>
            <button class='button basket' data-type='add'>Добавить в корзину</button>
            <span class='hidden counter-wrapper'>
                <button class='button minus'>-</button>
                <p class='counter'>В корзине: <b class='counterText'>0</b></p>
                <button class='button plus'>+</button>
            </span>
        </span>";
    } else {
        $itemHTML .= "<span class='item' data-id='$_GET[id_item]' data-count='$item[count_items]'>
            <button class='button basket' data-type='remove'>Убрать из корзины</button>
            <span class='counter-wrapper'>
                <button class='button minus'>-</button>
                <p class='counter'>В корзине: <b class='counterText'>$basket[count_baskets]</b></p>
                <button class='button plus'>+</button>
            </span>
        </span>";
    }
}
    
foreach ($itemProperties ?? [] as $property) {
    $itemHTML .= "
        <span>
            <p>$property[name_properties]</p>
            <p>$property[description_items_properties]</p>
        </span>
    ";
}

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
                <label class="label" for="text_comments">Отзыв</label>
                <textarea class="input textarea" name="text_comments" id="text_comments" placeholder="Введите комментарий" maxlength="256"></textarea>
                <p class="error"></p>
            </div>
            <div class="field">
                <label class="label" for="rating_comments">Рейтинг</label>
                <input type="number" class="input" name="rating_comments" id="rating_comments" placeholder="Введите оценку от 1 до 5">
                <p class="error"></p>
            </div>
            <div class="field">
                <button class="button">Отправить</button>
            </div>
        </form>
        <?php } echo getCommentsHTML($comments)  ?>
    </section>
    <?php 
        if (isAdmin()) {
            echo "<a href='adminEditItem.php?id_item=$_GET[id_item]' class='button'>Изменить товар</a>
            <button class='delete button'>Удалить товар</button>";
        }
    ?>
</main>
<?php include_once __DIR__ . "/footer.php"; ?>