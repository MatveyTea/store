<?php
$headerHTML = "<a href='/'>Каталог</a>";
$headerMobileHTML = "";

if (isUserAuth()) {
    if (isAdmin()) {
        $headerHTML .= "<a href='/admin/admin.php'>Админ панель</a>";
    }
    if (isDeliver()) {
        $headerHTML .= "
            <a href='/deliver/allOrders.php'>Все заказы</a>
            <a href='/deliver/myOrders.php'>Мои заказы</a>
        ";
    }
    $supportHeader = "";
    if (isSupport()) {
        $supportHeader .= "
            <a href='/support/allSupport.php'>Все обращения</a>
            <a href='/user/support.php'>Мои обращения</a>
        ";
    } else {
        $supportHeader .= "<a href='/user/support.php'>Техподдержка</a>";
    }
    $userInfo = getUserInfo();
    $img = empty($userInfo["avatar_users"]) ? "" : $userInfo["avatar_users"];
    $headerHTML .= "
        <a href='/user/basket.php'>Корзина</a>
        <a href='/user/favorites.php'>Избранные</a>
        $supportHeader
        <a href='/user/profile.php'><img class='avatar' src='" . getValidImage("avatars/$img") . "'></a>
    ";
    $headerMobileHTML = $headerHTML;
} else {
    $headerHTML .= "
        <span>
            <a href='/user/auth.php'>Вход</a>
            <a href='/user/reg.php'>Регистрация</a>
        </span>
    ";
    $headerMobileHTML .= "
        <a href='/user/auth.php'>Вход</a>
        <a href='/user/reg.php'>Регистрация</a>
    ";
}

$currentFileName = pathinfo($_SERVER["SCRIPT_NAME"], PATHINFO_FILENAME);
$links = "";

if (file_exists(__DIR__ . "/../../public/assets/js/$currentFileName.js")) {
    $links .= "<script src='/assets/js/$currentFileName.js' defer></script>";
}

if (file_exists(__DIR__ . "/../../public/assets/css/$currentFileName.css")) {
    $links .= "<link rel='stylesheet' href='/assets/css/$currentFileName.css'>";
}

$tokenHTML = "";
if (!empty($_SESSION["token"])) {
    $tokenHTML .= "<meta name='token' content='$_SESSION[token]'>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $tokenHTML ?>
    <title>Интернет-магазин</title>
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/main.css">
    <script src="/assets/js/function.js" defer></script>
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