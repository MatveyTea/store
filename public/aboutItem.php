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

$itemHTML = "";

include_once __DIR__ . "/header.php";
?>
<main class="content">
    <section class="about">
        <h1>Рейтинг: <b><?= getRatingItem($_GET["id_item"]) ?></b></h1>
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
</main>
<?php include_once __DIR__ . "/footer.php"; ?>