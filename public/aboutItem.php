<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (empty($_GET["id_item"])) {
    redirect();
}


include_once __DIR__ . "/header.php";
?>
<main class="content">
    <section>
    </section>
</main>
<?php include_once __DIR__ . "/footer.php"; ?>