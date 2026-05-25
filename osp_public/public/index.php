<?php
include_once __DIR__ . "/../app/server/function.php";

$typesHTML = "";
$types = makeSelectQuery("SELECT * FROM `items_type`", [], false);
if ($types == "FAIL") {
    redirect();
}
foreach ($types as $type) {
    $typesHTML .= "<label>$type[name_items_type]<input class='input' type='checkbox' data-name='items_type_id_search_items' value='$type[id_items_type]'></label>";
}

$attributesHTML = "";
$attributes = makeSelectQuery("SELECT 
    `attributes`.`id_attributes`,
    `attributes`.`value_attributes`,
    `properties`.`id_properties`,
    `properties`.`name_properties`
    FROM `attributes`
    JOIN `properties` ON `properties`.`id_properties` = `attributes`.`properties_id_attributes`
    ORDER BY `properties`.`id_properties`
    ", [], false
);
if ($attributes == "FAIL") {
    redirect();
}

$currentAttributeID = null;
foreach  ($attributes as $attribute) {
    if ($currentAttributeID != $attribute["id_properties"]) {
        if ($currentAttributeID != null) {
            $attributesHTML .= "</div>";
        }
        $attributesHTML .= "<div class='field'><p>$attribute[name_properties]</p>";
        $currentAttributeID = $attribute["id_properties"];
    }
    $attributesHTML .= "<label>$attribute[value_attributes]<input class='input' type='checkbox' value='$attribute[id_attributes]' data-name='attributes_search'></label>";
}
$attributesHTML .= "</div>";

$items = "";
if (!empty($_GET["items_type_id_items"])) {
    $tempItems = getItems(0, "WHERE `items_type_id_items` = ?", [$_GET["items_type_id_items"]]);
    if ($tempItems == "") {
        $items = "<p class='notfound'>Ничего не найдено</p>";
    } else {
        $items = $tempItems;
    }
} else {
    $items = getItems();
}

include_once __DIR__ . "/../app/server/header.php";
?>

<main class="content">
    <section class="form-wrapper">
        <article class="form-switches">
            <h1 class="legend">Поиск</h1>
            <img src="assets/img/selectArrow.png">
        </article>
        <article class="form-appear">
            <form action="/" method="POST" class="form">
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="name_search_items" data-is-insert-server="0">
                    <p class="error"></p>
                </div>
                    <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="description_search_items" data-is-insert-server="0">
                    <p class="error"></p>
                </div>
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="min_cost_items" data-is-insert-server="0">
                    <p class="error"></p>
                </div>
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="max_cost_items" data-is-insert-server="0">
                    <p class="error"></p>
                </div>
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="min_count_items" data-is-insert-server="0">
                    <p class="error"></p>
                </div>
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="max_count_items" data-is-insert-server="0">
                    <p class="error"></p>
                </div>
                <div class="field-properties">
                    <div class="field">
                        <label class="label">Тип товара</label>
                        <?= $typesHTML ?>
                    </div>
                    <?= $attributesHTML ?>
                </div>
                <div class="field">
                    <label class="label"><input class="input popular" type="checkbox" data-name="popular_items" data-is-insert-server="0"></label>
                </div>
                <div class="field">
                    <label class="label"><input class="input strict" type="checkbox" data-name="strict_search" data-is-insert-server="0"></label>
                </div>
                <div class="field">
                    <input class="button" type="submit" value="Найти" id="search_button" name="submit_button">
                </div>
            </form>
        </article>
    </section>
    <section class="content items">
        <?= $items ?>
    </section>
</main>

<?php include_once __DIR__ . "/../app/server/footer.php"; ?>