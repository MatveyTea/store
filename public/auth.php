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
            $stmt = $link->prepare("SELECT `id_users`, `password_users` FROM `users` WHERE `email_users` = ?");
            $stmt->execute([$validatedData["data"]["email_users"]]);
            $stmt = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!empty($stmt) && password_verify($validatedData["data"]["password_users"], $stmt["password_users"])) {
                $_SESSION["id_user"] = $stmt["id_users"];
                unset($_SESSION["data"], $_SESSION["errorField"]);
                redirect();
            } else {
                $_SESSION["errorField"]["server"] = "Не верный пароль или почта";
            }
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось найти пользователя";
        }
    }
    redirect("auth.php");
}

include_once __DIR__ . "/header.php";
?>
<main class="content">
    <form action="auth.php" method="POST" class="form">
        <legend>Вход</legend>
        <div class="field">
            <label class="label" for="email_users">Почта</label>
            <input class="input" type="text" placeholder="Введите почту" id="email_users" name="email_users" autocomplete="username" value="<?= $_SESSION["data"]["email_users"] ?? "" ?>">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["email_users"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <label class="label" for="password_users">Пароль</label>
            <input class="input" type="password" placeholder="Введите логин" id="password_users" name="password_users" autocomplete="current-password">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["password_users"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <p class="error server-error"><?= $_SESSION["errorField"]["server"] ?? "" ?></p>
            <input type="submit" id="submit_button" name="submit_button" value="Войти" class="input button">
        </div>
    </form>
</main>

<?php unset($_SESSION["data"], $_SESSION["errorField"]); ?>
<?php include_once __DIR__ . "/footer.php"; ?>