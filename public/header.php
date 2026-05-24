<?php
$headerHTML = "<a href='/'>Каталог</a>";
$headerMobileHTML = "";

if (isUserAuth()) {
    if (isAdmin()) {
        $headerHTML .= "<a href='admin.php'>Админ панель</a>";
    }
    if (isDeliver()) {
        $headerHTML .= "
            <a href='allOrders.php'>Все заказы</a>
            <a href='myOrders.php'>Мои заказы</a>
        ";
    }
    if (isSupport()) {
        $headerHTML .= "
            <a href='allSupport.php'>Все вопросы</a>
            <a href='support.php'>Мои вопросы</a>
        ";
    } else {
        $headerHTML .= "<a href='support.php'>Техподдержка</a>";
    }
    $userInfo = getUserInfo();
    $img = empty($userInfo["avatar_users"]) ? "" : $userInfo["avatar_users"];
    $headerHTML .= "
        <a href='basket.php'>Корзина</a>
        <a href='favorites.php'>Избранные</a>
        <a href='profile.php'><img class='avatar' src='" . getValidImage(FOLDER_UPLOAD . "/" . FOLDER_AVATARS, $img) . "'></a>
    ";
    $headerMobileHTML = $headerHTML;
} else {
    $headerHTML .= "
        <span>
            <a href='auth.php'>Вход</a>
            <a href='reg.php'>Регистрация</a>
        </span>
    ";
    $headerMobileHTML .= "
        <a href='auth.php'>Вход</a>
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
            <?= $headerHTML ?>
        </nav>
        <nav class="content header-mobile">
            <div class="header-mobile-burger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="header-mobile-content">
                <?= $headerMobileHTML ?>
            </div>
        </nav>
    </header>
</body>
</html>