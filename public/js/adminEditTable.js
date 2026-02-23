"use strict";

const buttons = document.querySelectorAll(".field .button:not(.input)");
buttons.forEach((button) => {
    const form = document.querySelector(`.form:has(.button[data-id^="${button.dataset.id}"])`);
    button.addEventListener("click", async (event) => {
        event.preventDefault();
        form.classList.add("hidden");
        const result = await fetch("server.php", {
            "method": "POST",
            "body": JSON.stringify({
                "server_type": "delete_from_table",
                "table": button.dataset.table,
                "id": button.dataset.id
            }),
            "headers": {
                "Content-type": "application/json"
            }
        });
        const resultData = await result.json();
        if (resultData["status"] == "OK") {
            form.remove();
        } else {
            showModal("Не удалось выполнить запрос")
            form.classList.remove("hidden");
        }
    })
});