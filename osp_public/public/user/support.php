<?php
include_once __DIR__ . "/../../app/server/function.php";

$isSupport = isSupport();

$whereField = $isSupport ? "`users_support_talks`" : "`users_id_talks`";
$orderByField = $isSupport ? "`datetime_accept_support_talks` DESC" : " `id_talks` DESC";


$messages = makeSelectQuery("SELECT
    `user`.`id_users` AS `user_id`,
    `user`.`name_users` AS `user_name`,
    `user`.`roles_id_users` AS `user_role`,
    `support`.`id_users` AS `support_id`,
    `support`.`name_users` AS `support_name`,
    `support`.`roles_id_users` AS `support_role`,
    `users_write_supports`,
    `title_talks`,
    `is_end_talks`,
    `talks_id_supports`,
    `text_supports`,
    `image_supports`,
    `datetime_supports`,
    `users_id_talks`
    FROM `supports`
    JOIN `talks` ON `id_talks` = `talks_id_supports`
    JOIN `users` AS `user` ON `user`.`id_users` = `users_id_talks`
    LEFT JOIN `users` AS `support` ON `support`.`id_users` = `users_support_talks`
    WHERE $whereField = ?
    ORDER BY $orderByField 
", [getUserID()], false);
if ($messages == "FAIL") {
   redirect();
}

$messagesHTML = getStartMessageHTML($messages, $isSupport);

$startTalkFrom = "";
if (!$isSupport) {
    $startTalkFrom .= "<form action='/user/support.php' method='POST' class='form start-talk'>
        <legend class='legend'>Новая переписка</legend>
        <div class='field'>
            <label class='label'></label>
            <textarea class='input' data-name='title_talks'></textarea>
            <span class='error-wrapper'>
                <p class='error'></p>
            </span>
        </div>
        <div class='field'>
            <label class='label'></label>
            <textarea class='input' data-name='text_supports'></textarea>
            <span class='error-wrapper'>
                <p class='error'></p>
            </span>
        </div>
        <div class='field'>
            <label class='label'></label>
            <input class='input' type='file' data-name='image_supports'>
            <span class='error-wrapper'>
                <p class='error'></p>
            </span>
        </div>
        <div class='field'>
            <input class='button' type='submit' name='submit_button' value='Отправить'>
        </div>
    </form>";
}

include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <?= $startTalkFrom ?>
    <section class="">
        <?= $messagesHTML == "" ? "<h2 class='notfound'>Пусто</h2>" : "<h1>Ваши переписки</h1>$messagesHTML" ?>
    </section>
</main>

<template>
    <?= getMessageHTML() ?>
</template>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>