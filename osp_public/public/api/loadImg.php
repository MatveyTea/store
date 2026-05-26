<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!empty($_GET["path"])) {
    $path = $_GET['path'];
    $fullPath = __DIR__ . "/../../app/upload/$path";

    $explodePath = explode("/", $path);
    $dir = $explodePath[0] ?? "";
    $file = $explodePath[1] ?? "";

    if (!in_array($dir, ["avatars", "items", "supports"])) {
        http_response_code(406);
        exit;
    }

    if (strpos($path, "..") != false) {
        http_response_code(403);
        exit;
    }

    if ($dir == "supports") {
        $check = makeSelectQuery("SELECT
            `users_id_talks`,
            `users_support_talks`
            FROM `supports`
            JOIN `talks` ON `id_talks` = `talks_id_supports`
            WHERE `image_supports` = ?
        ", [$file], true);

        if ($check == "FAIL") {
            http_response_code(500);
            exit;
        }

        if (!in_array(getUserID(), $check)) {
            http_response_code(403);
            exit;
        }
    }

    if (!file_exists($fullPath) || !is_file($fullPath)) {
        header("Content-Type: image/png");
        readfile(__DIR__ . "/../assets/img/default.png");
        exit;
    }

    $mime = mime_content_type($fullPath);
    header("Content-Type: $mime");
    readfile($fullPath);
    exit;
} else {
    http_response_code(406);
    exit;
}