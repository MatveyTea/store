"use strict";

const receiptButton = document.querySelector(".button.receipt");
receiptButton?.addEventListener("click", async () => {
    const resultData = await sendToServer({
        "server_type": "receipt_orders",
        "datetime_buy_orders": receiptButton.dataset.datetime
    });
    if (resultData["status"] == "OK") {
        document.querySelector(".status p:last-of-type").classList.add("completed");
        receiptButton.remove();
    } else {
        showModal("Не удалось");
    }
});