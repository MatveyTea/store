<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || !isAdmin() || empty($_GET["id_item"])) {
    redirect();
}

$idItem = $_GET["id_item"];

if (!empty($_POST["submit_button"]) && count($_POST) > 1) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES));
    $_SESSION["data"] = $validatedData["data"];
    $_SESSION["errorField"] = $validatedData["errorField"];

    if ($validatedData["isCorrect"]) {
        $sql = "";
        $params = [];
        if (!empty($validatedData["data"]["items_properties"])) {
            $itemProperties = $validatedData["data"]["items_properties"];
            foreach ($itemProperties as $property) {
                if ($property["id_items_properties"] == "") {
                    $sql .= "INSERT INTO `items_properties`
                        (`items_id_items_properties`, `properties_id_items_properties`, `description_items_properties`)
                        VALUES (?, ?, ?);
                    ";
                    array_push($params, $idItem, $property["properties_id_items_properties"], $property["description_items_properties"]);
                } else {
                    $tempSQL = getUpdateSQL(array_diff_key($property, ["id_items_properties" => true]));
                    $sql .= "UPDATE `items_properties` SET $tempSQL[sql] WHERE `id_items_properties` = ?;";
                    $tempSQL["params"][] = $property["id_items_properties"];
                    array_push($params, ...$tempSQL["params"]);
                }
            }
        }
        try {
            $result = getUpdateSQL(array_diff_key($validatedData["data"], ["items_properties" => true, "id_items" => true]));
            if ($result['sql'] != "" && $result["params"] != []) {
                $link->prepare("UPDATE `items` SET $result[sql] WHERE `id_items` = ?")->execute([...$result["params"], $idItem]);
            }

            if ($sql != "" && $params != []) {
                $link->prepare($sql)->execute($params);
            }
                
            clearValidatedSession();
            $_SESSION["errorField"]["server"] = "Товар изменен";
        } catch (Throwable $e) {
            $_SESSION["errorField"]["server"] = "Не удалось обновить товар " . $e->getMessage();
        }
    }

    redirect("adminEditItem.php", "id_item=$idItem");
}


$itemInfo = $link->prepare("SELECT
    `name_items`,
    `count_items`,
    `image_items`,
    `cost_items`,
    `date_add_items`,
    `description_items`,
    `items_type_id_items`
    FROM `items`
    JOIN `items_type` ON `items_type`.`id_items_type` = `items`.`items_type_id_items`
    WHERE `id_items` = ?
");
$itemInfo->execute([$idItem]);
$itemInfo = $itemInfo->fetch(PDO::FETCH_ASSOC);

$stmt = $link->query("SELECT * FROM `properties`");
$stmt->execute();
$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
$selectName = "";
foreach ($stmt as $option) {
    $selectName .= "<option value='$option[id_properties]'>$option[name_properties]</option>";
}

$itemProperties = $link->prepare("SELECT
    `id_items_properties`,
    `properties_id_items_properties`,
    `description_items_properties`
    FROM `items_properties`
    JOIN `properties` ON `properties`.`id_properties` = `items_properties`.`properties_id_items_properties`
    WHERE `items_properties`.`items_id_items_properties` = ?");
$itemProperties->execute([$idItem]);
$itemProperties = $itemProperties->fetchAll(PDO::FETCH_ASSOC);
$propertiesHTML = "";

foreach ($itemProperties as $key => $property) {
    $startIndexValue = strpos($selectName, "value='$property[properties_id_items_properties]'");
    $selectSelected = substr($selectName, 0, $startIndexValue) . "selected " . substr($selectName, $startIndexValue);
    $propertiesHTML .= "<div class='field additional'>
        <span class='button'>Удалить</span>
        <div class='field hidden'>
            <label class='label'></label>
            <input class='hidden input' name='id_items_properties' type='text' value='$property[id_items_properties]'>
            <p class='error'></p>
        </div>
        <div class='field'>
            <label class='label'>Свойство " . $key + 1 . "</label>
            <select class='input' name='properties_id_items_properties'>
                <option value='' disabled selected>Выбрать</option>
                $selectSelected
            </select>
            <p class='error'></p>
        </div>
        <div class='field'>
            <label class='label'>Описание " . $key + 1 . "</label>
            <input class='input' name='description_items_properties' type='text' placeholder='Введите описание' value='$property[description_items_properties]'>
            <p class='error'></p>
        </div>
    </div>
    ";
}

$types = $link->query("SELECT * FROM `items_type`")->fetchAll(PDO::FETCH_ASSOC);
$typesHTML = "";
foreach ($types as $type) {
    $selected = $type["id_items_type"] == $itemInfo["items_type_id_items"] ? "selected" : "";
    $typesHTML .= "<option value='$type[id_items_type]' $selected>$type[name_items_type]</option>";
}

include_once __DIR__ . "/header.php";
?>
<main class="content">
    <form action="adminEditItem.php?id_item=<?= $idItem ?>" method="POST" class="form" enctype="multipart/form-data">
        <legend>Изменение товара</legend>
        <div class="field">
            <label class="label" for="name_items">Имя товара</label>
            <input class="input" type="text" placeholder="Введите имя товара" id="name_items" name="name_items" value="<?= $itemInfo["name_items"] ?? "" ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label" for="count_items">Количество</label>
            <input class="input" type="number" placeholder="Введите количество" id="count_items" name="count_items" value="<?= $itemInfo["count_items"] ?? "" ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label" for="cost_items">Цена</label>
            <input class="input" type="number" placeholder="Введите цену" id="cost_items" name="cost_items" value="<?= $itemInfo["cost_items"] ?? "" ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label" for="image_items">Изображение</label>
            <input class="input" type="file" id="image_items" name="image_items">
            <img src="<?= getValidImage(FOLDER_INDEX, $itemInfo["image_items"]) ?>">
            <p class="error"></p>
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
            <input type="hidden" class="hidden input" name="items_properties" id="items_properties">
            <p class="error"></p>
            <button class="additional button">Добавить дополнительное описание</button>
        </div>
        <?= $propertiesHTML ?>
        <div class="field">
            <p class="error server-error"><?= $_SESSION["errorField"]["server"] ?? "" ?></p>
            <input type="submit" id="submit_button" name="submit_button" value="Обновить" class="input button">
        </div>
    </form>
</main>

<div class="field additional hidden" data-count="<?= count($itemProperties) ?>">
    <span class="button">Удалить</span>
    <div class="field hidden">
        <label class="label"></label>
        <input class="hidden input" type="text">
        <p class="error"></p>
    </div>
    <div class="field">
        <label class="label"></label>
        <select class="input">
            <option value="" disabled selected>Выбрать</option>
            <?= $selectName ?>
        </select>
        <p class="error"></p>
    </div>
    <div class="field">
        <label class="label"></label>
        <input class="input" type="text" placeholder="Введите описание">
        <p class="error"></p>
    </div>
</div>

<?php clearValidatedSession(); include_once __DIR__ . "/footer.php"; ?>