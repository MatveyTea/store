"use strict";

const acceptOrdersButton = document.querySelectorAll(".button[data-id-order]");
acceptOrdersButton.forEach((button) => {
    button.addEventListener("click", async () => {
        const dataResult = await sendToServer({
            "server_type": "accept_orders",
            "id_orders": button.dataset.idOrder
        });
        if (dataResult?.isValid == false) return;
        if (dataResult["status"] == "OK") {
            window.location.href = "/deliver/myOrders.php";
        } else {
            showModal("Не удалось выполнить запрос.");
        }
    });
});