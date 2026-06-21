<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isAdmin()) {
    redirect();
}

include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <article>
        <h2 class="title">Административная панель</h2>
        <a href="/admin/addItem.php" class="button">Добавление товаров</a>
        <a href="/admin/editTable.php?table=properties" class="button">Редактировать свойства товаров</a>
        <a href="/admin/editTable.php?table=items_type" class="button">Редактировать типы товаров</a>
        <a href="/admin/editUser.php" class="button">Редактирование пользователей</a>
    </article>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>