<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty($_SESSION["id_user"])) {
    redirect();
}

if (!empty($_POST["submit_button"])) {
    if (!empty($_POST["name_users"]) && !empty($_POST["email_users"]) && !empty($_POST["password_users"]) && !empty($_POST["re_password_users"])) {
        $name = trim($_POST["name_users"]);
        $email = $_POST["email_users"];
        $password = $_POST["password_users"];
        $rePassword = $_POST["re_password_users"];

        if ($password == $rePassword) {
            try {
                $checkEmail = $link->prepare("SELECT `id_users` FROM `users` WHERE `email_users` = ?");
                $checkEmail->execute([$email]);
                if (empty($checkEmail->fetch(PDO::FETCH_ASSOC)["id_users"])) {
                    $link->prepare("INSERT INTO `users` (`email_users`, `password_users`, `name_users`, `date_create_users`) VALUES (?, ?, ?, ?)")->execute([$email, password_hash($password, PASSWORD_DEFAULT), $name, date("y-m-d")]);
                    redirect("auth.php");
                } else {
                    $_SESSION["error"] = "Такая почта занята";
                }
            } catch (Throwable $e) {
                $_SESSION["error"] = "Не удалось вставить пользователя";
            }
        } else {
            $_SESSION["error"] = "Ошибки";
        }
    } else {
        $_SESSION["error"] = "Заполните все поля";
    }
    redirect("reg.php");
}

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);

include_once __DIR__ . "/header.php";
?>

<form action="reg.php" method="POST" class="content form">
    <legend>Регистрация</legend>
    <div>
        <label for="name_users">Имя</label>
        <input type="text" placeholder="Введите имя" id="name_users" name="name_users">
        <p class="error hidden"></p>
    </div>
    <div>
        <label for="email_users">Почта</label>
        <input type="text" placeholder="Введите почту" id="email_users" name="email_users" autocomplete="username">
        <p class="error hidden"></p>
    </div>
    <div>
        <label for="password_users">Пароль</label>
        <input type="password" placeholder="Введите пароль" id="password_users" name="password_users" autocomplete="new-password">
        <p class="error hidden"></p>
    </div>
    <div>
        <label for="re_password_users">Повтор пароля</label>
        <input type="password" placeholder="Введите пароль" id="re_password_users" name="re_password_users" autocomplete="new-password">
        <p class="error hidden"></p>
    </div>
    <div>
        <input type="submit" id="submit_button" name="submit_button" value="Зарегистрироваться" class="button">
    </div>
    <p><?= $error ?></p>
    <a href="auth.php" class="button">Войти</a>
</form>
