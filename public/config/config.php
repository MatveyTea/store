<?php
session_start();
include_once __DIR__ . "/../function.php";
$link;
try {
    $link = new PDO("mysql:host=MySQL-8.4;dbname=store", "root", "");
} catch (Throwable $error){
    echo "Ошибка подключения к базе данных: " . $error->getMessage();
    die();
}