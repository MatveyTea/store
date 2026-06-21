<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isUserAuth()) {
    redirect("user/auth.php");
}

$itemsHTML = getItems(0, "JOIN `favorites` ON `items_id_favorites` = `id_items` WHERE `users_id_favorites` = ?", [getUserID()]);
if ($itemsHTML == "") {
    $itemsHTML = "<h2 class='notfound'>У Вас ещё нет избранных товаров.</h2>";
} else {
    $itemsHTML = "
        <h2 class='title'>Избранные товары</h2> <article class='items'>
        $itemsHTML
        </article>
    ";
}

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <section class="items-container">
        <?= $itemsHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>