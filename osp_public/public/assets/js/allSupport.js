"use strict";

const acceptSupportButton = document.querySelectorAll(".button[data-id-talks]");
acceptSupportButton.forEach((button) => {
    button.addEventListener("click", async () => {
        const dataResult = await sendToServer({
            "server_type": "accept_support",
            "id_talks": button.dataset.idTalks
        });
        if (dataResult?.isValid == false) return;
        if (dataResult["status"] == "OK") {
            window.location.href = "/user/support.php";
        } else {
            showModal("Не удалось выполнить запрос.");
        }
    });
});