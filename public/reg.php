<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (isUserAuth()) {
    redirect();
}

if (!empty($_POST["submit_button"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData($_POST);
    $_SESSION["data"] = $validatedData["data"];
    $_SESSION["errorField"] = $validatedData["errorField"];

    if ($validatedData["isCorrect"]) {
        try {
            $stmt = $link->prepare("SELECT `id_users` FROM `users` WHERE `email_users` = ?");
            $stmt->execute([$validatedData["data"]["email_users"]]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $link->prepare("INSERT INTO `users` (`email_users`, `password_users`, `name_users`, `date_create_users`) VALUES (?, ?, ?, ?)")->execute([$validatedData["data"]["email_users"], password_hash($validatedData["data"]["password_users"], PASSWORD_DEFAULT), $validatedData["data"]["name_users"], date("y-m-d")]);
                redirect("auth.php");
            } else {
                $_SESSION["errorField"]["server"] = "Такая почта занята";
            }
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось вставить пользователя";
        }
    }
    redirect("reg.php");
}

include_once __DIR__ . "/header.php";
?>
<main class="content">
    <form action="reg.php" method="POST" class="form">
        <legend>Регистрация</legend>
        <div class="field">
            <label class="label" for="name_users">Имя</label>
            <input class="input" type="text" placeholder="Введите имя" id="name_users" name="name_users" value="<?= $_SESSION["data"]["name_users"] ?? "" ?>">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["name_users"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <label class="label" for="email_users">Почта</label>
            <input class="input" type="text" placeholder="Введите почту" id="email_users" name="email_users" autocomplete="username" value="<?= $_SESSION["data"]["email_users"] ?? "" ?>">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["email_users"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <label class="label" for="password_users">Пароль</label>
            <input class="input" type="password" placeholder="Введите пароль" id="password_users" name="password_users" autocomplete="new-password">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["password_users"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <label class="label" for="re_password_users">Повтор пароля</label>
            <input class="input" type="password" placeholder="Введите пароль" id="re_password_users" name="re_password_users" autocomplete="new-password">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["re_password_users"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <p class="error server-error"><?= $_SESSION["errorField"]["server"] ?? "" ?></p>
            <input class="input button" type="submit" id="submit_button" name="submit_button" value="Зарегистрироваться" class="button">
        </div>
    </form>
</main>

<?php unset($_SESSION["data"], $_SESSION["errorField"]); ?>
<?php include_once __DIR__ . "/footer.php"; ?>