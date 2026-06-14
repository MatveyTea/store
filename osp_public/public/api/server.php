<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!empty(file_get_contents("php://input"))) {
    $json = json_decode(file_get_contents("php://input"), true);

    if (!empty($json["token"]) && !empty($_SESSION["token"]) && $json["token"] == $_SESSION["token"]) {
        $serverType = $json["server_type"] ?? "";
        unset($json["server_type"], $json["token"]);
        $isAuth = isUserAuth();
        $isAdmin = isAdmin();
        $isDeliver = isDeliver();
        $isSupport = isSupport();

        if ($isAuth && $serverType == "change_basket" && !empty($json["id_items"]) && isset($json["count_items"]) && !empty($json["action_items"])) { // index.js и aboutItem.js - Добавление и удаление товаров в корзине
            changeBasket($json["id_items"], $json["count_items"], $json["action_items"]);
        } else if ($isAuth && $serverType == "delete_avatar") { // profile.js - Удаление аватарки
            deleteAvatar();
        } else if ($isAuth && $serverType == "buy_items" && !empty($json["address_orders"]) && isset($json["note_orders"]) && !empty($json["datetime_plan_orders"])) { // basket.js - Покупка товаров в корзине
            buyItems($json);
        } else if($isAuth && $serverType == "change_favorites" && !empty($json["id_items"])) { // function.js - Добавление в избранное
            changeFavorites($json["id_items"]);
        } else if ($isAdmin && $serverType == "delete_items" && !empty($json["id_item"])) { // editItem.js - Удаление товара
            deleteItem($json["id_item"]);
        } else if ($serverType == "search_items" && isset($json["offset_search_items"])) { // index.js - Поиск товаров
            searchItems($json);
        } else if ($isAuth && $serverType == "add_comment" && !empty($json["id_items"] && !empty($json["rating_comments"]))) { // aboutItem.js - Добавление комментария о товаре
            addComment($json["id_items"], $json["rating_comments"], $json["text_comments"] ?? "");
        } else if ($isAdmin && $serverType == "delete_item_properties" && !empty($json["id_items_properties"])) {
            deleteItemProperties($json["id_items_properties"]);
        } else if ($isAdmin && $serverType == "delete_from_table" && !empty($json["id"]) && !empty($json["table"])) { // editTable.js - Удаление поля из таблиц ("properties" или "items_type")
            deleteFromTable($json["table"], $json["id"]);
        } else if ($isAuth && $serverType == "delete_comment" && !empty($json["id_comment"])) { // aboutItem.js - Удаление комментария
            deleteComment($json["id_comment"]);
        } else if ($isAuth && $serverType == "add_view" && !empty($json["id_item"])) { // aboutItem.js - Увеличение счётчик просмотра
            addView($json["id_item"]);
        } else if ($isAdmin && $serverType == "delete_one_from_table" && !empty($json["id"])) { // editTable.js - Удалить свойства из таблицы attributes
            deleteOneFromTable($json["id"]);
        } else if ($isAdmin && $serverType == "banned_users" && !empty($json["id_users"])) { // editUser.js - Блокировка пользователя
            bannedUser($json["id_users"]);
        } else if ($isAdmin && $serverType == "search_users") { // editUser.js - Поиск пользователей
            searchUsers($json);
        } else if ($isAdmin && $serverType == "delete_user" && !empty($json["id_users"])) { // editUser.js - Удаление пользователей
            deleteUser($json["id_users"]);
        } else if ($isAdmin && $serverType == "deliver_users" && !empty($json["id_users"])) { // editUser.js - Сменить роль доставщика
            deliverUsers($json["id_users"]);
        } else if ($isDeliver && $serverType == "accept_orders" && !empty($json["id_orders"])) { // allOrders.js - Принятие товаров доставщиком
            acceptOrders($json["id_orders"]);
        } else if ($isDeliver && $serverType == "status_orders" && !empty($json["id_orders"])) { // myOrders.js - Изменение статусов доставки доставщиком
            statusOrders($json["id_orders"]);
        } else if ($serverType == "receipt_orders" && !empty($json["id_orders"])) { // deliveryItem.js - Изменение статусов доставки клиентом
            receiptOrders($json["id_orders"]);
        } else if ($isAuth && $serverType == "get_talk_html" && !empty($json["id_talks"])) {
            getTalkHTML($json["id_talks"]);
        } else if ($isAuth && $serverType == "start_talk" && !empty($json["title_talks"]) && !empty($json["text_supports"])) { // support.js - Первое написание сообщение в поддержку пользователем
            startTalk($json);
        } else if ($isAuth && $serverType == "continue_talk" && !empty($json["talks_id_supports"]) && !empty($json["text_supports"])) { // support.js - Написание сообщение в поддержку пользователем
            continueTalk($json);
        } else if ($isAuth && $serverType == "ping_message" && !empty($json["id_supports"])) { // support.js - Получение сообщения через интервал времени
            pingMessage($json);
        } else if ($isAuth && $serverType == "end_talk" && !empty($json["talks_id_supports"])) { // support.js - Конец разговора
            endTalk($json["talks_id_supports"]);
        } else if ($isSupport && $serverType == "accept_support" && !empty($json["id_talks"])) { // allSupport.js - Принятие сообщение
            acceptSupport($json["id_talks"]);
        } else if ($isAdmin && $serverType == "support_users" && !empty($json["id_users"])) { // allSupport.js - Изменение роли поддержки
            supportUsers($json["id_users"]);
        } else { // Иначе ошибка
            setAnswer("FAIL");
        }
    } else {
        setAnswer("FAIL");
    }
}