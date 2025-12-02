<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";
include_once __DIR__ . "/header.php";
?>
<script src="js/index.js" defer></script>
<link rel="stylesheet" href="css/index.css">
<section class="content">
    <?= getItems(20, 0) ?>
</section>