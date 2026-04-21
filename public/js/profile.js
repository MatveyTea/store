"use strict";

const deleteAvatar = document.querySelector(".delete-avatar");
deleteAvatar?.addEventListener("click", async (event) => {
    event.preventDefault();
    const resultData = await sendToServer({
        "server_type": "delete_avatar"
    });
    if (resultData["status"] == "OK") {
        deleteAvatar.remove();
        document.querySelectorAll(".avatar").forEach((avatar) => avatar.src = resultData["data"]["src"]);
    } else {
        showModal("Не удалось удалить аватарку");
    }
});