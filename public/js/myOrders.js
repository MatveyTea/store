"use strict";

const allStatus = {
    3: {
        "action": "Собрано",
        "text": "Соберите товар"
    },
    4: {
        "action": "Доставлено",
        "text": "Доставьте товар"
    },
    5: {
        "action": null,
        "text": "Ожидайте пока покупатель получит товар"
    },
    6: {
        "action": null,
        "text": "Товар получен"
    }
};

const actionButtons = document.querySelectorAll(".button.action[data-id-status]");
actionButtons.forEach((button) => {
    const parent = button.parentElement;
    const helpText = parent.querySelector(".help-text");
    const nameStatus = parent.querySelector(".name-status b");
    helpText.textContent = allStatus[button.dataset.idStatus]["text"];

    if (button.dataset.idStatus > 4) {
        button.remove();
    } else {
        button.textContent = allStatus[button.dataset.idStatus]["action"];
        button.addEventListener("click", async () => {
            const resultData = await sendToServer({
                "server_type": "status_orders",
                "datetime_buy_orders": button.dataset.datetime,
            });
            if (resultData["status"] == "OK") {
                button.dataset.idStatus++;
                nameStatus.textContent = resultData["data"]["name_status"];
                helpText.textContent = allStatus[button.dataset.idStatus]["text"];
                if (button.dataset.idStatus > 4) {
                    button.remove();
                } else {
                    button.textContent = allStatus[button.dataset.idStatus]["action"];
                }
            } else {
                showModal("Не удалось");
            }
        });
    }
});
