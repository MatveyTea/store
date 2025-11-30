<?php
include_once __DIR__ . "/config/config.php";

$isLogin = !empty($_SESSION["id_user"]);

$partHeader = "";

if ($isLogin) {
    $partHeader = "<span>
        <a href='profile.php'>Профиль</a>
    </span>";
} else {
    $partHeader = "<span>
        <a href='log.php'>Войти</a>
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
        <nav>
            <a href="catalog.php">Каталог</a>
            <a href="/">Тест 2</a>
            <a href="/">Тест 3</a>
            <a href="/">Тест 4</a>
            <?= $partHeader ?>
        </nav>
    </header>
</body>
</html>