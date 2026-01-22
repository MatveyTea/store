<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";
unset($_SESSION["id_user"], $_SESSION["is_admin"]);
redirect();