"use strict";

const receiptButton = document.querySelector(".button.receipt");
receiptButton?.addEventListener("click", async () => {
    const dataResult = await sendToServer({
        "server_type": "receipt_orders",
        "id_orders": receiptButton.dataset.idOrder
    });
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        document.querySelector(".status p:last-of-type").classList.add("completed");
        receiptButton.remove();
    } else {
        showModal("Не удалось выполнить запрос.");
    }
});