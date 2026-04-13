"use strict";

const receiptButton = document.querySelector(".button.receipt");
receiptButton?.addEventListener("click", async () => {
    const resultData = await sendToServer({
        "server_type": "receipt_orders",
        "id_orders": receiptButton.dataset.idOrder
    });
    if (resultData["status"] == "OK") {
        document.querySelector(".status p:last-of-type").classList.add("completed");
        receiptButton.remove();
    } else {
        showModal("Не удалось");
    }
});