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
        $result = getUpdateSQL(array_diff_key($validatedData["data"], ["re_password_users" => true]));
        try {
            $link->prepare("UPDATE `users` SET $result[sql] WHERE `id_users` = ?")->execute([...$result["params"], getUserID()]);
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось обновить информацию" . $e->getMessage();
        }
    }
    redirectYourself();
}

$userInfo = getUserInfo();
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action="profile.php" method="POST" enctype="multipart/form-data" class="form">
        <legend class="legend">Профиль</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $userInfo["name_users"] ?>" data-name="name_users" data-is-insert-server="<?= !empty($userInfo["name_users"]) ? 1 : 0 ?>" autocomplete="username">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="file" data-name="avatar_users" data-is-insert-server="0">
            <img class="avatar" src="<?= getValidImage(FOLDER_PROFILE, $userInfo["avatar_users"]) ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="password" data-name="password_users" data-is-insert-server="0" autocomplete="new-password">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="password" data-name="re_password_users" data-is-insert-server="0" autocomplete="new-password">
            <p class="error"></p>
        </div>
        <div class="field">
            <p class="error server-error"><?= $_SESSION["errorField"]["server"] ?? "" ?></p>
            <input type="submit" class="input button" name="submit_button" value="Обновить">
        </div>
    </form>
    <div>
        <a href='logout.php'>Выйти из аккаунта</a>
    </div>
</main>

<?php clearValidatedSession(); include_once __DIR__ . "/footer.php";  ?>