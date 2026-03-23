<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

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

include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action="/" method="POST" class="form">
        <legend class="legend">Поиск</legend>
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
        <div class="field-attributes">
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
            <input class="button input" type="submit" value="Найти" id="search_button" name="submit_button">
        </div>
    </form>
    <section class="content items">
        <?= getItems() ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>