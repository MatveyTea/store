"use strict";

const deleteButton = document.querySelector(".button.delete");
deleteButton.addEventListener("click", async (event) => {
    event.preventDefault()
    const dataResult = await sendToServer({
        "server_type": "delete_items",
        "id_item": window.location.search.split("=")[1]
    });
    if (dataResult["status"] == "OK") {
        window.location.href = "/";
    } else {
        showModal("Не удалось удалить товар");
    }
});