"use strict";

const acceptOrdersButton = document.querySelectorAll(".button[data-id-order]");
acceptOrdersButton.forEach((button) => {
    button.addEventListener("click", async () => {
        const resultData = await sendToServer({
            "server_type": "accept_orders",
            "id_orders": button.dataset.idOrder
        });
        if (resultData["status"] == "OK") {
            window.location.href = "/myOrders.php";
        } else {
            showModal("Не удалось принять заказ");
        }
    });
});