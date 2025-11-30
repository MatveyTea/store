<?php
include_once __DIR__ . "/header.php";

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
                redirect();
            }
        } catch (Throwable $e) {
            $_SESSION["error"] = "Не удалось найти пользователя";
        }
    } else {
        $_SESSION["error"] = "Ошибки";
    }
    redirect("log.php");
}

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
?>

<form action="log.php" method="POST">
    <legend>Вход</legend>
    <div>
        <label for="email_users">Почта</label>
        <input type="text" placeholder="Введите почту" id="email_users" name="email_users">
        <p class="error hidden"></p>
    </div>
    <div>
        <label for="password_users">Пароль</label>
        <input type="password" placeholder="Введите логин" id="password_users" name="password_users">
        <p class="error hidden"></p>
    </div>
    <div>
        <input type="submit" id="submit_button" name="submit_button" value="Войти">
    </div>
    <p><?= $error ?></p>
</form>