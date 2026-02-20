<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin() || empty($_GET["table"])) {
    redirect();
}

$tableName = in_array($_GET["table"], ["properties", "status", "items_type"]) ? $_GET["table"] : false;

if (!$tableName) {
    redirect();
}

if (!empty($_POST["submit_button"]) && count($_POST) > 1 && !empty($_GET["type"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData($_POST);

    if ($validatedData["isCorrect"]) {
        if ($_GET["type"] == "add") {
            $result = getInsertSQL($validatedData["data"]);
            if (makeSafeQuery("INSERT INTO `$tableName` ($result[sql]) VALUES ($result[question])", $result["params"])) {
                $_SESSION["server"] = "Создано";
            } else {
                $_SESSION["server"] = "Не удалось создать";
            }
        } else if ($_GET["type"] == "update") {
            $result = getUpdateSQL(array_diff_key($validatedData["data"], ["id_$tableName" => true]));
            $result["params"][] = $validatedData["data"]["id_$tableName"];
            if (makeSafeQuery("UPDATE `$tableName` SET $result[sql] WHERE `id_$tableName` = ?", $result["params"])) {
                $_SESSION["server"] = "Обновлено";
            } else {
                $_SESSION["server"] = "Не удалось обновить";
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
}

$table = makeSelectQuery("SELECT * FROM `$tableName`", [], false);

if ($table === "FAIL") {
    redirect();
}

$formEditHTML = "";

$countEditForms = 0;
foreach ($table as $row) {
    $countEditForms++;
    $id = "id_$tableName";
    $formEditHTML .= "<form action='adminEditTable.php?table=$tableName&type=update' method='POST' class='form'>
        <legend class='legend'>Изменение №$countEditForms</legend>
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

    $formEditHTML .= "<div class='field'>
            <input class='input button' type='submit' name='submit_button' value='Изменить'>
        </div>
        <div class='field'>
            <button class='button' data-id='$row[$id]' data-table='$tableName'>Удалить</button>
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

<?php include_once __DIR__ . "/footer.php"; ?>