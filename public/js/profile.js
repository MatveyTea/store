"use strict";

const buyButton = document.querySelector(".buy");
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
        window.location.reload();
    }
});