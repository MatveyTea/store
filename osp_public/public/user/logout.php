<?php
include_once __DIR__ . "/../../app/server/function.php";
unset($_SESSION["id_user"], $_SESSION["token"]);
clearValidatedSession();
redirect("user/auth.php");