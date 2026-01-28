<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty($_POST["submit_button"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES));
    $_SESSION["data"] = $validatedData["data"];
    $_SESSION["errorField"] = $validatedData["errorField"];

    if ($validatedData["isCorrect"]) {
        $properties = $validatedData["data"]["items_properties"];
        unset($validatedData["data"]["items_properties"]);
        $result = getInsertSQL($validatedData["data"]);
        $result["sql"] .= ",`date_add_items`";
        $result["params"][] = date("y-m-d");
        $result["question"] .= ",?";

        try {
            $link->prepare("INSERT INTO `items` ($result[sql]) VALUES ($result[question])")->execute($result["params"]);
            $id = $link->lastInsertId();
            $insertSql = "";
            $insetParams = [];
            foreach ($properties as $property) {
                $insertSql .= "INSERT INTO `items_properties`
                    (`items_id_items_properties`, `properties_id_items_properties`, `description_items_properties`)
                    VALUES (?, ?, ?);
                ";
                array_push($insetParams, $id, $property["name"], $property["description"]);
            }
            if ($insertSql != "" && $insetParams != []) {
                $link->prepare($insertSql)->execute($insetParams);
            }
            
            unset($_SESSION["data"], $_SESSION["errorField"]);
            $_SESSION["errorField"]["server"] = "Товар добавлен";
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось добавить товар " . $e->getMessage();
        }
    }

    redirect("admin.php");
}

$stmt = $link->query("SELECT * FROM `properties`");
$stmt->execute();
$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
$selectName = "";
foreach ($stmt as $option) {
    $selectName .= "<option value='$option[id_properties]'>$option[name_properties]</option>";
}

$propertiesHTML = "";
$count = 0;
foreach ($_SESSION["data"]["items_properties"] ?? [] as $key => $property) {
    $selectSelected = substr($selectName, 0, strpos($selectName, "value='$property[name]'")) . "selected " . substr($selectName, strpos($selectName, "value='$property[name]'"));
    $no = $key + 1;
    $propertiesHTML .= "<div class='field additional'>
        <div class='field'>
            <label class='label'>Имя $no</label>
            <select class='input' name='items_properties_name'>
                <option value='' disabled selected>Выбрать</option>
                $selectSelected
            </select>
            <p class='error'></p>
        </div>
        <div class='field'>
            <label class='label'>Описание $no</label>
            <input class='input' name='items_properties_description' type='text' placeholder='Введите описание' value='$property[description]'>
            <p class='error' data-has-error=" . ($_SESSION["errorField"]["items_properties"] ?? 0) . "></p>
        </div>
    </div>
    ";
    $count++;
}

$types = $link->query("SELECT * FROM `items_type`")->fetchAll(PDO::FETCH_ASSOC);
$typesHTML = "";
foreach ($types as $type) {
    $selected = $type["id_items_type"] == ($_SESSION["data"]["items_type_id_items"] ?? "") ? "selected" : "";
    $typesHTML .= "<option value='$type[id_items_type]' $selected>$type[name_items_type]</option>";
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
            <label class="label" for="items_type_id_items">Тип товара</label>
            <select class="input" name="items_type_id_items" id="items_type_id_items">
                <option value="" disabled selected>Выбрать</option>
                <?= $typesHTML ?>
            </select>
            <p class="error"></p>
        </div>
        <div class="field">
            <input type="hidden" class="hidden" name="items_properties" id="items_properties">
            <button class="additional button">Добавить дополнительное описание</button>
        </div>
        <?= $propertiesHTML ?>
        <div class="field">
            <p class="error server-error"><?= $_SESSION["errorField"]["server"] ?? "" ?></p>
            <input type="submit" id="submit_button" name="submit_button" value="Добавить" class="input button">
        </div>
    </form>
</main>

<div class="field additional hidden" data-count="<?= $count ?>">
    <div class="field">
        <label class="label">Имя</label>
        <select class="input">
            <option value="" disabled>Выбрать</option>
            <?= $selectName ?>
        </select>
        <p class="error"></p>
    </div>
    <div class="field">
        <label class="label">Описание</label>
        <input class="input" type="text" placeholder="Введите описание">
        <p class="error"></p>
    </div>
</div>

<?php unset($_SESSION["data"], $_SESSION["errorField"]); ?>
<?php include_once __DIR__ . "/footer.php"; ?>