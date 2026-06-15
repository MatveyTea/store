<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isUserAuth()) {
    redirect("user/auth.php");
}

if (!empty($_POST["submit_button"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES));

    if ($validatedData["isCorrect"]) {
        $isSuccess = false;
        if (!empty($validatedData["data"]["avatar_users"])) {
            $tempUserImg = getUserInfo();
            $result = getUpdateSQL(array_merge(array_diff_key($validatedData["data"], ["re_password_users" => true, "avatar_users" => true]), ["avatar_users" => $validatedData["data"]["avatar_users"]["current_name"]]));
            $result["params"][] = getUserID();
            $isSuccess = makeSafeQuery("UPDATE `users` SET $result[sql] WHERE `id_users` = ?", $result["params"]);
            if ($isSuccess && move_uploaded_file($validatedData["data"]["avatar_users"]["tmp_name"], __DIR__ . "/../../app/upload/avatars/" . $validatedData["data"]["avatar_users"]["current_name"])) {
                if (!empty($tempUserImg["avatar_users"])) {
                    unlink(__DIR__ . "/../../app/upload/avatars/$tempUserImg[avatar_users]");
                }
            }
        } else {
            $result = getUpdateSQL(array_diff_key($validatedData["data"], ["re_password_users" => true]));
            $result["params"][] = getUserID();
            $isSuccess = makeSafeQuery("UPDATE `users` SET $result[sql] WHERE `id_users` = ?", $result["params"]);
        }
        if ($isSuccess) {
            $_SESSION["server"] = "Данные обновлены.";
        } else {
            $_SESSION["server"] = "Не удалось обновить данные.";
        }
    } else {
        $_SESSION["server"] = "Не корректные данные.";
    }
    redirectYourself();
}

$userInfo = getUserInfo();
$srcImg = getValidImage("avatars/$userInfo[avatar_users]");
getModalHTML();

include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <form action="/user/profile.php" method="POST" enctype="multipart/form-data" class="form">
        <legend class="legend">Изменение профиля</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $userInfo["name_users"] ?>" data-name="name_users" autocomplete="username">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $userInfo["tel_users"] ?? "" ?>" data-name="tel_users" autocomplete="tel">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input file" type="file" data-name="avatar_users">
            <img class="avatar" src="<?= $srcImg ?>" data-base-src="<?= $srcImg ?>">
            <button class="remove-avatar button hidden">Отмена</button>
            <?= $userInfo["avatar_users"] != null ? "<button class='delete-avatar button'>Удалить</button>" : "" ?>
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field close">
            <label class="label"></label>
            <input class="input" type="password" data-name="password_users" autocomplete="new-password">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field close">
            <label class="label"></label>
            <input class="input" type="password" data-name="re_password_users" autocomplete="new-password">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field"> 
            <button class="button password-appear close">Сменить пароль</button>
            <a href='logout.php' class="button">Выйти из аккаунта</a>
            <input type="submit" class="button" name="submit_button" value="Обновить данные">
        </div>
    </form>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>