"use strict";

const buyButton = document.querySelector("button.buy");
const historyBasket = document.querySelector(".history-basket");
const currentBasket = document.querySelector(".current-basket");
buyButton?.addEventListener("click", async () => {
    const result = await fetch("server.php", {
        "method": "POST",
        "headers": {
            "Content-Type": "application/json"
        },
        "body": JSON.stringify({
            "server_type": "buy_items"
        })
    });
    const resultData = await result.json();
    if (resultData["status"] == "OK") {
        historyBasket.querySelector("h2").insertAdjacentHTML("afterend", resultData["data"]["historyHTML"]);
        currentBasket.innerHTML = "<h2>У вас нет ничего в корзине</h2>";
    }
});