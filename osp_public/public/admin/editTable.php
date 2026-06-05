<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isAdmin() || empty($_GET["table"])) {
    redirect();
}

$tableName = in_array($_GET["table"], ["properties", "items_type"]) ? $_GET["table"] : false;
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
            if ($isAttribute && !empty($attributes)) {
                $sql = "";
                $params = [];
                foreach ($attributes as $attribute) {
                    $attribute["properties_id_attributes"] = $link->lastInsertId();
                    $result = getInsertSQL(array_diff_key($attribute));
                    $sql .= "INSERT INTO `attributes` ($result[sql]) VALUES ($result[question]);";
                    array_push($params, ...$result["params"]);
                }
                if (makeSafeQuery($sql, $params)) {
                    $_SESSION["server"] = "Создано";
                } else {
                    $_SESSION["server"] = "Не удалось создать";
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
                    if (empty($attribute["id_attributes"])) {
                        $result = getInsertSQL($attribute);
                        $sql .= "INSERT INTO `attributes` ($result[sql]) VALUES ($result[question]);";
                        array_push($params, ...$result["params"]);
                    } else {
                        $result = getUpdateSQL(array_diff_key($attribute, ["id_attributes" => true, "properties_id_attributes" => true]));
                        $result["params"][] = $attribute["id_attributes"];
                        $sql .= "UPDATE `attributes` SET $result[sql] WHERE `id_attributes` = ?;";
                        array_push($params, ...$result["params"]);
                    }
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
    //redirectYourself("table=$tableName");
}

$columnNames = makeSelectQuery("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` = ? AND `COLUMN_NAME` NOT LIKE 'id_%'", [$tableName], false);

if ($columnNames === "FAIL") {
    redirect();
}

$formAddHTML = "";
$tempFormAddButtonNew = "";
foreach ($columnNames as $column) {
    $formAddHTML .= "<div class='field full'>
        <label class='label'></label>
        <input class='input' type='text' data-name='$column[COLUMN_NAME]' data-is-insert-server='1'>
        <span class='error-wrapper'>
            <p class='error'></p>
        </span>
    </div>";
    if ($isAttribute) {
        $formAddHTML .= "
            <div class='field hidden'>
                <label class='label hidden'></label>
                <input class='input' data-name='attributes' data-is-insert-server='1'>
                <span class='error-wrapper'>
                    <p class='error'></p>
                </span>
            </div>
        ";
        $tempFormAddButtonNew .= "<button class='button add-one'>Добавить значение</button>";
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
    $formEditHTML .= "<form action='/admin/editTable.php?table=$tableName&type=update' method='POST' class='form'>
        <legend class='legend'>Изменение</legend>
    ";

    foreach ($row as $key => $column) {
        $typeInput = preg_match("/^id_/", $key) ? "hidden" : "text";
        $classField = $typeInput == "hidden" ? "hidden" : "";
        $formEditHTML .= "<div class='field full $classField'>
            <label class='label'></label>
            <input class='input' type='$typeInput' value='$column' data-name='$key'>
            <span class='error-wrapper'>
                <p class='error'></p>
            </span>
        </div>";
    };

    $tempButtonHTML = "";
    if ($isAttribute) {
        $formEditHTML .= "<div class='field hidden'>
                <label class='label'></label>
                <input class='input' data-name='attributes'>
                <span class='error-wrapper'>
                    <p class='error'></p>
                </span>
            </div>
        ";
        foreach ($attributesProperties as $attribute) {
            if ($attribute["properties_id_attributes"] == $row["id_properties"]) {
                $formEditHTML .= "
                    <div class='field property'>
                        <div class='field'>
                            <label class='label'></label>
                            <input class='input' data-name='value_attributes' data-id-attributes='$attribute[id_attributes]' value='$attribute[value_attributes]'>
                            <span class='error-wrapper'>
                                <p class='error'></p>
                            </span>
                        </div>
                        <div class='field'>
                            <button class='button delete-one' data-id-attributes='$attribute[id_attributes]'>Удалить это значение</button>
                        </div>
                    </div>
                ";
            }
        }
        $tempButtonHTML .= "<button class='button add-one'>Добавить ещё значение</button>";
    }

    $formEditHTML .= "<div class='field'>
            $tempButtonHTML
            <button class='button delete-all' data-id='$row[$id]' data-table='$tableName'>Удалить всё</button>
            <input class='button' type='submit' name='submit_button' value='Изменить'>
        </div>
    </form>";
}

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <form action='/admin/editTable.php?table=<?= $tableName ?>&type=add' method='POST' class='form'>
        <legend class="legend">Добавление</legend>
        <?= $formAddHTML  ?>
        <div class="field">
            <?= $tempFormAddButtonNew ?>
            <input class="button" type="submit" name="submit_button" value="Создать">
        </div>
    </form>
    <?= $formEditHTML ?>
</main>

<template id="add-one-template">
    <div class="field property">
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

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>