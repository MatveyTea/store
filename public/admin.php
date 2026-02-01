<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || !isAdmin()) {
    redirect();
}

include_once __DIR__ . "/header.php";
?>
<main class="content">
    <a href="adminAddItem.php" class="button">Добавить товар</a>
    <!-- <a href="adminEditItem.php" class="button">Изменить товар</a> -->
    <a href="adminTable.php?table=properties" class="button">Добавить свойства товаров</a>
    <a href="adminTable.php?table=status" class="button">Добавить статус товаров</a>
    <a href="adminTable.php?table=items_type" class="button">Добавить тип товаров</a>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>