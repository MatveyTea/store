<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty(file_get_contents("php://input"))) {
    $json = json_decode(file_get_contents("php://input"), true);
    $serverType = $json["server_type"] ?? "";
    $idUser = $_SESSION["id_user"] ?? null;
    $isAuth = isUserAuth();


    if ($serverType == "basket" && $isAuth) { // index.js - Добавление/Удаление вещей в корзине
        if (empty($json["id_item"]) || !isset($json["count_item"]) || empty($json["action_item"]) || !in_array($json["action_item"], ["add", "remove"])) {
            echo json_encode(["status" => "FAIL"]);
            exit;
        }

        $id = $json["id_item"];
        $count = $json["count_item"];
        $action = $json["action_item"];

        try {
            $item = $link->prepare("SELECT `count_items` FROM `items` WHERE `id_items` = ?");
            $item->execute([$id]);
            $item = $item->fetch(PDO::FETCH_ASSOC);

            if (!empty($item) && $item["count_items"] >= $count) {
                if ($action == "add") {
                    $check = $link->prepare("SELECT `id_baskets` FROM `baskets` WHERE `items_id_baskets` = ? AND `status_id_baskets` = ? AND `users_id_baskets` = ?");
                    $check->execute([$id, 1, $idUser]);
                    $check = $check->fetch(PDO::FETCH_ASSOC);
                    if (!empty($check["id_baskets"])) {
                        $link->prepare("UPDATE `baskets` SET `count_baskets` = ? WHERE `id_baskets` = ?")->execute([$count, $check["id_baskets"]]);
                    } else {
                        $link->prepare("INSERT INTO `baskets` (`items_id_baskets`, `count_baskets`, `status_id_baskets`, `users_id_baskets`) VALUES (?, ?, ?, ?)")->execute([$id, $count, 1, $idUser]);
                    }
                } else if ($action == "remove") {
                    $link->prepare("DELETE FROM `baskets` WHERE `items_id_baskets` = ? AND `status_id_baskets` = ? AND `users_id_baskets` = ?")->execute([$id, 1, $idUser]);
                }
                echo json_encode(["status" => "OK"]);
            } else {
                echo json_encode(["status" => "FAIL"]);
            }
        } catch (Throwable $e) {
            echo json_encode(["status" => "FAIL"]);
        }
    } else if ($serverType == "buy_items") { // profile.js Покупка товаров в корзине
        try {
            $datetime = date("y-m-d H:i:s");
            $link->prepare("UPDATE `baskets` SET `status_id_baskets` = 2, `datetime_baskets` = ? WHERE `users_id_baskets` = ? AND `status_id_baskets` = 1 AND `datetime_baskets` IS NULL ")->execute([$datetime, $idUser]);
            echo json_encode(["status" => "OK"]);
        } catch (Throwable $e) {
            echo json_encode(["status" => "FAIL"]);
        }
    } else if ($serverType == "search_items" && !empty($json["offset"])) { // index.php Поиск товаров
        $offset = $json["offset"];    
        $name = trim($json["name_search_items"] ?? "");
        $maxCount = $json["min_cost_items"] ?? "";
        $minCount = $json["max_cost_items"] ?? "";

        $where = [];
        if ($name != "") {
            $where[] = "`name_items` LIKE '%$name%'";
        }
        if ($maxCount != "" && $minCount != "") {
            $where[] ="`cost_items` <= $maxCount AND `cost_items` >= $minCount";
        }
        if ($where != []) {
            $where = "WHERE " . join(" AND ",  $where);
        }

        if (is_array($where)) {
            $where = "";
        }

        $items = getItems(20, $offset, $where);
        if (!empty($items)) {
            echo json_encode(["status" => "OK", "items" => $items]);
        } else {
            echo json_encode(["status" => "NOTFOUND"]);
        }
    } else {
        echo json_encode(["status" => "FAIL"]);
    }
}