<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isSupport()) {
    redirect();
}

$messagesHTML = "";
$messages = makeSelectQuery("SELECT
    `id_talks`,
    `name_users`,
    `avatar_users`,
    `title_talks`,
    `datetime_start_user_talks`
    FROM `talks`
    JOIN `users` ON `id_users` = `users_id_talks`
    WHERE `users_support_talks` IS NULL AND `is_end_talks` = 0
    ORDER BY `datetime_start_user_talks` DESC
", [], false);
if ($messages == "FAIL") {
    redirect();
}

foreach ($messages as $message) {
    $messagesHTML .= "<div class='support'>
        <img src='" . getValidImage("avatars/$message[avatar_users]") . "' class='avatar'>
        <p class='support-username'>$message[name_users]</p>
        <p class='support-datetime'>" . dateformat($message["datetime_start_user_talks"]) . "</p>
        <p class='support-title'>$message[title_talks]</p>    
        <button data-id-talks='$message[id_talks]' class='support-accept button'>Принять</button>
    </div>";
}

if ($messagesHTML == "") {
    $messagesHTML = "<h2 class='notfound'>В данный момент нет обращений.</h2> $messagesHTML";
} else {
    $messagesHTML = "<h2 class='title'>Доступные обращения</h2> $messagesHTML";
}

include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <section class="supports">
        <?= $messagesHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>