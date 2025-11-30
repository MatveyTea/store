<?php
include_once __DIR__ . "/config/config.php";

if (!empty(file_get_contents("php://input"))) {
    $json = json_decode(file_get_contents("php://input"), true);

    if (!empty($json["id_item"]) && !empty($json["count"]) && !empty($json["type"])) {
        $id = $json["id_item"];
        $count = $json["count"];
        $type = $json["type"];
        $item = $link->prepare("SELECT `count_items` FROM `items` WHERE `id_items` = ?");
        $item->execute([$id]);
        $item = $item->fetch(PDO::FETCH_ASSOC);
        if (!empty($item)) {
            if ($item["count_items"] >= $count) {
                try {
                    if ($type == "add") {
                        $link->prepare("INSERT INTO `baskets` (`items_id_baskets`, `count_baskets`, `status_id_baskets`) VALUES (?, ?, ?)")->execute([$id, $count, 1]);
                        echo json_encode(["status" => "OK"]);
                    } else if ($type == "remove") {
                        $link->prepare("DELETE FROM `baskets` WHERE `items_id_baskets` = ? AND `status_id_baskets` = ?")->execute([$id, 1]);
                        echo json_encode(["status" => "OK"]);
                    } else {
                        echo json_encode(["status" => "FAIL4"]);
                    }
                } catch (Throwable $e) {
                    echo json_encode(["status" => "FAIL3"]);
                }
            } else {
                echo json_encode(["status" => "FAIL2"]);
            }
        } else {
            echo json_encode(["status" => "FAIL1"]);
        }
    }
}
?>