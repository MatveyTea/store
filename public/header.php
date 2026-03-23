<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

$partHeader = "";
$partHeaderMobile = "";

if (isUserAuth()) {
    if (isAdmin()) {
        $partHeader .= "<a href='admin.php'>Админ панель</a>";
    }
    $img = getValidImage(FOLDER_UPLOAD . "/" . FOLDER_AVATARS, getUserInfo()["avatar_users"]);
    $partHeader .= "<a href='basket.php'>Корзина</a>
        <a href='profile.php'><img class='avatar' src=$img></a>
    ";
    $partHeaderMobile = $partHeader;
} else {
    $partHeader = "<span>
        <a href='auth.php'>Вход</a>
        <a href='reg.php'>Регистрация</a>
    </span>";
    $partHeaderMobile .= "<a href='auth.php'>Вход</a>
        <a href='reg.php'>Регистрация</a>
    ";
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
    <header class="header">
        <nav class="content header-desktop">
            <a href="/">Каталог</a>
            <?= $partHeader ?>
        </nav>
        <nav class="content header-mobile">
            <div class="header-mobile-burger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="header-mobile-content">
                <a href="/">Каталог</a>
                <?= $partHeaderMobile ?>
            </div>
        </nav>
    </header>
</body>
</html>