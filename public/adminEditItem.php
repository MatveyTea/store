<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin() || empty($_GET["id_item"])) {
    redirect();
}

$idItem = $_GET["id_item"];

if (!empty($_POST["submit_button"]) && count($_POST) > 1) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES, ["id_items" => $idItem]));
    $_SESSION["data"] = $validatedData["data"];

    if ($validatedData["isCorrect"]) {
        $sql = "";
        $params = [];

        $itemProperties = $validatedData["data"]["items_properties"] ?? [];
        $itemPropertiesDB = makeSelectQuery("SELECT `id_items_properties`, `properties_id_items_properties` FROM `items_properties` WHERE `items_id_items_properties` = ?", [$idItem], false);
        $countPropertiesDB = count($itemPropertiesDB);
        $maxProperties = makeSelectQuery("SELECT COUNT(*) as `max_count` FROM `properties`", [], true);

        if ($itemPropertiesDB === "FAIL" || $maxProperties === "FAIL") {
            $_SESSION["server"] = "Не удалось выполнить запрос";
            redirectYourself("id_item=$idItem");
        }

        $maxProperties = $maxProperties["max_count"];

        foreach ($itemProperties as $property) {
            if (empty($property["id_items_properties"]) && $maxProperties > $countPropertiesDB && !in_array($property["properties_id_items_properties"], $itemPropertiesDB)) {
                $countPropertiesDB++;
                $tempSQL = getInsertSQL(array_merge($property, ["items_id_items_properties" => $idItem]));
                $sql .= "INSERT INTO `items_properties` ($tempSQL[sql]) VALUES ($tempSQL[question]);";
                array_push($params, ...$tempSQL["params"]);
            } else if (!empty($property["id_items_properties"]) && in_array($property["id_items_properties"], $itemPropertiesDB)) {
                $tempSQL = getUpdateSQL(array_diff_key($property, ["id_items_properties" => true]));
                $sql .= "UPDATE `items_properties` SET $tempSQL[sql] WHERE `id_items_properties` = ?;";
                $tempSQL["params"][] = $property["id_items_properties"];
                array_push($params, ...$tempSQL["params"]);
            }
        }

        $result = getUpdateSQL(array_diff_key($validatedData["data"], ["items_properties" => true, "id_items_properties" => true]));
        if ($result["sql"] != "") {
            $sql .= "UPDATE `items` SET $result[sql] WHERE `id_items` = ?;";
            $result["params"][] = $idItem;
        }
        array_push($params, ...$result["params"]);

        if ($sql != "" && $params != []) {
            clearValidatedSession();
            if (makeSafeQuery($sql, $params)) {
                $_SESSION["server"] = "Товар изменен";
            } else {
                $_SESSION["server"] = "Не удалось обновить товар";
            }
        } else {
            $_SESSION["server"] = "Не удалось выполнить запрос";
            redirectYourself("id_item=$idItem");
        }
    } else {
        $_SESSION["server"] = "Не корректные данные";
    }

    redirectYourself("id_item=$idItem");
}

$allProperties = makeSelectQuery("SELECT * FROM `properties`", [], false);

if ($allProperties === "FAIL") {
    redirect();
}

$allPropertiesHTML = "";
foreach ($allProperties as $option) {
    $allPropertiesHTML .= "<option value='$option[id_properties]'>$option[name_properties]</option>";
}

$itemInfo = makeSelectQuery("SELECT
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
", [$idItem], true);

if ($itemInfo === "FAIL" || empty($itemInfo)) {
    redirect();
}

$itemProperties = makeSelectQuery("SELECT
    `id_items_properties`,
    `properties_id_items_properties`,
    `description_items_properties`
    FROM `items_properties`
    JOIN `properties` ON `properties`.`id_properties` = `items_properties`.`properties_id_items_properties`
    WHERE `items_properties`.`items_id_items_properties` = ?
    ", [$idItem], false
);

if ($itemProperties === "FAIL") {
    redirect();
}

$itemPropertiesHTML = "";

foreach ($itemProperties as $key => $property) {
    $startIndexValue = strpos($allPropertiesHTML, "value='$property[properties_id_items_properties]'");
    $selectSelected = substr($allPropertiesHTML, 0, $startIndexValue) . "selected " . substr($allPropertiesHTML, $startIndexValue);
    $no = $key + 1;
    $itemPropertiesHTML .= "<div class='field additional'>
        <h2>№<b>$no</b></h2>
        <div class='field hidden'>
            <label class='label'></label>
            <input class='hidden input' type='text' value='$property[id_items_properties]' data-name='id_items_properties' data-is-insert-server='1'>
            <p class='error'></p>
        </div>
        <div class='field'>
            <label class='label'></label>
            <select class='input' data-name='properties_id_items_properties' data-is-insert-server='1'>
                <option value='' disabled selected>Выбрать</option>
                $selectSelected
            </select>
            <p class='error'></p>
        </div>
        <div class='field'>
            <label class='label'></label>
            <input class='input' type='text' value='$property[description_items_properties]' data-name='description_items_properties' data-is-insert-server='1'>
            <p class='error'></p>
        </div>
        <div class='field' data-id='$property[id_items_properties]'>
            <span class='button'>Удалить</span>
        </div>
    </div>
    ";
}

$types = makeSelectQuery("SELECT * FROM `items_type`", [], false);

if ($types === "FAIL") {
    redirect();
}

$typesHTML = "";
foreach ($types as $type) {
    $selected = $type["id_items_type"] == $itemInfo["items_type_id_items"] ? "selected" : "";
    $typesHTML .= "<option value='$type[id_items_type]' $selected>$type[name_items_type]</option>";
}

echo getAdditionalHTML($allPropertiesHTML, $allProperties, $itemProperties, false);
getModalHTML();

include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action="adminEditItem.php?id_item=<?= $idItem ?>" method="POST" enctype="multipart/form-data" class="form">
        <legend class="legend">Изменение товара</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $itemInfo["name_items"] ?? "" ?>" data-name="name_items" data-is-insert-server="<?= !empty($itemInfo["name_items"]) ? 1 : 0 ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="number" value="<?= $itemInfo["count_items"] ?? "" ?>" data-name="count_items" data-is-insert-server="<?= !empty($itemInfo["count_items"]) ? 1 : 0 ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="number" value="<?= $itemInfo["cost_items"] ?? "" ?>" data-name="cost_items" data-is-insert-server="<?= !empty($itemInfo["cost_items"]) ? 1 : 0 ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $data["description_items"] ?? "" ?>" data-name="description_items" data-is-insert-server="<?= empty($data["description_items"]) ? 1 : 0 ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="file" data-name="image_items" data-is-insert-server="0">
            <img src="<?= getValidImage(FOLDER_UPLOAD . "/" . FOLDER_ITEMS, $itemInfo["image_items"]) ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <select class="input" data-name="items_type_id_items">
                <option value="" disabled selected>Выбрать</option>
                <?= $typesHTML ?>
            </select>
            <p class="error"></p>
        </div>
        <div class="field">
            <label class='label hidden'></label>
            <input type="hidden" class="hidden input" data-name="items_properties">
            <button class="additional button">Добавить дополнительное описание</button>
            <p class="error"></p>
        </div>
        <?= $itemPropertiesHTML ?>
        <div class="field">
            <input type="submit" class="input button" name="submit_button" value="Обновить">
        </div>
        <div class="field">
            <button class='delete button'>Удалить товар</button>
        </div>
    </form>
    <div>
        <a href="aboutItem.php?id_item=<?= $idItem ?>" class="button">Перейти к товару</a>
    </div>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>