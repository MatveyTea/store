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
        $maxProperties = makeSelectQuery("SELECT COUNT(*) as `max_count` FROM `properties`", [], true);
        
        if ($maxProperties === "FAIL") {
            $_SESSION["server"] = "Не удалось выполнить запрос";
            redirectYourself();
        }

        $maxProperties = $maxProperties["max_count"];

        foreach ($itemProperties as $property) {
            $tempSQL = getInsertSQL(array_merge($property, ["items_id_items_properties" => $id]));
            $sql .= "INSERT INTO `items_properties` ($tempSQL[sql]) VALUES ($tempSQL[question]);";
            array_push($params, ...$tempSQL["params"]);
        }

        if ($sql != "" && $params != [] && makeSafeQuery($sql, $params) || $isSucceedItem) {
            clearValidatedSession();
            $_SESSION["server"] = "Товар добавлен";
        } else {
            $_SESSION["server"] = "Не удалось добавить товар";
        }
    } else {
        $_SESSION["server"] = "Не удалось добавить товар";
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

$itemProperties = $_SESSION["data"]["items_properties"] ?? [];
$itemPropertiesHTML = "";
foreach ($itemProperties as $key => $property) {
    $selectSelected = substr($allPropertiesHTML, 0, strpos($allPropertiesHTML, "value='$property[properties_id_items_properties]'")) . "selected " . substr($allPropertiesHTML, strpos($allPropertiesHTML, "value='$property[properties_id_items_properties]'"));
    $no = $key + 1;
    $itemPropertiesHTML .= "<div class='field additional'>
        <h2>№<b>$no</b></h2>
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
            <input class='input'type='text' value='$property[description_items_properties]' data-name='description_items_properties'  data-is-insert-server='1'>
            <p class='error'></p>
        </div>
        <div class='field'>
            <span class='button'>Удалить</span>
        </div>
    </div>
    ";
}

$types = $link->query("SELECT * FROM `items_type`")->fetchAll(PDO::FETCH_ASSOC);
$typesHTML = "";
foreach ($types as $type) {
    $selected = $type["id_items_type"] == ($_SESSION["data"]["items_type_id_items"] ?? "") ? "selected" : "";
    $typesHTML .= "<option value='$type[id_items_type]' $selected>$type[name_items_type]</option>";
}

$data = $_SESSION["data"] ?? [];

echo getAdditionalHTML($allPropertiesHTML, $allProperties, $itemProperties, false);
getModalHTML();

include_once __DIR__ . "/header.php";
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
            <button class="additional button">Добавить дополнительное описание</button>
        </div>
        <?= $itemPropertiesHTML ?>
        <div class="field">
            <input type="submit" class="input button" name="submit_button" value="Добавить">
        </div>
    </form>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>