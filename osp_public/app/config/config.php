<?php
session_start();
$host = "MySQL-8.4";
$dbname = "store";
$username = "root";
$password = "";

try {
    $link = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (Throwable $error) {
    echo "Ошибка подключения к базе данных. Код ошибки: " . $error->getCode();
    exit;
}