<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isUserAuth()) {
    redirect("user/auth.php");
}

$itemsHTML = getItems(0, "JOIN `favorites` ON `items_id_favorites` = `id_items` WHERE `users_id_favorites` = ?", [getUserID()]);

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <section class="items">
        <?= $itemsHTML == "" ? "<h1 class='notfound'>У вас нет избранных товаров</h1>" : "<h2>Избранные товары</h2>$itemsHTML" ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>