<?php
session_start();
$host = "MySQL-8.4";
$dbname = "store";
$username = "root";
$password = "";

try {
    $link = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (Throwable $e) {
    echo "Ошибка подключения к базе данных.";
    exit;
}