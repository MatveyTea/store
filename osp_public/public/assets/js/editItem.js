"use strict";

const deleteButton = document.querySelector(".delete-item");
deleteButton.addEventListener("click", async (event) => {
    event.preventDefault();
    const url = new URL(document.location);
    const dataResult = await sendToServer({
        "server_type": "delete_items",
        "id_item": url.searchParams.get("id_item")
    });
    if (dataResult["status"] == "OK") {
        window.location.href = "/";
    } else {
        showModal("Не удалось удалить товар");
    }
});