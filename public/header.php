<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

$partHeader = "";

if (isUserAuth()) {
    $partHeader = "<span>
        <a href='profile.php'>Профиль</a>
        <a href='logout.php'>Выйти</a>
    </span>";
    if (!empty($_SESSION["is_admin"])) {
        $partHeader .= "<a href='admin.php'>Админ панель</a>";
    }
} else {
    $partHeader = "<span>
        <a href='auth.php'>Войти</a>
        <a href='reg.php'>Зарегистрироваться</a>
    </span>";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Интернет-магазин</title>
    <script src="js/main.js" defer></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="shortcut icon" href="img/main/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <nav class="content">
            <a href="/">Тест 1</a>
            <a href="/">Тест 2</a>
            <a href="/">Тест 3</a>
            <a href="/">Тест 4</a>
            <?= $partHeader ?>
        </nav>
    </header>
</body>
</html>