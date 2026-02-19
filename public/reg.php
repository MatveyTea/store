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


    echo "<pre>";
    print_r($validatedData);

    if ($validatedData["isCorrect"]) {
        try {
            $stmt = $link->prepare("SELECT `id_users` FROM `users` WHERE `email_users` = ?");
            $stmt->execute([$validatedData["data"]["email_users"]]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $result = getInsertSQL(array_merge($validatedData["data"], ["date_create_users" => date("y-m-d")]));
                $link->prepare("INSERT INTO `users` ($result[sql]) VALUES ($result[question])")->execute($result["params"]);
                clearValidatedSession();
                redirect("auth.php");
            } else {
                $_SESSION["errorField"]["server"] = "Такая почта занята";
            }
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось вставить пользователя";
        }
    } else if ($validatedData["errorField"]["password_users"] == 1) {
        $_SESSION["errorField"]["server"] = "Пароли должны совпадать";
    }
    redirectYourself();
}

$data = $_SESSION["data"] ?? [];
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
            <p class="error server-error"><?= $_SESSION["errorField"]["server"] ?? "" ?></p>
            <input class="input button" type="submit" name="submit_button" value="Зарегистрироваться">
        </div>
    </form>
</main>

<?php clearValidatedSession(); include_once __DIR__ . "/footer.php";  ?>