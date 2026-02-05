<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isUserAuth() || !isAdmin() || empty($_GET["table"])) {
    redirect();
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
    $html .= "<form action='adminTable.php' method='POST' class='form'>";
    foreach ($row as $key => $column) {

        if (preg_match("/^id_/", $key)) {
            $html .= "<div class='field hidden'>
                <label class='label' for='$key'>$key</label>
                <input type='hidden' name='$key' value='$column'>
                <p class='error'></p>
            </div>";
        } else {
            $html .= "<div class='field'>
                <label class='label' for='$key'>$key</label>
                <input class='input' type='text' placeholder='' name='$key' value='$column'>
                <p class='error'></p>
            </div>";
        }
    }
    $html .= "<div class='field'>
            <p class='error server-error'>" . ($_SESSION["errorField"]["server"] ?? "") . "</p>
            <input class='input button' type='submit' id='submit_button' name='submit_button' value='Изменить' class='button'>
        </div>
    </form>";
}



include_once __DIR__ . "/header.php";
?>
<main class="content">
    <?= $html ?>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>