<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty(file_get_contents("php://input"))) {
    $json = json_decode(file_get_contents("php://input"), true);
    $serverType = $json["server_type"] ?? "";
    $isAuth = isUserAuth();
    $isAdmin = isAdmin();

    if ($isAuth && $serverType == "basket" && !empty($json["id_item"]) && isset($json["count_item"]) && !empty($json["action_item"])) { // index.js и aboutItem.js - Добавление и удаление товаров в корзине
        changeBasket($json["id_item"], $json["count_item"], $json["action_item"]);
    } else if ($isAuth && $serverType == "buy_items") { // profile.js - Покупка товаров в корзине
        buyItems();
    } else if ($isAdmin && $serverType == "delete_items" && !empty($json["id_item"])) { // adminEditItem.js - Удаление товара++
        deleteItem($json["id_item"]);
    } else if ($serverType == "search_items" && isset($json["offset"])) { // index.js - Поиск товаров
        $minCount = $json["min_cost_items"] == "" ? 1 : intval($json["min_cost_items"]);
        $maxCount = $json["max_cost_items"] == "" ? 10_000_000 : intval($json["max_cost_items"]);
        searchItems($json["offset"], trim($json["name_search_items"]), $minCount, $maxCount);
    } else if ($isAuth && $serverType == "add_comment" && !empty($json["id_items"] && !empty($json["rating_comments"]))) { // aboutItem.js - Добавление комментария о товаре
        addComment($json["id_items"], $json["rating_comments"], $json["text_comments"] ?? "");
    } else if ($isAdmin && $serverType == "delete_item_properties" && !empty($json["id_items_properties"])) { // adminEditItem.js - Удаление свойства у товара
        deleteItemProperties($json["id_items_properties"]);
    } else if ($isAdmin && $serverType == "delete_from_table" && !empty($json["id"]) && !empty($json["table"])) { // adminEditTable.js - Удаление поля из таблиц ("properties" или "status" или "items_type")
        deleteFromTable($json["table"], $json["id"]);
    } else if ($isAuth && $serverType == "delete_comment" && !empty($json["id_comment"])) {
        deleteComment($json["id_comment"]);
    } else if ($isAuth && $serverType == "add_view" && !empty($json["id_item"])) {
        addView($json["id_item"]);
    } else {
        setAnswer("FAIL");
    }
}