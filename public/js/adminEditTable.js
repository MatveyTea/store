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


document.querySelectorAll(".form").forEach((form) => {
    form.addEventListener("submit", (event) => {
        const ids = form.querySelectorAll(".input[data-name='id_attributes']");
        const values = form.querySelectorAll(".input[data-name='value_attributes']");
        const result = [];
        for (let i = 0; i < ids.length; i++) {
            let array = {};
            if (ids[i] && ids[i].value != "") {
                array["id_attributes"] = ids[i].value;
            }
            if (values[i].value != "" && values[i].dataset.isInsertServer == 0) {
                array["value_attributes"] = values[i].value;
            }
            result.push(array);
        }
        if (result.length > 0) {
            const attributes = form.querySelector(".input[data-name='attributes']");
            attributes.value = JSON.stringify(result);
            attributes.setAttribute("name", attributes.dataset.name);
        }
    });
});