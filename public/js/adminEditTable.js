"use strict";

const deleteAll = document.querySelectorAll(".field .button.delete-all");
deleteAll.forEach((button) => {
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

const addOneTemplate = document.querySelector("#add-one-template");
const addOne = document.querySelectorAll(".field .button.add-one");
addOne?.forEach((button) => {
    const insertPlace = button.parentElement;
    button.addEventListener("click", (event) => {
        event.preventDefault();
        const additional = addOneTemplate.content.firstElementChild.cloneNode(true);
        insertPlace.insertAdjacentElement("beforebegin", additional);
        additional.querySelector(".delete-one").addEventListener("click", (event) => {
            event.preventDefault();
            deleteOne(additional.querySelector(".delete-one"), additional);
        });
        setBasicSettingInput(additional.querySelectorAll(".input"), insertPlace.parentElement);
    });
});

const deleteOneButtons = document.querySelectorAll(".field .button.delete-one");
deleteOneButtons?.forEach((button) => {
    button.addEventListener("click", (event) => {
        event.preventDefault();
        deleteOne(button, button.parentElement.parentElement);
    });
});

async function deleteOne(button, additional) {
    if (button.hasAttribute("data-id-attributes")) {
        additional.classList.add("hidden");
        const resultData = sendToServer({
            "server_type": "delete_one_from_table",
            "id": button.dataset.idAttributes
        });
        if (resultData["status"] == "OK") {
            additional.remove();
            current.remove();
            showModal("Удалено");
        } else {
            showModal("Не удалось выполнить запрос")
            additional.classList.remove("hidden");
        }
    } else {
        additional.remove();
    }
}

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