<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty(file_get_contents("php://input"))) {
    $json = json_decode(file_get_contents("php://input"), true);
    $serverType = $json["server_type"] ?? "";
    $isAuth = isUserAuth();
    $isAdmin = isAdmin();
    $isDeliver = isDeliver();

    if ($isAuth && $serverType == "change_basket" && !empty($json["id_items"]) && isset($json["count_items"]) && !empty($json["action_items"])) { // index.js и aboutItem.js - Добавление и удаление товаров в корзине
        changeBasket($json["id_items"], $json["count_items"], $json["action_items"]);
    } else if ($isAuth && $serverType == "buy_items" && !empty($json["server_type"]) && !empty($json["address_orders"]) && isset($json["note_orders"]) && !empty($json["datetime_plan_orders"])) { // profile.js - Покупка товаров в корзине
        buyItems($json);
    } else if ($isAdmin && $serverType == "delete_items" && !empty($json["id_item"])) { // adminEditItem.js - Удаление товара
        deleteItem($json["id_item"]);
    } else if ($serverType == "search_items" && isset($json["offset_search_items"])) { // index.js - Поиск товаров
        searchItems($json);
    } else if ($isAuth && $serverType == "add_comment" && !empty($json["id_items"] && !empty($json["rating_comments"]))) { // aboutItem.js - Добавление комментария о товаре
        addComment($json["id_items"], $json["rating_comments"], $json["text_comments"] ?? "");
    } else if ($isAdmin && $serverType == "delete_from_table" && !empty($json["id"]) && !empty($json["table"])) { // adminEditTable.js - Удаление поля из таблиц ("properties" или "status" или "items_type")
        deleteFromTable($json["table"], $json["id"]);
    } else if ($isAuth && $serverType == "delete_comment" && !empty($json["id_comment"])) { // aboutItem.js - Удаление комментария
        deleteComment($json["id_comment"]);
    } else if ($isAuth && $serverType == "add_view" && !empty($json["id_item"])) { // aboutItem.js - Увеличение счётчик просмотра
        addView($json["id_item"]);
    } else if ($isAdmin && $serverType == "delete_one_from_table" && !empty($json["id"])) { // adminEditTable.js - Удалить свойства из таблицы attributes
        deleteOneFromTable($json["id"]);
    } else if ($isAdmin && $serverType == "banned_users" && !empty($json["id_users"]) && isset($json["is_banned_users"])) { // adminEditUser.js - Блокировка пользователя
        bannedUser($json["id_users"], $json["is_banned_users"]);
    } else if ($isAdmin && $serverType == "search_users") {
        searchUsers($json);
    } else if ($isAdmin && $serverType == "delete_user" && !empty($json["id_users"])) {
        deleteUser($json["id_users"]);
    } else if ($isAdmin && $serverType == "deliver_users" && !empty($json["id_users"])) {
        deliverUsers($json["id_users"]);
    } else if ($isDeliver && $serverType == "accept_orders" && !empty($json["id_orders"])) { // allOrders.js - Принятие товаров доставщиком
        acceptOrders($json["id_orders"]);
    } else if ($isDeliver && $serverType == "status_orders" && !empty($json["id_orders"])) { // myOrders.js - Изменение статусов доставки доставщиком
        statusOrders($json["id_orders"]);
    } else if ($serverType == "receipt_orders" && !empty($json["id_orders"])) { // deliveryItem.js - Изменение статусов доставки клиентом
        receiptOrders($json["id_orders"]);
    } else { // Иначе ошибка
        setAnswer("FAIL");
    }
}