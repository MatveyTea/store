<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isAdmin()) {
    redirect();
}

if (!empty($_POST["submit_button"]) && count($_POST) > 1) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData(array_merge($_POST, $_FILES));
    $_SESSION["data"] = $validatedData["data"];

    if ($validatedData["isCorrect"]) {
        $tempSQL = getInsertSQL(array_merge(array_diff_key($validatedData["data"], ["items_properties" => true, "image_items_images" => true]), ["date_add_items" => date("y-m-d")]));
        $isSucceedItem = makeSafeQuery("INSERT INTO `items` ($tempSQL[sql]) VALUES ($tempSQL[question])", $tempSQL["params"]);
        $id = $link->lastInsertId();

        $sql = "";
        $params = [];
        $images = $validatedData["data"]["image_items_images"] ?? [];
        foreach ($images as $image) {
            $sql .= "INSERT INTO `items_images` (`items_id_items_images`, `image_items_images`) VALUES (?, ?);";
            array_push($params, $id, $image);
        }

        $isSucceedImages = true;
        if ($sql != "" && $params != []) {
            $isSucceedImages = makeSafeQuery($sql, $params);
        }

        $sql = "";
        $params = [];
        $itemProperties = $validatedData["data"]["items_properties"] ?? [];
        foreach ($itemProperties as $property) {
            $sql .= "INSERT INTO `items_properties` (`items_id_items_properties`, `attributes_id_items_properties`) VALUES (?, ?);";
            array_push($params, $id, $property["id_attributes"]);
        }

        $isSucceedProperties = true;
        if ($sql != "" && $params != []) {
            $isSucceedProperties = makeSafeQuery($sql, $params);
        }

        if ($isSucceedItem && $isSucceedImages && $isSucceedProperties) {
            clearValidatedSession();
            $_SESSION["server"] = "Товар добавлен";
        } else {
            $_SESSION["server"] = "Не удалось добавить товар";
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
    $allAttributesHTML .= "<label class='hidden'>
        $attribute[value_attributes]
        <input class='input' type='checkbox' value='$attribute[id_attributes]' data-name='attributes_select_value'>
    </label>";
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
            $allAttributesHTMLSome = "";
            foreach ($allAttributes as $attributeSome) {
                echo  "$attributeSome[id_attributes] | $attribute[id_attributes]";
                $isInsertServer = $attributeSome["id_attributes"] == $attribute["id_attributes"] ? 0 : 1;
                $allAttributesHTMLSome .= "<label class='hidden'>$attributeSome[value_attributes]<input class='input' type='checkbox' value='$attributeSome[id_attributes]' data-name='attributes_select_value' data-is-insert-server='$isInsertServer'></label>";
            }
            $attributesItemHTML .= getAdditionalSelectHTML(0, $allPropertiesHTML, $allAttributesHTMLSome, $attributesItem[$key - 1]["id_properties"], $dataValue);
            $dataValue = [];
        }
        $propertyID = $attribute["id_properties"];
        $dataValue[] = $attribute["id_attributes"];
    }
    $allAttributesHTMLSome = "";
    foreach ($allAttributes as $attributeSome) {
        $isInsertServer = $attributeSome["id_attributes"] == $attributesItem[count($attributesItem) - 1]["id_attributes"] ? 0 : 1;
        $allAttributesHTMLSome .= "<label class='hidden'>$attributeSome[value_attributes]<input class='input' type='checkbox' value='$attributeSome[id_attributes]' data-name='attributes_select_value' data-is-insert-server='$isInsertServer'></label>";
    }
    $attributesItemHTML .= getAdditionalSelectHTML(0, $allPropertiesHTML, $allAttributesHTMLSome, $attributesItem[count($attributesItem) - 1]["id_properties"], $dataValue);
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


$types = $link->query("SELECT * FROM `items_type`")->fetchAll(PDO::FETCH_ASSOC);
$typesHTML = "";
foreach ($types as $type) {
    $selected = $type["id_items_type"] == ($_SESSION["data"]["items_type_id_items"] ?? "") ? "selected" : "";
    $typesHTML .= "<option value='$type[id_items_type]' $selected>$type[name_items_type]</option>";
}

$data = $_SESSION["data"] ?? [];

include_once __DIR__ . "/../../app/server/header.php";
getAdditionalTemplateHTML($allPropertiesHTML, $allAttributesHTML, $attributes);
getModalHTML();
?>

<main class="content">
    <form action="/admin/addItem.php" method="POST" class="form" enctype="multipart/form-data">
        <legend class="legend">Добавление товара</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="text" value="<?= $data["name_items"] ?? "" ?>" data-name="name_items" data-is-insert-server="<?= empty($data["name_items"]) ? 1 : 0 ?>">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <textarea class="input" type="text" value="<?= $data["description_items"] ?? "" ?>" data-name="description_items" data-is-insert-server="<?= empty($data["description_items"]) ? 1 : 0 ?>"></textarea>
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="number" value="<?= $data["count_items"] ?? "" ?>" data-name="count_items" data-is-insert-server="<?= empty($data["count_items"]) ? 1 : 0 ?>">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="number" value="<?= $data["cost_items"] ?? "" ?>" data-name="cost_items" data-is-insert-server="<?= empty($data["cost_items"]) ? 1 : 0 ?>">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <select class="input" data-name="items_type_id_items" data-is-insert-server="<?= empty($data["name_items"]) ? 1 : 0 ?>">
                <option value="" disabled selected>Выбрать</option>
                <?= $typesHTML ?>
            </select>
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
            <div class="field">
            <label class="label"></label>
            <input class="input" type="number" value="<?= $data["discount_items"] ?? "" ?>" data-name="discount_items" data-is-insert-server="<?= empty($data["discount_items"]) ? 1 : 0 ?>">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="file" data-name="image_items_images" multiple="true">
            <?= getSliderImagesItemHTML([], false) ?>
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field hidden">
            <input type="hidden" class="hidden input" data-name="items_properties">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field-properties">
            <?= $attributesItemHTML ?>
        </div>
        <div class="field">
            <button class="add-properties button">Добавить свойство</button>
            <input type="submit" class="button" name="submit_button" value="Добавить">
        </div>
    </form>
</main>

<template class="template-image">
    <?= getImagesItemHTML(isAdminFile: true); ?>
</template>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>