"use strict";

const acceptOrdersButton = document.querySelectorAll(".button[data-datetime]");
acceptOrdersButton.forEach((button) => {
    button.addEventListener("click", async () => {
        const resultData = await sendToServer({
            "server_type": "accept_orders",
            "datetime_buy_orders": button.dataset.datetime
        });
        if (resultData["status"] == "OK") {
            window.location.href = "/myOrders.php";
        } else {
            showModal("Не удалось принять заказ");
        }
    });
});