<?php
include_once __DIR__ . "/config/config.php";

function redirect($path = "index.php")
{
    if (!file_exists(__DIR__ . "/$path")) {
        header("Location: /");
    } else {
        header("Location: $path");
    }
    exit;
}
function checkImage($img)
{
    if (!file_exists(__DIR__ . "/img/index/$img")) {
        return "/img/index/base.png";
    }
    return "/img/index/$img";
}