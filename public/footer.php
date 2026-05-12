<?php
$randomItem = makeSelectQuery("SELECT `id_items` FROM `items` ORDER BY RAND() LIMIT 1", [], true);
if ($randomItem == "FAIL") {
    $randomItem = [];
    $randomItem["id_items"] = 1;
}

$randomCategory = makeSelectQuery("SELECT `id_items_type` FROM `items_type` ORDER BY RAND() LIMIT 1", [], true);
if ($randomCategory == "FAIL") {
    $randomCategory = [];
    $randomCategory["id_items_type"] = 1;
}
?>

<footer>
    <div class="footer-container content">
        <p class="footer-slogan">Уют складывается из мелочей, каждая из которых дышит заботой и гармонией. Качество же проявляется не только в безупречном исполнении, но и в ощущении долговечности и продуманности каждого предмета, приглашающего вас остаться и насладиться моментом.</p>
        <div class="footer-items">
            <a href="aboutItem.php?id_item=<?= $randomItem["id_items"] ?>" class="footer-item button">Случайный товар</a>
            <a href="/" class="footer-item"><img src="img/main/logo.png"></a>
            <a href="index.php?items_type_id_items=<?= $randomCategory["id_items_type"] ?>" class="footer-item button">Случайная категория</a>
        </div>
        <p class="footer-copyright">© 2026 Интернет-магазин. <br>Все права защищены.</p>
    </div>
</footer>