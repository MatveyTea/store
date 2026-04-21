<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin()) {
    redirect();
}

include_once __DIR__ . "/header.php";
?>

<main class="content">
    <article>
        <h2>Редактирование</h2>
        <a href="adminAddItem.php" class="button">Товары</a>
        <a href="adminEditTable.php?table=properties" class="button">Свойства</a>
        <a href="adminEditTable.php?table=status" class="button">Статусы</a>
        <a href="adminEditTable.php?table=items_type" class="button">Типы</a>
        <a href="adminEditUser.php" class="button">Пользователи</a>
    </article>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>