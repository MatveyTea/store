<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || !isAdmin() || empty($_GET["table"]) && empty($_POST["table"])) {
    redirect();
}

$_GET["table"] ??= $_POST["table"];

if (!empty($_POST["submit_button"])) {
    unset($_POST["submit_button"]);
    $validatedData = getValidatedData($_POST);
    $_SESSION["data"] = $validatedData["data"];

    if ($validatedData["isCorrect"]) {
        try {
            $result = getUpdateSQL(array_diff($validatedData["data"], [$_POST["table"], $_POST["id_$_GET[table]"]]));
            $link->prepare("UPDATE `$_GET[table]` SET $result[sql] WHERE `id_$_GET[table]` = ?")->execute([...$result["params"], $_POST["id_$_GET[table]"]]);
        }
        catch(Throwable $e) {
            $_SESSION["errorField"]["id_$_GET[table]"] = "id_$_GET[table]";
            $_SESSION["errorField"]["server"] = $e->getMessage();
        }
    }

    // redirect("adminTable.php?table=$_GET[table]");
}

$stmt = $link->prepare("SELECT `TABLE_NAME` FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_NAME` = ?");
$stmt->execute([$_GET["table"]]);

$tableName = $stmt->fetch(PDO::FETCH_ASSOC)["TABLE_NAME"];

if (!$tableName) {
    redirect();
}

$table = $link->query("SELECT * FROM `$tableName`")->fetchAll(PDO::FETCH_ASSOC);
$html = "";

foreach ($table as $row) {
    $html .= "<form action='adminTable.php?table=$_GET[table]' method='POST' class='form'>
        <div class='field hidden'>
            <label class='label' for='$_GET[table]'></label>
            <input type='hidden' name='table' value='$_GET[table]'>
            <p class='error'></p>
        </div>
    ";

    $error = "";

    foreach ($row as $key => $column) {

        if (preg_match("/^id_/", $key)) {
            $html .= "<div class='field hidden'>
                <label class='label' for='$key'>$key</label>
                <input type='hidden' name='$key' value='$column'>
                <p class='error'></p>
            </div>";
            if (key_exists($key, $_SESSION["errorField"] ?? [])) {
                $error =  $_SESSION["errorField"]["server"];
            }
        } else {
            $html .= "<div class='field'>
                <label class='label' for='$key'>$key</label>
                <input class='input' type='text' placeholder='' name='$key' value='$column'>
                <p class='error'></p>
            </div>";
        }
    }

    $html .= "<div class='field'>
            <p class='error server-error'>$error</p>
            <input class='input button' type='submit' name='submit_button' value='Изменить' class='button'>
        </div>
    </form>";
}

include_once __DIR__ . "/header.php";
?>
<main class="content">
    <?= $html ?>
</main>

<?php include_once __DIR__ . "/footer.php"; clearValidatedSession() ?>