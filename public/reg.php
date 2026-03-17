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
        $checkEmail = makeSelectQuery("SELECT `id_users` FROM `users` WHERE `email_users` = ?", [$validatedData["data"]["email_users"]], true);
        if ($checkEmail === "FAIL") {
            $_SESSION["server"] = "Не удалось вставить пользователя";
        } else if (empty($checkEmail)) {
            $result = getInsertSQL(array_merge(array_diff_key($validatedData["data"], ["re_password_users"=> true]), ["date_create_users" => date("y-m-d")]));
            $isSuccess = makeSafeQuery("INSERT INTO `users` ($result[sql]) VALUES ($result[question])", $result["params"]);
            if ($isSuccess) {
                clearValidatedSession();
                redirect("auth.php");
            } else {
                $_SESSION["server"] = "Не удалось вставить пользователя";
            }
        } else if (!empty($checkEmail)) {
            $_SESSION["server"] = "Такая почта занята";
        }
    } else if ($validatedData["errorField"]["password_users"] ?? 0 == 1) {
        $_SESSION["server"] = "Пароли должны совпадать";
    } else {
        $_SESSION["server"] = "Не корректные данные";
    }
    redirectYourself();
}

$data = $_SESSION["data"] ?? [];
getModalHTML();

include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action="reg.php" method="POST" class="form">
        <legend class="legend">Регистрация</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $data["name_users"] ?? "" ?>" data-name="name_users" data-is-insert-server="<?= empty($data["name_users"]) ? 1 : 0 ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $data["email_users"] ?? "" ?>" data-name="email_users" data-is-insert-server="<?= empty($data["email_users"]) ? 1 : 0 ?>" autocomplete="username">
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
            <input class="input button" type="submit" name="submit_button" value="Зарегистрироваться">
        </div>
    </form>
</main>

<?php include_once __DIR__ . "/footer.php";  ?>