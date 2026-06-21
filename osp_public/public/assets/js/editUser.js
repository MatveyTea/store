"use strict";

const form = document.querySelector(".form");
const inputs = form.querySelectorAll(".input");
const insertPlace = document.querySelector(".users");
insertPlace.querySelectorAll(".banned").forEach((button) => button.addEventListener("click", bannedAction));
insertPlace.querySelectorAll(".delete").forEach((button) => button.addEventListener("click", deleteAction));
insertPlace.querySelectorAll(".input[data-name='roles_id_users']").forEach((button) => button.addEventListener("change", () => changeRoles(button)));

form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const dataResult = await sendToServer(getSearchData(inputs));
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        insertPlace.innerHTML = "";
        insertPlace.insertAdjacentHTML("beforeend", dataResult["data"]);
        insertPlace.querySelectorAll(".banned").forEach((button) => button.addEventListener("click", bannedAction));
        insertPlace.querySelectorAll(".delete").forEach((button) => button.addEventListener("click", deleteAction));
        insertPlace.querySelectorAll(".input[data-name='roles_id_users']").forEach((button) => {
            setBasicSettingInput([button], insertPlace.querySelector(`div.form:has(.input[data-name='roles_id_users'][data-id='${button.dataset.id}'])`))
            button.addEventListener("change", () => changeRoles(button));
        });
    } else if (dataResult["status"] == "NOTFOUND") {
        insertPlace.innerHTML = "<p class='notfound'>Ничего не найдено</p>";
    } else {
        showModal("Не удалось выполнить запрос.");
    }
});

async function changeRoles(button) {
    const dataResult = await sendToServer({
        "server_type": "change_roles",
        "id_users": button.dataset.id,
        "roles_id_users": button.value
    });
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        showModal("Успешно выполнено.");
    } else {
        showModal("Не удалось выполнить запрос.");
    }
}

async function bannedAction() {
    toggleUser(this.parentElement, this.dataset.isBanned);
    const dataResult = await sendToServer({
        "server_type": "banned_users",
        "id_users": this.dataset.id,
        "is_banned_users": this.dataset.isBanned
    });
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        showModal("Успешно выполнено.");
    } else {
        toggleUser(this.parentElement, this.dataset.isBanned);
        showModal("Не удалось выполнить запрос.");
    }
}

async function deleteAction() {
    this.parentElement.classList.add("hidden");
    const dataResult = await sendToServer({
        "server_type": "delete_user",
        "id_users": this.dataset.id,
    });
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        this.parentElement.remove(); 
        showModal("Успешно выполнено.");
    } else {
        this.parentElement.classList.remove("hidden");
        showModal("Не удалось выполнить запрос.");
    }
}

function toggleUser(parent, isBanned) {
    if (isBanned == 0) {
        parent.querySelector(".user-status span").textContent = "Заблокирован";
        const button = parent.querySelector(".banned");
        button.textContent = "Разблокировать";
        button.dataset.isBanned = 1;
    } else {
        parent.querySelector(".user-status span").textContent = "Разблокирован";
        const button = parent.querySelector(".banned");
        button.textContent = "Заблокировать";
        button.dataset.isBanned = 0;
    }
    showModal("Успешно выполнено.");
}

function getSearchData(inputs) {
    let data = {
        "server_type": "search_users"
    }
    inputs.forEach((input) => {
        if (input.dataset.isInsertServer == 0) {
            data[input.dataset.name] = input.value;
        }
    });
    return data;
}