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

    if ($validatedData["isCorrect"]) {
        $userInfo = makeSelectQuery("SELECT `id_users`, `password_users` FROM `users` WHERE `email_users` = ?", [$validatedData["data"]["email_users"]], true);
        if ($userInfo === "FAIL") {
            $_SESSION["server"] = "Не удалось найти пользователя";
        } else if (!empty($userInfo) && password_verify($validatedData["data"]["password_users"], $userInfo["password_users"])) {
            $_SESSION["id_user"] = $userInfo["id_users"];
            clearValidatedSession();
            redirect();
        } else {
            $_SESSION["server"] = "Не верный пароль или почта";
        }
    } else {
        $_SESSION["server"] = "Не корректные данные";
    }
    redirectYourself();
}

getModalHTML();
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
            <input class="input button" type="submit" name="submit_button" value="Войти">
        </div>
    </form>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>