<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin() || empty($_GET["id_item"])) {
    redirect();
}

$idItem = $_GET["id_item"];

if (!empty($_POST["submit_button"]) && count($_POST) > 1) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES));
    $_SESSION["data"] = $validatedData["data"];

    if ($validatedData["isCorrect"]) {
        $sql = "";
        $params = [];

        $itemProperties = $validatedData["data"]["items_properties"] ?? [];
        $itemPropertiesDB = makeSelectQuery(
            "SELECT
            `attributes_id_items_properties`
            FROM `items_properties`
            JOIN `attributes` ON `id_attributes` = `attributes_id_items_properties`
            WHERE `items_id_items_properties` = ?
            ",
            [$idItem],
            false
        );
        if ($itemPropertiesDB == "FAIL") {
            $_SESSION["server"] = "Не удалось выполнить запрос";
            redirectYourself("id_item=$idItem");
        }

        foreach ($itemProperties as $property) {
            if ($property["type"] == "remove") {
                $sql .= "DELETE FROM `items_properties` WHERE `attributes_id_items_properties` = ? AND `items_id_items_properties` = ?;";
                array_push($params, $property["id_attributes"], $idItem);
            } else if ($property["type"] == "add") {
                $canInsert = true;
                foreach ($itemPropertiesDB as $propertyDB) {
                    if ($propertyDB["attributes_id_items_properties"] == $property["id_attributes"]) {
                        $canInsert = false;
                    }
                }
                if ($canInsert) {
                    $sql .= "INSERT INTO `items_properties` (`items_id_items_properties`, `attributes_id_items_properties`) VALUES (?, ?);";
                    array_push($params, $idItem, $property["id_attributes"]);
                }
            }
        }

        $result = getUpdateSQL(array_diff_key($validatedData["data"], ["items_properties" => true]));
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
        }
    } else {
        $_SESSION["server"] = "Не корректные данные";
    }

    redirectYourself("id_item=$idItem");
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

$allProperties = makeSelectQuery("SELECT * FROM `properties`", [], false);
if ($allProperties === "FAIL") {
    redirect();
}
$allPropertiesHTML = "";
foreach ($allProperties as $option) {
    $allPropertiesHTML .= "<option value='$option[id_properties]'>$option[name_properties]</option>";
}

$allAttributes = makeSelectQuery("SELECT * FROM `attributes`", [], false);
if ($allAttributes == "FAIL") {
    redirect();
}
$allAttributesHTML = "";
foreach ($allAttributes as $attribute) {
    $allAttributesHTML .= "<label class='hidden'>$attribute[value_attributes]<input class='input' type='checkbox' value='$attribute[id_attributes]' data-name='attributes_select_value' data-is-insert-server='1'></label>";
}

$dataValue = [];
$attributesHTML = "";
$propertyID = null;
$attributesItem = makeSelectQuery(
    "SELECT 
    `attributes`.`id_attributes`,
    `attributes`.`value_attributes`,
    `properties`.`id_properties`,
    `attributes_id_items_properties`,
    `properties`.`name_properties`
    FROM `items_properties`
    LEFT JOIN `attributes` ON `id_attributes` = `attributes_id_items_properties`
    LEFT JOIN `properties` ON `properties_id_attributes` = `id_properties`
    WHERE `items_id_items_properties` = ?
    ORDER BY `properties_id_attributes`
    ",
    [$idItem],
    false
);
if ($attributesItem === "FAIL") {
    redirect();
}

if (!empty($attributesItem)) {
    foreach ($attributesItem as $key => $attribute) {
        if ($key == 0) {
            $dataValue[] = $attribute["id_attributes"];
            continue;
        }
        if ($propertyID != $attribute["id_properties"] && $propertyID != null) {
            $attributesHTML .= getAdditionalSelectHTML($allPropertiesHTML, $allAttributesHTML,$attributesItem[$key - 1]["id_properties"], $dataValue);
            $dataValue = [];
        }
        $propertyID = $attribute["id_properties"];
        $dataValue[] = $attribute["id_attributes"];
    }
    $attributesHTML .= getAdditionalSelectHTML($allPropertiesHTML, $allAttributesHTML,$attributesItem[count($attributesItem) - 1]["id_properties"], $dataValue);
}

$attributes = makeSelectQuery(
    "SELECT 
    `attributes`.`id_attributes`,
    `attributes`.`value_attributes`,
    `properties`.`id_properties`,
    `properties`.`name_properties`
    FROM `attributes`
    JOIN `properties` ON `properties`.`id_properties` = `attributes`.`properties_id_attributes`
    ORDER BY `properties`.`id_properties`
    ",
    [],
    false
);
if ($attributes == "FAIL") redirect();
$dependencies = [];
foreach ($attributes as $attribute) {
    $dependencies[$attribute["id_properties"]][] = $attribute["id_attributes"];
}
$dependencies = json_encode($dependencies);

$types = makeSelectQuery("SELECT * FROM `items_type`", [], false);
if ($types === "FAIL") {
    redirect();
}
$typesHTML = "";
foreach ($types as $type) {
    $selected = $type["id_items_type"] == $itemInfo["items_type_id_items"] ? "selected" : "";
    $typesHTML .= "<option value='$type[id_items_type]' $selected>$type[name_items_type]</option>";
}

include_once __DIR__ . "/header.php";
getAdditionalTemplateHTML($allPropertiesHTML, $allAttributesHTML, $idItem);
getModalHTML();
echo "<template id='dependencies'>$dependencies</template>";
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
            <input class="input" type="file" data-name="image_items" data-is-insert-server="1">
            <img src="<?= getValidImage(FOLDER_UPLOAD . "/" . FOLDER_ITEMS, $itemInfo["image_items"]) ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <select class="input" data-name="items_type_id_items" data-is-insert-server="1">
                <option value="" disabled selected>Выбрать</option>
                <?= $typesHTML ?>
            </select>
            <p class="error"></p>
        </div>
        <div class="field">
            <input type="hidden" class="hidden input" data-name="items_properties" data-is-insert-server="1">
            <button class="additional button">Добавить свойства</button>
            <p class="error"></p>
        </div>
        <?= $attributesHTML ?>
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