"use strict";

const form = document.querySelector(".form");
const inputs = form.querySelectorAll(".input");
const insertPlace = document.querySelector(".users");
insertPlace.querySelectorAll(".banned").forEach((button) => button.addEventListener("click", bannedAction));
insertPlace.querySelectorAll(".delete").forEach((button) => button.addEventListener("click", deleteAction));
insertPlace.querySelectorAll(".deliver").forEach((button) => button.addEventListener("click", deliverAction));
insertPlace.querySelectorAll(".support").forEach((button) => button.addEventListener("click", supportAction));

form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const dataResult = await sendToServer(getSearchData(inputs));
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        insertPlace.innerHTML = "";
        insertPlace.insertAdjacentHTML("beforeend", dataResult["data"]);
        insertPlace.querySelectorAll(".banned").forEach((button) => button.addEventListener("click", bannedAction));
        insertPlace.querySelectorAll(".delete").forEach((button) => button.addEventListener("click", deleteAction));
        insertPlace.querySelectorAll(".status").forEach((button) => button.addEventListener("click", deliverAction));
        insertPlace.querySelectorAll(".support").forEach((button) => button.addEventListener("click", supportAction))
    } else if (dataResult["status"] == "NOTFOUND") {
        insertPlace.innerHTML = "<p class='notfound'>Ничего не найдено</p>";
    } else {
        showModal("Не удалось выполнить запрос.");
    }
});

async function supportAction() {
    const dataResult = await sendToServer({
        "server_type": "support_users",
        "id_users": this.dataset.id
    });
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        if (this.dataset.idStatus == 2) {
            this.parentElement.querySelector(".user-role span").textContent = "Техподдержка";
            this.textContent = "Убрать из поддержки";
            this.dataset.idStatus = 4;
        } else {
            this.parentElement.querySelector(".user-role span").textContent = "Пользователь";
            this.textContent = "Сделать поддержкой";
            this.dataset.idStatus = 2;
        }
        showModal("Успешно.");
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
        showModal(this.dataset.isBanned == 0 ? "Разблокирован" : "Заблокирован");
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
        showModal("Пользователь удалён.");
    } else {
        this.parentElement.classList.remove("hidden");
        showModal("Не удалось выполнить запрос.");
    }
}

async function deliverAction() {
    const dataResult = await sendToServer({
        "server_type": "deliver_users",
        "id_users": this.dataset.id
    });
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        if (this.dataset.deliver == 3) {
            this.parentElement.querySelector(".user-role span").textContent = "Доставщик";
            this.textContent = "Убрать из доставщика";
            this.dataset.deliver = 2;
        } else {
            this.parentElement.querySelector(".user-role span").textContent = "Пользователь";
            this.textContent = "Сделать доставщиком";
            this.dataset.deliver = 3;
        }
        showModal("Успешно.");
    } else {
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
    showModal("Успешно.");
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