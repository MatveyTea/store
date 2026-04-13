<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin()) {
    redirect();
}

include_once __DIR__ . "/header.php";
?>

<main class="content">
    <div class="add">
        <h2>Добавление</h2>
        <a href="adminAddItem.php" class="button">Товары</a>
    </div>
    <div class="edit">
        <h2>Добавление, изменение, удаление товаров</h2>
        <a href="adminEditTable.php?table=properties" class="button">Свойства</a>
        <a href="adminEditTable.php?table=status" class="button">Статусы</a>
        <a href="adminEditTable.php?table=items_type" class="button">Типы</a>
        <a href="adminEditUser.php" class="button">Пользователи</a>
    </div>
    <div class="edit">
        <h2>Пользователи</h2>
        <a href="adminEditUser.php" class="button">Пользователи</a>
    </div>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>