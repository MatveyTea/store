<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!empty(file_get_contents("php://input"))) {
    $json = json_decode(file_get_contents("php://input"), true);
    $serverType = $json["server_type"] ?? "";
    $idUser = getUserID();
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
    } else if ($serverType == "buy_items" && $isAuth) { // profile.js Покупка товаров в корзине
        try {
            $datetime = date("y-m-d H:i:s");
            try {
                $link->prepare("UPDATE `baskets` SET `status_id_baskets` = 2, `datetime_buy_baskets` = ? WHERE `users_id_baskets` = ? AND `status_id_baskets` = 1 AND `datetime_buy_baskets` IS NULL ")->execute([$datetime, $idUser]);

                $stmt = $link->prepare("SELECT
                    `baskets`.`id_baskets`,
                    `baskets`.`count_baskets`,
                    `baskets`.`users_id_baskets`,
                    `baskets`.`datetime_buy_baskets`,
                    `items`.`id_items`,
                    `items`.`name_items`,
                    `items`.`image_items`,
                    `items`.`cost_items`
                    FROM `baskets`
                    JOIN `items` ON `items`.`id_items` = `items_id_baskets`
                    WHERE `users_id_baskets` = ? AND `datetime_buy_baskets` = ?
                    ORDER BY CASE WHEN `baskets`.`datetime_buy_baskets` IS NULL THEN 0 ELSE 1 END, `baskets`.`datetime_buy_baskets` DESC
                ");
                $stmt->execute([$idUser, $datetime]);

                echo json_encode(["status" => "OK", "data" => getBasketHTML($stmt->fetchAll(PDO::FETCH_ASSOC))]);
            } catch (Throwable $e) {
                echo json_encode(["status" => "FAIL"]);
            }
        } catch (Throwable $e) {
            echo json_encode(["status" => "FAIL"]);
        }
    } else if ($serverType == "delete_items" && isAdmin() && !empty($json["id_item"])) {
        try {
            $link->prepare("DELETE FROM `items` WHERE `id_items` = ?")->execute([$json["id_item"]]);
            echo json_encode(["status" => "OK"]);
        } catch (Throwable $e) {
            echo json_encode(["status" => "FAIL"]);
        }
    } else if ($serverType == "search_items" && isset($json["offset"])) { // index.php Поиск товаров
        $offset = $json["offset"];    
        $name = trim($json["name_search_items"] ?? "");
        $minCount = $json["min_cost_items"] == "" ? - 1 : intval($json["min_cost_items"]);
        $maxCount = $json["max_cost_items"] == "" ? 10_000_000 : intval($json["max_cost_items"]);

        $where = [];
        if ($name != "") {
            $where[] = "`name_items` LIKE '%$name%'";
        }
        if ($maxCount >= $minCount) {
            if ($minCount > -1) {
                $where[] = "`cost_items` >= $minCount";
            }
            if ($maxCount < 10_000_000) {
                $where[] = "`cost_items` <= $maxCount";
            }
        }

        if ($where != []) {
            $where = "WHERE " . join(" AND ",  $where);
        }

        if (is_array($where)) {
            $where = "";
        }

        $items = getItems(50, $offset, $where);
        if (!empty($items)) {
            echo json_encode(["status" => "OK", "items" => $items]);
        } else {
            echo json_encode(["status" => "NOTFOUND"]);
        }
    } else if ($isAuth && $serverType == "add_comment" && !empty($json["id_items"] && !empty($json["rating_comments"]))) {
        $validatedData = getValidatedData(array: ["id_items" => $json["id_items"], "rating_comments" => $json["rating_comments"], "text_comments" => $json["text_comments"] ?? ""]);

        if ($validatedData["isCorrect"]) {
            $validatedData = $validatedData["data"];
            if ($validatedData["rating_comments"] == 0) {
                echo json_encode(["status" => "FAIL"]);
            }

            $isSuccess = makeInsertQuery("INSERT INTO
            `comments` (`users_id_comments`, `text_comments`, `rating_comments`, `date_add_comments`, `items_id_comments`)
            VALUES (?, ?, ?, ?, ?)",
            [$idUser, $validatedData["text_comments"], $validatedData["rating_comments"], date("y-m-d"), $validatedData["id_items"]]);
            if ($isSuccess) {
                $comment = makeSelectQuery("SELECT
                    `comments`.`text_comments`,
                    `comments`.`rating_comments`,
                    `comments`.`date_add_comments`,
                    `users`.`name_users`,
                    `users`.`avatar_users`
                    FROM `comments`
                    JOIN `users` ON `comments`.`users_id_comments` = `users`.`id_users`
                    WHERE `comments`.`id_comments` = ? AND `users`.`id_users` = ?", [$link->lastInsertId(), $idUser], getOne: false);
                echo json_encode(["status" => "OK", "data" => getCommentsHTML($comment), "rating" => getRatingItem($validatedData["id_items"])]);
            } else {
                echo json_encode(["status" => "FAIL"]);
            }
        } else {
            echo json_encode(["status" => "FAIL"]);
        }

    } else {
        echo json_encode(["status" => "FAIL"]);
    }
}