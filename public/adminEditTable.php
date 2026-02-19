<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin() || empty($_GET["table"])) {
    redirect();
}

$tableName = makeSelectQuery("SELECT `TABLE_NAME` FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_NAME` = ?", [$_GET["table"]], true)["TABLE_NAME"];

if (!$tableName) {
    redirect();
}

if (!empty($_POST["submit_button"]) && count($_POST) > 1 && !empty($_GET["type"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData($_POST);

    if ($validatedData["isCorrect"]) {
        if ($_GET["type"] == "add") {
            $result = getInsertSQL(array_diff_key($validatedData["data"], ["id_$tableName" => true]));
            if (makeInsertQuery("INSERT INTO `$tableName` ($result[sql]) VALUES ($result[question])", $result["params"])) {
                $_SESSION["errorField"]["server"] = "Создано";
            } else {
                $_SESSION["errorField"]["server"] = "Не удалось создать";
            }
        } else if ($_GET["type"] == "update") {
            $result = getUpdateSQL(array_diff_key($validatedData["data"], ["id_$tableName" => true]));
            $result["params"][] = $validatedData["data"]["id_$tableName"];

            if (makeUpdateQuery("UPDATE `$tableName` SET $result[sql] WHERE `id_$tableName` = ?", $result["params"])) {
                $_SESSION["errorField"]["server"] = "Обновлено";
            } else {
                $_SESSION["errorField"]["server"] = "Не удалось обновить";
            }
        }
    }
    redirectYourself("table=$tableName");
}

$table = makeSelectQuery("SELECT * FROM `$tableName`", [], false);
$formEditHTML = "";
$formAddHTML = "";

foreach ($table as $key => $row) {
    $id = "id_$tableName";
    $formEditHTML .= "<form action='adminEditTable.php?table=$tableName&type=update' method='POST' class='form'>
        <legend class='legend'>Изменение №$key</legend>
    ";
    foreach ($row as $key => $column) {
        $typeInput = preg_match("/^id_/", $key) ? "hidden" : "text";
        $classField = $typeInput == "hidden" ? "hidden" : "";
        $formEditHTML .= "<div class='field $classField'>
            <label class='label'></label>
            <input class='input' type='$typeInput' value='$column' data-name='$key' data-is-insert-server='1'>
            <p class='error'></p>
        </div>";
    }
    $formEditHTML .= "<div class='field'>
            <input class='input button' type='submit' name='submit_button' value='Изменить'>
        </div>
        <div class='field'>
            <button class='button' data-id='$row[$id]' data-table='$tableName'>Удалить</button>
        </div>
    </form>";

    if (!str_starts_with($key, "id") && $row == $table[0]) {
        $formAddHTML .= "<div class='field'>
            <label class='label'></label>
            <input class='input' type='text' data-name='$key' data-is-insert-server='1'>
            <p class='error'></p>
        </div>";
    };
}

include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action='adminEditTable.php?table=<?= $tableName ?>&type=add' method='POST' class='form'>
        <legend class="legend">Добавление</legend>
        <?= $formAddHTML  ?>
        <div class="field">
            <p class="error server-error"><?= $_SESSION["errorField"]["server"]["add"] ?? "" ?></p>
            <input class="input button" type="submit" name="submit_button" value="Создать">
        </div>
    </form>
    <?= $formEditHTML ?>
</main>

<?php getModalHTML(!empty($_SESSION["errorField"]["server"]) ? 1 : 0); clearValidatedSession(); include_once __DIR__ . "/footer.php"; ?>