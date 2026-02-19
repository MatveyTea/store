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
                clearValidatedSession();
                redirect();
            } else {
                $_SESSION["errorField"]["server"] = "Не верный пароль или почта";
            }
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось найти пользователя";
        }
    }
    redirectYourself();
}

include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action="auth.php" method="POST" class="form">
        <legend class="legend">Вход</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $_SESSION["data"]["email_users"] ?? "" ?>" data-name="email_users" data-is-insert-server="<?= empty($_SESSION["data"]["email_users"]) ? 1 : 0 ?>" autocomplete="username">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="password" data-name="password_users" data-is-insert-server="0" autocomplete="current-password">
            <p class="error"></p>
        </div>
        <div class="field">
            <p class="error server-error"><?= $_SESSION["errorField"]["server"] ?? "" ?></p>
            <input class="input button" type="submit" name="submit_button" value="Войти">
        </div>
    </form>
</main>

<?php clearValidatedSession(); include_once __DIR__ . "/footer.php"; ?>