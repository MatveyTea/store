<?php
include_once __DIR__ . "/../../app/server/function.php";

if (isUserAuth()) {
    redirect();
}

if (!empty($_POST["submit_button"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData($_POST);
    $_SESSION["data"] = $validatedData["data"];

    if ($validatedData["isCorrect"]) {
        $userInfo = makeSelectQuery("SELECT `id_users`, `password_users`, `is_banned_users` FROM `users` WHERE `email_users` = ?", [$validatedData["data"]["email_users"]], true);
        if ($userInfo === "FAIL") {
            $_SESSION["server"] = "Не удалось найти пользователя";
        } else if (!empty($userInfo) && password_verify($validatedData["data"]["password_users"], $userInfo["password_users"])) {
            if ($userInfo["is_banned_users"] == 0) {
                $_SESSION["id_user"] = $userInfo["id_users"];
                clearValidatedSession();
                redirect("user/profile.php");
            } else {
                $_SESSION["server"] = "Ваш аккаунт заблокирован";
            }
        } else {
            $_SESSION["server"] = "Не верный пароль или почта";
        }
    } else {
        $_SESSION["server"] = "Не корректные данные";
    }
    redirectYourself();
}

$data = $_SESSION["data"] ?? [];
getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <form action="/user/auth.php" method="POST" class="form">
        <legend class="legend">Вход</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $data["email_users"] ?? "" ?>" data-name="email_users" data-is-insert-server="<?= empty($data["email_users"]) ? 1 : 0 ?>" autocomplete="username">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="password" data-name="password_users" autocomplete="current-password">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <a href="reg.php" class="button">Ещё нет аккаунта?</a>
            <input class="button" type="submit" name="submit_button" value="Войти">
        </div>
    </form>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>