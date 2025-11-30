<?php
include_once __DIR__ . "/config/config.php";

function redirect($path = "index.php")
{
    if (!file_exists(__DIR__ . "/$path")) {
        header("Location: /");
    } else {
        header("Location: $path");
    }
    die();
}
function checkImage($img)
{
    if (!file_exists(__DIR__ . "/img/catalog/$img")) {
        return "/img/catalog/base.png";
    }
    return "/img/catalog/$img";
}