<?php
$randomItem = makeSelectQuery("SELECT `id_items` FROM `items` ORDER BY RAND() LIMIT 1", [], true);
if ($randomItem == "FAIL") {
    $randomItem = [];
    $randomItem["id_items"] = 1;
}

$popularItem = makeSelectQuery("SELECT `id_items` FROM `items` ORDER BY `views_items` DESC LIMIT 1", [], true);
if ($popularItem == "FAIL") {
    $popularItem = [];
    $popularItem["id_items"] = 1;
}
?>

<footer>
    <div class="footer-container content">
        <p class="footer-slogan">Уют складывается из мелочей, каждая из которых дышит заботой и гармонией. Качество же проявляется не только в безупречном исполнении, но и в ощущении долговечности и продуманности каждого предмета, приглашающего вас остаться и насладиться моментом.</p>
        <div class="footer-items">
            <a href="aboutItem.php?id_item=<?= $randomItem["id_items"] ?>" class="footer-item button">Случайный товар</a>
            <a href="/" class="footer-item"><img src="img/main/logo.png"></a>
            <a href="aboutItem.php?id_item=<?= $popularItem["id_items"] ?>" class="footer-item button">Популярный товар</a>
        </div>
        <p class="footer-copyright">© 2026 Интернет-магазин. <br>Все права защищены.</p>
    </div>
</footer>