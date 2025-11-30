<?php
include_once __DIR__ . "/header.php";

$items = $link->query("SELECT `id_items`, `name_items`, `count_items`, `image_items` FROM `items`");

$itemsHTML = "";

foreach ($items as $item) {
    $img = checkImage($item["image_items"]);
    $itemsHTML .= "<div data-id='$item[id_items]' data-count='$item[count_items]'>
        <p>$item[name_items]</p>
        <p>Количество: $item[count_items]</p>
        <img src='$img'>
        <button class='buy'>Указать количество</button>
        <span class='hidden'>
            <button class='minus'>-</button>
            <p>Количество: <b>0</b></p>
            <button class='plus'>+</button>
            <button class='basket' data-type='add'>Добавить в корзину</button>
        </span>
    </div>";
}

?>

<section>
    <?= $itemsHTML ?>
</section>
<script>
    "use strict";
    const items = document.querySelectorAll("div[data-id]");
    items.forEach((item) => {
        const buyButton = item.querySelector("button.buy");
        const counter = item.querySelector("span.hidden");
        const minusButton = counter.querySelector("button.minus");
        const plusButton = counter.querySelector("button.plus");
        const counterText = counter.querySelector("p b");
        const basketButton = counter.querySelector("button.basket");
        buyButton.addEventListener("click", () => {
            counter.classList.remove("hidden");
            counterText.textContent = 1;
            buyButton.classList.add("hidden");
        });
        minusButton.addEventListener("click", () => {
            if (counterText.textContent <= 1) {
                counterText.textContent = 0;
                counter.classList.add("hidden");
                buyButton.classList.remove("hidden");
            } else {
                counterText.textContent = +counterText.textContent - 1
            }
        });
        plusButton.addEventListener("click", () => {
            if (item.dataset.count > counterText.textContent) {
                counterText.textContent = +counterText.textContent + 1
            }
        });
        basketButton.addEventListener("click", async () => {
            basketButton.textContent = basketButton.dataset.type == "add" ? "Убрать из корзины" : "Добавить в корзину"
            const dataItem = {
                "id_item": item.dataset.id,
                "count": counterText.textContent,
                "type": basketButton.dataset.type
            };
            const result = await fetch("basketServer.php", {
                "method": "POST",
                "headers": {
                    "Content-Type": "application/json"
                },
                "body": JSON.stringify(dataItem)
            });
            const dataResult = await result.json();
            if (dataResult["status"] != "OK") {
                basketButton.textContent = basketButton.dataset.type == "add" ? "Добавить в корзину" : "Убрать из корзины";
            } else {
                basketButton.dataset.type = basketButton.dataset.type == "add" ? "remove" : "add";
            }
        });
    });
</script>