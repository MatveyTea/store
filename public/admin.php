<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty($_POST["submit_button"])) {
    if (!empty($_POST["name_items"]) && !empty($_POST["count_items"]) && !empty($_POST["cost_items"]) && !empty($_FILES["image_items"])) {
        $name = $_POST["name_items"];
        $count = $_POST["count_items"];
        $cost = $_POST["cost_items"];
        $image = $_FILES["image_items"];

        $extension = pathinfo($image["name"], PATHINFO_EXTENSION);
        if (in_array($extension, ["jpg", "png", "webp"])) {
            $datetime = date("Y-m-d H-i-s") . ".$extension";
            if (move_uploaded_file($image["tmp_name"], __DIR__ . "/img/index/$datetime")) {
                try {
                    $link->prepare("INSERT INTO `items` (`name_items`, `count_items`, `cost_items`, `image_items`) VALUES (?, ?, ?, ?)")->execute([$name, $count, $cost, $datetime]);
                } catch (Throwable $e) {
                    $_SESSION["error"] = "Ошибки";
                }
            } else {
                $_SESSION["error"] = "Ошибки";
            }
        } else {
            $_SESSION["error"] = "Неверный формат";
        }

    } else {
        $_SESSION["error"] = "Заполните все поля";
    }
}

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);

include_once __DIR__ . "/header.php";
?>

<form method="POST" action="admin.php" class="content form" enctype="multipart/form-data">
    <form action="auth.php" method="POST" class="content form">
    <legend>Добавление товара</legend>
    <div>
        <label for="name_items">Имя товара</label>
        <input type="text" placeholder="Введите имя" id="name_items" name="name_items">
        <p class="error hidden"></p>
    </div>
    <div>
        <label for="count_items">Количество</label>
        <input type="text" placeholder="Введите количество" id="count_items" name="count_items">
        <p class="error hidden"></p>
    </div>
    <div>
        <label for="image_items">Картинка</label>
        <input type="file" id="image_items" name="image_items">
        <p class="error hidden"></p>
    </div>
        <div>
        <label for="cost_items">Цена</label>
        <input type="text" placeholder="Введите цену" id="cost_items" name="cost_items">
        <p class="error hidden"></p>
    </div>
    <div>
        <input type="submit" id="submit_button" name="submit_button" value="Добавить" class="button">
    </div>
    <p class="error"><?=  $error ?></p>
</form>