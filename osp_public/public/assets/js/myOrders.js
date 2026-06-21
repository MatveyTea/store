"use strict";

const allStatus = {
    3: {
        "action": "Собрано",
        "text": ", соберите товар"
    },
    4: {
        "action": "Доставлено",
        "text": ", доставьте товар"
    },
    5: {
        "action": null,
        "text": ", ожидайте пока покупатель получит товар"
    }
};

const actionButtons = document.querySelectorAll(".button.action[data-id-status]");
actionButtons.forEach((button) => {
    const parent = button.parentElement;
    const helpText = parent.querySelector(".help-text");
    const nameStatus = parent.querySelector(".name-status");
    nameStatus.innerHTML += allStatus[button.dataset.idStatus]["text"];

    if (button.dataset.idStatus > 4) {
        button.remove();
    } else {
        button.textContent = allStatus[button.dataset.idStatus]["action"];
        button.addEventListener("click", async () => {
            const dataResult = await sendToServer({
                "server_type": "status_orders",
                "id_orders": button.dataset.idOrder,
            });
            if (dataResult?.isValid == false) return;
            if (dataResult["status"] == "OK") {
                button.dataset.idStatus++;
                nameStatus.textContent = `Статус: ${dataResult["data"]["name_status"]}${allStatus[button.dataset.idStatus]["text"]}`;
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
