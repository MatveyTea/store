"use strict";

const deleteAll = document.querySelectorAll(".field .button.delete-all");
deleteAll.forEach((button) => {
    const form = document.querySelector(`.form:has(.button[data-id^="${button.dataset.id}"])`);
    button.addEventListener("click", async (event) => {
        event.preventDefault();
        form.classList.add("hidden");
        const dataResult = await sendToServer({
            "server_type": "delete_from_table",
            "table": button.dataset.table,
            "id": button.dataset.id
        });
        if (dataResult?.isValid == false) return;
        if (dataResult["status"] == "OK") {
            form.remove();
            showModal("Удалено");
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
        const dataResult = await sendToServer({
            "server_type": "delete_one_from_table",
            "id": button.dataset.idAttributes
        });
        if (dataResult?.isValid == false) return;
        if (dataResult["status"] == "OK") {
            additional.remove();
            showModal("Удалено");
        } else {
            showModal("Не удалось выполнить запрос")
            additional.classList.remove("hidden");
        }
    } else {
        additional.remove();
    }
}