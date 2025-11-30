<?php
include_once __DIR__ . "/header.php";

if (!empty($_SESSION["id_user"])) {
    redirect();
}

if (!empty($_POST["submit_button"])) {
    if (!empty($_POST["name_users"]) && !empty($_POST["email_users"]) && !empty($_POST["password_users"]) && !empty($_POST["repassword_users"])) {
        $name = trim($_POST["name_users"]);
        $email = $_POST["email_users"];
        $password = $_POST["password_users"];
        $repassword = $_POST["repassword_users"];

        if ($password == $repassword) {
            try {
                $checkemail = $link->prepare("SELECT `id_users` FROM `users` WHERE `email_users` = ?");
                $checkemail->execute([$email]);
                if (empty($checkemail->fetch(PDO::FETCH_ASSOC)["id_users"])) {
                    $link->prepare("INSERT INTO `users` (`email_users`, `password_users`, `name_users`, `date_create_users`) VALUES (?, ?, ?, ?)")->execute([$email, password_hash($password, PASSWORD_DEFAULT), $name, date("y-m-d")]);
                    redirect("log.php");
                } else {
                    $_SESSION["error"] = "Такая почта занята";
                }
            } catch (Throwable $e) {
                $_SESSION["error"] = "Не удалось вставить пользователя";
            }
        } else {
            $_SESSION["error"] = "Ошибки";
        }
    }
    redirect("reg.php");
}

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
?>

<form action="reg.php" method="POST">
    <legend>Регистрация</legend>
    <div>
        <label for="name_users">Имя</label>
        <input type="text" placeholder="Введите имя" id="name_users" name="name_users">
        <p class="error hidden"></p>
    </div>
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
        <label for="repassword_users">Повтор пароля</label>
        <input type="password" placeholder="Введите логин" id="repassword_users" name="repassword_users">
        <p class="error hidden"></p>
    </div>
    <div>
        <input type="submit" id="submit_button" name="submit_button" value="Зарегистироваться">
    </div>
    <p><?= $error ?></p>
</form>