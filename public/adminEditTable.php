<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin() || empty($_GET["table"])) {
    redirect();
}

$tableName = in_array($_GET["table"], ["properties", "status", "items_type"]) ? $_GET["table"] : false;
$isAttribute = $tableName == "properties";

if (!$tableName) {
    redirect();
}

if (!empty($_POST["submit_button"]) && count($_POST) > 1 && !empty($_GET["type"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData($_POST);
    $result = [];

    if ($validatedData["isCorrect"]) {
        if ($_GET["type"] == "add") {
            $attributes = $validatedData["data"]["attributes"] ?? [];
            unset($validatedData["data"]["attributes"]);
            $result = getInsertSQL($validatedData["data"]);
            if (makeSafeQuery("INSERT INTO `$tableName` ($result[sql]) VALUES ($result[question])", $result["params"])) {
                $_SESSION["server"] = "Создано";
            } else {
                $_SESSION["server"] = "Не удалось создать";
            }
            if ($isAttribute) {
                $sql = "";
                $params = [];
                foreach ($attributes as $attribute) {
                    $attribute["properties_id_attributes"] = $link->lastInsertId();
                    $result = getInsertSQL(array_diff_key($attribute));
                    $sql .= "INSERT INTO `attributes` ($result[sql]) VALUES ($result[question]);";
                    array_push($params, ...$result["params"]);
                }
                
                if (makeSafeQuery($sql, $params)) {
                    $_SESSION["server"] = "Обновлено";
                } else {
                    $_SESSION["server"] = "Не удалнось создать";
                }
            }

        } else if ($_GET["type"] == "update") {
            if (!empty($validatedData["data"]["id_$tableName"])) {
                $result = getUpdateSQL(array_diff_key($validatedData["data"], ["id_$tableName" => true, "attributes" => true]));
                $result["params"][] = $validatedData["data"]["id_$tableName"];
                if (makeSafeQuery("UPDATE `$tableName` SET $result[sql] WHERE `id_$tableName` = ?", $result["params"])) {
                    $_SESSION["server"] = "Обновлено";
                } else {
                    $_SESSION["server"] = "Не удалось обновить";
                }
            }
            if ($isAttribute) {
                $attributes = $validatedData["data"]["attributes"] ?? [];
                $sql = "";
                $params = [];
                foreach ($attributes as $attribute) {
                    $result = getUpdateSQL(array_diff_key($attribute, ["id_attributes" => true]));
                    $result["params"][] = $attribute["id_attributes"];
                    $sql .= "UPDATE `attributes` SET $result[sql] WHERE `id_attributes` = ?;";
                    array_push($params, ...$result["params"]);
                }
                if (makeSafeQuery($sql, $params)) {
                    $_SESSION["server"] = "Обновлено";
                } else {
                    $_SESSION["server"] = "Не удалось обновить";
                }
            }
        }
    } else {
        $_SESSION["server"] = "Не корректные данные";
    }
    redirectYourself("table=$tableName");
}

$columnNames = makeSelectQuery("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` = ? AND `COLUMN_NAME` NOT LIKE 'id_%'", [$tableName], false);

if ($columnNames === "FAIL") {
    redirect();
}

$formAddHTML = "";
foreach ($columnNames as $column) {
    $formAddHTML .= "<div class='field'>
        <label class='label'></label>
        <input class='input' type='text' data-name='$column[COLUMN_NAME]' data-is-insert-server='1'>
        <p class='error'></p>
    </div>";
    if ($isAttribute) {
        $formAddHTML .= "
            <div class='field hidden'>
                <label class='label hidden'></label>
                <input class='input' data-name='attributes' data-is-insert-server='1'>
                <p class='error'></p>
            </div>
            <div class='field'>
                <label class='label'></label>
                <input class='input' data-name='value_attributes' data-is-insert-server='1'>
                <p class='error'></p>
            </div>
            <div class='field'>
                <button class='button add-one'>Добавить ещё</button>
            </div>
        ";
    }
}

$table = makeSelectQuery("SELECT * FROM `$tableName` ORDER BY `id_$tableName` DESC", [], false);

if ($table === "FAIL") {
    redirect();
}


$attributesProperties = [];
if ($isAttribute) {
    $attributesProperties = makeSelectQuery("SELECT * FROM `attributes` ORDER BY `id_attributes`", [], false);
}

$formEditHTML = "";
$countEditForms = 0;
foreach ($table as $row) {
    $countEditForms++;
    $id = "id_$tableName";
    $formEditHTML .= "<form action='adminEditTable.php?table=$tableName&type=update' method='POST' class='form'>
        <legend class='legend'>Изменение</legend>
    ";

    foreach ($row as $key => $column) {
        $typeInput = preg_match("/^id_/", $key) ? "hidden" : "text";
        $classField = $typeInput == "hidden" ? "hidden" : "";
        $formEditHTML .= "<div class='field $classField'>
            <label class='label'></label>
            <input class='input' type='$typeInput' value='$column' data-name='$key' data-is-insert-server='1'>
            <p class='error'></p>
        </div>";
    };

    if ($isAttribute) {
        $formEditHTML .= "<div class='field hidden'>
                <label class='label'></label>
                <input class='input' data-name='attributes' data-is-insert-server='1'>
                <p class='error'></p>
            </div>
        ";
        foreach ($attributesProperties as $attribute) {
            if ($attribute["properties_id_attributes"] == $row["id_properties"]) {
                $formEditHTML .= "
                    <div class='field additional'>
                        <div class='field hidden'>
                            <label class='label'></label>
                            <input class='input' data-name='id_attributes' data-is-insert-server='1' value='$attribute[id_attributes]'>
                            <p class='error'></p>
                        </div>
                        <div class='field'>
                            <label class='label'></label>
                            <input class='input' data-name='value_attributes' data-is-insert-server='1' value='$attribute[value_attributes]'>
                            <p class='error'></p>
                        </div>
                        <div class='field'>
                            <button class='button delete-one' data-id-attributes='$attribute[id_attributes]'>Удалить это свойство</button>
                        </div>
                    </div>
                ";
            }
        }
        $formEditHTML .= "<div class='field'>
            <button class='button add-one'>Добавить ещё свойство</button>
        </div>";
    }

    $formEditHTML .= "<div class='field'>
            <input class='input button' type='submit' name='submit_button' value='Изменить'>
        </div>
        <div class='field'>
            <button class='button delete-all' data-id='$row[$id]' data-table='$tableName'>Удалить всё</button>
        </div>
    </form>";
}

getModalHTML();
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action='adminEditTable.php?table=<?= $tableName ?>&type=add' method='POST' class='form'>
        <legend class="legend">Добавление</legend>
        <?= $formAddHTML  ?>
        <div class="field">
            <input class="input button" type="submit" name="submit_button" value="Создать">
        </div>
    </form>
    <?= $formEditHTML ?>
</main>

<template id="add-one-template">
    <div class="field additional">
        <div class="field hidden">
            <label class="label"></label>
            <input class="input" data-name="id_attributes" data-is-insert-server="1">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <input class="input" data-name="value_attributes" data-is-insert-server="1">
            <p class="error"></p>
        </div>
        <div class="field">
            <button class="button delete-one">Удалить это свойство</button>
        </div>
    </div>
</template>

<?php include_once __DIR__ . "/footer.php"; ?>