<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin()) {
    redirect();
}

if (!empty($_POST["submit_button"]) && count($_POST) > 1) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES));
    $_SESSION["data"] = $validatedData["data"];

    if ($validatedData["isCorrect"]) {
        $tempSQL = getInsertSQL(array_merge(array_diff_key($validatedData["data"], ["items_properties" => true]), ["date_add_items" => date("y-m-d"), "views_items" => 0]));
        $isSucceedItem = makeSafeQuery("INSERT INTO `items` ($tempSQL[sql]) VALUES ($tempSQL[question])", $tempSQL["params"]);
        $id = $link->lastInsertId();

        $sql = "";
        $params = [];

        $itemProperties = $validatedData["data"]["items_properties"] ?? [];
        foreach ($itemProperties as $property) {
            $sql .= "INSERT INTO `items_properties` (`items_id_items_properties`, `attributes_id_items_properties`) VALUES (?, ?);";
            array_push($params, $id, $property["id_attributes"]);
        }

        if ($sql != "" && $params != [] && makeSafeQuery($sql, $params) || $isSucceedItem) {
            clearValidatedSession();
            $_SESSION["server"] = "Товар добавлен";
        } else {
            $_SESSION["server"] = "Не удалось добавить товар";
            $_SESSION["data"]["item_properties"] = $itemProperties;
        }
    } else {
        $_SESSION["server"] = "Не удалось добавить товар";
        $_SESSION["data"]["item_properties"] = $validatedData["data"]["items_properties"] ?? [];
    }

    redirectYourself();
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
$propertyID = null;
$attributesItem = $_SESSION["data"]["item_properties"] ?? [];
$attributesItemHTML = "";
if (!empty($attributesItem)) {
    foreach ($attributesItem as $key => $attribute) {
        if ($key == 0) {
            $dataValue[] = $attribute["id_attributes"];
            continue;
        }
        if ($propertyID != $attribute["id_properties"] && $propertyID != null) {
            $attributesItemHTML .= getAdditionalSelectHTML($allPropertiesHTML, $allAttributesHTML,$attributesItem[$key - 1]["id_properties"], $dataValue);
            $dataValue = [];
        }
        $propertyID = $attribute["id_properties"];
        $dataValue[] = $attribute["id_attributes"];
    }
    $attributesItemHTML .= getAdditionalSelectHTML($allPropertiesHTML, $allAttributesHTML,$attributesItem[count($attributesItem) - 1]["id_properties"], $dataValue);
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

$types = $link->query("SELECT * FROM `items_type`")->fetchAll(PDO::FETCH_ASSOC);
$typesHTML = "";
foreach ($types as $type) {
    $selected = $type["id_items_type"] == ($_SESSION["data"]["items_type_id_items"] ?? "") ? "selected" : "";
    $typesHTML .= "<option value='$type[id_items_type]' $selected>$type[name_items_type]</option>";
}

$data = $_SESSION["data"] ?? [];

include_once __DIR__ . "/header.php";
getAdditionalTemplateHTML($allPropertiesHTML, $allAttributesHTML);
getModalHTML();
echo "<template id='dependencies'>$dependencies</template>";
?>

<main class="content">
    <form action="adminAddItem.php" method="POST" class="form" enctype="multipart/form-data">
        <legend class="legend">Добавление товара</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $data["name_items"] ?? "" ?>" data-name="name_items" data-is-insert-server="<?= empty($data["name_items"]) ? 1 : 0 ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="number" value="<?= $data["count_items"] ?? "" ?>" data-name="count_items" data-is-insert-server="<?= empty($data["count_items"]) ? 1 : 0 ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="number" value="<?= $data["cost_items"] ?? "" ?>" data-name="cost_items" data-is-insert-server="<?= empty($data["cost_items"]) ? 1 : 0 ?>">
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
            <img src="<?= getValidImage(FOLDER_UPLOAD . "/" . FOLDER_ITEMS, "") ?>">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <select class="input" data-name="items_type_id_items" data-is-insert-server="<?= empty($data["name_items"]) ? 1 : 0 ?>">
                <option value="" disabled selected>Выбрать</option>
                <?= $typesHTML ?>
            </select>
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label hidden"></label>
            <input type="hidden" class="hidden input" data-name="items_properties" data-is-insert-server="0">
            <p class="error"></p>
            <button class="additional button">Добавить свойства</button>
        </div>
        <?= $attributesItemHTML ?>
        <div class="field">
            <input type="submit" class="input button" name="submit_button" value="Добавить">
        </div>
    </form>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>