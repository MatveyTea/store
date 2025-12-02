<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty($_SESSION["id_user"])) {
    redirect();
}

if (!empty($_POST["submit_button"])) {
    if (!empty($_POST["email_users"]) && !empty($_POST["password_users"])) {
        $email = $_POST["email_users"];
        $password = $_POST["password_users"];
        try {
            $check = $link->prepare("SELECT `id_users`, `email_users`, `password_users` FROM `users` WHERE `email_users` = ?");
            $check->execute([$email]);
            $check = $check->fetch(PDO::FETCH_ASSOC);
            if (!empty($check) && password_verify($password, $check["password_users"])) {
                $_SESSION["id_user"] = $check["id_users"];
                if ($check["id_users"] == 1) {
                    $_SESSION["is_admin"] = true;
                }
                redirect();
            }
        } catch (Throwable $e) {
            $_SESSION["error"] = "Не удалось найти пользователя";
        }
    } else {
        $_SESSION["error"] = "Заполните все поля";
    }
    redirect("auth.php");
}

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);

include_once __DIR__ . "/header.php";
?>

<form action="auth.php" method="POST" class="content form">
    <legend>Вход</legend>
    <div>
        <label for="email_users">Почта</label>
        <input type="text" placeholder="Введите почту" id="email_users" name="email_users" autocomplete="username">
        <p class="error hidden"></p>
    </div>
    <div>
        <label for="password_users">Пароль</label>
        <input type="password" placeholder="Введите логин" id="password_users" name="password_users" autocomplete="current-password">
        <p class="error hidden"></p>
    </div>
    <div>
        <input type="submit" id="submit_button" name="submit_button" value="Войти" class="button">
    </div>
    <p><?= $error ?></p>
    <a href="reg.php" class="button">Зарегистрироваться</a>
</form>