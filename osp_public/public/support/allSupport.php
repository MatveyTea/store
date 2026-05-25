<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isSupport()) {
    redirect();
}

$messagesHTML = "";
$messages = makeSelectQuery("SELECT
    `id_talks`,
    `name_users`,
    `title_talks`
    FROM `talks`
    JOIN `users` ON `id_users` = `users_id_talks`
    WHERE `users_support_talks` IS NULL AND `is_end_talks` = 0
    ORDER BY `id_talks` DESC
", [], false);
if ($messages == "FAIL") {
    redirect();
}

foreach ($messages as $message) {
    $messagesHTML .= "<div>
        <h1>$message[title_talks]</h1>    
        <p>$message[name_users]</p>
        <button data-id-talks='$message[id_talks]' class='button'>Взять</button>
    </div>";
}

include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <article>
        <?= $messagesHTML == "" ? "<h2 class='notfound'>Пусто</h2>" : "<h1>Все переписки</h1>$messagesHTML" ?>
    </article>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>