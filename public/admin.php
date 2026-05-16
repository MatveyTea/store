<?php
include_once __DIR__ . "/function.php";

if (!isAdmin()) {
    redirect();
}

include_once __DIR__ . "/header.php";
?>

<main class="content">
    <article>
        <h2>Административная панель</h2>
        <a href="adminAddItem.php" class="button">Добавление товаров</a>
        <a href="adminEditTable.php?table=properties" class="button">Редактировать свойства товаров</a>
        <a href="adminEditTable.php?table=status" class="button">Редактировать статусы доставки</a>
        <a href="adminEditTable.php?table=items_type" class="button">Редактировать типы товаров</a>
        <a href="adminEditUser.php" class="button">Редактирование пользователей</a>
    </article>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>