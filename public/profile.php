<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth()) {
    redirect("auth.php");
}

if (!empty($_POST["submit_button"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData([...$_POST, ...$_FILES]);
    $_SESSION["errorField"] = $validatedData["errorField"];

    if ($validatedData["isCorrect"]) {
        $result = getUpdateSQL($validatedData["data"]);
        try {
            $link->prepare("UPDATE `users` SET $result[sql] WHERE `id_users` = ?")->execute([...$result["params"], getUserID()]);
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось изменить!";
        }
    }
    redirect("profile.php");
}

$userInfo = getUserInfo();
include_once __DIR__ . "/header.php";
?>
<main class="content">
    <form action="profile.php" method="POST" class="form" enctype="multipart/form-data">
        <legend>Профиль</legend>
        <div class="field">
            <label class="label" for="name_users">Имя</label>
            <input class="input" type="text" placeholder="Введите имя" id="name_users" name="name_users" value="<?= $userInfo["name_users"] ?>" autocomplete="username">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["name_users"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <label class="label" for="avatar_users">Аватар</label>
            <input class="input" type="file" id="avatar_users" name="avatar_users">
            <img class="avatar" src="<?= getValidImage(FOLDER_PROFILE, $userInfo["avatar_users"]) ?>">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["avatar_users"] ?? 0 ?>"></p>
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
            <input type="submit" id="submit_button" name="submit_button" value="Обновить" class="input button">
        </div>
    </form>
    <a href="basket.php">Корзина</a>
    <a href='logout.php'>Выйти из аккаунта</a>
</main>
<?= clearValidatedSession() ?>