<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth()) {
    redirect("auth.php");
}

if (!empty($_POST["submit_button"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES));

    if ($validatedData["isCorrect"]) {
        $result = getUpdateSQL(array_diff_key($validatedData["data"], ["re_password_users" => true]));
        $result["params"][] = getUserID();
        $isSuccess = makeSafeQuery("UPDATE `users` SET $result[sql] WHERE `id_users` = ?", $result["params"]);
        if ($isSuccess) {
            $_SESSION["server"] = "Данные обновлены";
        } else {
            $_SESSION["server"] = "Не удалось обновить информацию";
        }
    } else {
        $_SESSION["server"] = "Не корректные данные";
    }
    redirectYourself();
}

$userInfo = getUserInfo();
getModalHTML();

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
            <img class="avatar" src="<?= getValidImage(FOLDER_UPLOAD . "/" . FOLDER_AVATARS, $userInfo["avatar_users"]) ?>">
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
            <input type="submit" class="input button" name="submit_button" value="Обновить">
        </div>
    </form>
    <div>
        <a class="button" href='logout.php'>Выйти из аккаунта</a>
    </div>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>