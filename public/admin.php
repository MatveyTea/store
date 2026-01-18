<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty($_POST["submit_button"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES));
    $_SESSION["data"] = $validatedData["data"];
    $_SESSION["errorField"] = $validatedData["errorField"];

    if ($validatedData["isCorrect"]) {
        try {
            $link->prepare("INSERT INTO `items` (`name_items`, `count_items`, `cost_items`, `image_items`, `date_add_items`) VALUES (?, ?, ?, ?, ?)")->execute([$validatedData["data"]["name_items"], $validatedData["data"]["count_items"], $validatedData["data"]["cost_items"], $validatedData["data"]["image_items"], date("y-m-d")]);
            unset($_SESSION["data"], $_SESSION["errorField"]);
            $_SESSION["errorField"]["server"] = "Товар добавлен";
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось добавить товар " . $e->getMessage();
        }
    }

    redirect("admin.php");
}

include_once __DIR__ . "/header.php";
?>
<main class="content">
    <form action="admin.php" method="POST" class="form" enctype="multipart/form-data">
        <legend>Добавление товара</legend>
        <div class="field">
            <label class="label" for="name_items">Имя товара</label>
            <input class="input" type="text" placeholder="Введите имя товара" id="name_items" name="name_items" value="<?= $_SESSION["data"]["name_items"] ?? "" ?>">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["name_items"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <label class="label" for="count_items">Количество</label>
            <input class="input" type="number" placeholder="Введите количество" id="count_items" name="count_items" value="<?= $_SESSION["data"]["count_items"] ?? "" ?>">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["count_items"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <label class="label" for="cost_items">Цена</label>
            <input class="input" type="number" placeholder="Введите цену" id="cost_items" name="cost_items" value="<?= $_SESSION["data"]["cost_items"] ?? "" ?>">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["cost_items"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <label class="label" for="image_items">Изображение</label>
            <input class="input" type="file" id="image_items" name="image_items">
            <img src="" class="hidden">
            <p class="error" data-has-error="<?= $_SESSION["errorField"]["image_items"] ?? 0 ?>"></p>
        </div>
        <div class="field">
            <p class="error server-error"><?= $_SESSION["errorField"]["server"] ?? "" ?></p>
            <input type="submit" id="submit_button" name="submit_button" value="Добавить" class="input button">
        </div>
    </form>
</main>

<?php unset($_SESSION["data"], $_SESSION["errorField"]); ?>
<?php include_once __DIR__ . "/footer.php"; ?>