<?php
include_once __DIR__ . "/../app/server/function.php";

$typesHTML = "";
$types = makeSelectQuery("SELECT * FROM `items_type`", [], false);
if ($types == "FAIL") {
    redirect();
}
foreach ($types as $type) {
    $typesHTML .= "<label>
        $type[name_items_type]
        <input class='input checkbox' type='checkbox' data-name='items_type_id_search_items' value='$type[id_items_type]'>
    </label>";
}

$attributesHTML = "";
$attributes = makeSelectQuery("SELECT 
    `id_attributes`,
    `value_attributes`,
    `id_properties`,
    `name_properties`
    FROM `attributes`
    JOIN `properties` ON `id_properties` = `properties_id_attributes`
    ORDER BY `id_properties`
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
        $attributesHTML .= "
            <div class='field'>
            <p>$attribute[name_properties]</p>
        ";
        $currentAttributeID = $attribute["id_properties"];
    }
    $attributesHTML .= "<label>
        $attribute[value_attributes]
        <input class='input checkbox' type='checkbox' value='$attribute[id_attributes]' data-name='id_search_attributes'>
    </label>";
}
$attributesHTML .= "</div>";

$itemsHTML = "";
if (!empty($_GET["items_type_id_items"])) {
    $itemsHTML = getItems(0, "WHERE `items_type_id_items` = ?", [$_GET["items_type_id_items"]]);
} else {
    $itemsHTML = getItems();
}

if ($itemsHTML == "") {
    $itemsHTML = "
        <h2 class='notfound'>Ничего не найдено</h2>
        <h2 class='title hidden'>Каталог</h2>
        <article class='items'></article>
    ";
} else {
    $itemsHTML = "
        <h2 class='notfound hidden'>Ничего не найдено</h2>
        <h2 class='title'>Каталог</h2>
        <article class='items'>
            $itemsHTML
        </article>
    ";
}

include_once __DIR__ . "/../app/server/header.php";
?>

<main class="content">
    <section class="form-wrapper">
        <article class="form-switches button close">
            <h1 class="legend">Поиск</h1>
            <img src="assets/img/selectArrow.png">
        </article>
        <article class="form-appear close">
            <form action="/" method="POST" class="form">
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="name_search_items">
                    <p class="error"></p>
                </div>
                    <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="description_search_items">
                    <p class="error"></p>
                </div>
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="min_cost_items">
                    <p class="error"></p>
                </div>
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="max_cost_items">
                    <p class="error"></p>
                </div>
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="min_count_items">
                    <p class="error"></p>
                </div>
                <div class="field">
                    <label class="label"></label>
                    <input class="input" type="search" data-name="max_count_items">
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
                    <label class="label"><input class="input popular checkbox" type="checkbox" data-name="popular_items"></label>
                </div>
                <div class="field">
                    <label class="label"><input class="input discount checkbox" type="checkbox" data-name="discount_search_items"></label>
                </div>
                <div class="field">
                    <label class="label"><input class="input strict checkbox" type="checkbox" data-name="strict_search"></label>
                </div>
                <div class="field">
                    <input class="button" type="submit" value="Найти" id="search_button" name="submit_button">
                </div>
            </form>
        </article>
    </section>
    <section class="items-container">
        <?= $itemsHTML ?>
    </section>
</main>

<?php include_once __DIR__ . "/../app/server/footer.php"; ?>