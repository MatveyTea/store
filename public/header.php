<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

$partHeader = "";

if (isUserAuth()) {
    $userData = $link->prepare("SELECT `name_users`, `avatar_users` FROM `users` WHERE `id_users` = ?");
    $userData->execute([getUserID()]);
    $userData = $userData->fetch(PDO::FETCH_ASSOC);
    if (!empty($_SESSION["is_admin"])) {
        $partHeader .= "<a href='admin.php'>Админ панель</a>";
    }
    $img = getValidImage(FOLDER_PROFILE, getUserInfo()["avatar_users"]);
    $partHeader .= "<a href='profile.php'><img class='avatar' src=$img></a>";
} else {
    $partHeader = "<span>
        <a href='auth.php'>Войти</a>
        <a href='reg.php'>Зарегистрироваться</a>
    </span>";
}

$currentFileName = pathinfo($_SERVER["SCRIPT_NAME"], PATHINFO_FILENAME);
$links = "";

if (file_exists(__DIR__ . "/js/$currentFileName.js")) {
    $links .= "<script src='/js/$currentFileName.js' defer></script>";
}

if (file_exists(__DIR__ . "/css/$currentFileName.css")) {
    $links .= "<link rel='stylesheet' href='/css/$currentFileName.css'>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Интернет-магазин</title>
    <link rel="shortcut icon" href="/img/main/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/function.js" defer></script>
    <?= $links ?>
</head>
<body>
    <header>
        <nav class="content">
            <a href="/">Каталог</a>
            <a href="basket.php">Корзина</a>
            <?= $partHeader ?>
        </nav>
    </header>
</body>
</html>