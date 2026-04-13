"use strict";

const form = document.querySelector(".form");
const inputs = form.querySelectorAll(".input");
const insertPlace = document.querySelector(".users");
insertPlace.querySelectorAll(".banned").forEach((button) => button.addEventListener("click", bannedAction));
insertPlace.querySelectorAll(".delete").forEach((button) => button.addEventListener("click", deleteAction));
insertPlace.querySelectorAll(".deliver").forEach((button) => button.addEventListener("click", deliverAction));

form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const resultData = await sendToServer(getSearchData(inputs));
    if (resultData["status"] == "OK") {
        insertPlace.innerHTML = "";
        insertPlace.insertAdjacentHTML("beforeend", resultData["data"]);
        insertPlace.querySelectorAll(".banned").forEach((button) => button.addEventListener("click", bannedAction));
        insertPlace.querySelectorAll(".delete").forEach((button) => button.addEventListener("click", deleteAction));
        insertPlace.querySelectorAll(".status").forEach((button) => button.addEventListener("click", deliverAction));
    } else if (resultData["status"] == "NOTFOUND") {
        insertPlace.innerHTML = "<p class='notfound'>Ничего не найдено</p>";
    } else {
        showModal("Не удалось найти");
    }
});

async function bannedAction() {
    toggleUser(this.parentElement, this.dataset.isBanned);
    const resultData = await sendToServer({
        "server_type": "banned_users",
        "id_users": this.dataset.id,
        "is_banned_users": this.dataset.isBanned
    });
    if (resultData["status"] == "OK") {
        showModal(this.dataset.isBanned == 0 ? "Разблокирован" : "Заблокирован");
    } else {
        toggleUser(this.parentElement, this.dataset.isBanned);
        showModal(`Не удалось ${this.dataset.isBanned == 1 ? "разблокировать" : "заблокировать"}`);
    }
}

async function deleteAction() {
    this.parentElement.classList.add("hidden");
    const resultData = await sendToServer({
        "server_type": "delete_user",
        "id_users": this.dataset.id,
    });
    if (resultData["status"] == "OK") {
        this.parentElement.remove(); 
        showModal("Пользователь удалён");
    } else {
        this.parentElement.classList.remove("hidden");
        showModal("Не удалось удалить");
    }
}

async function deliverAction() {
    const resultData = await sendToServer({
        "server_type": "deliver_users",
        "id_users": this.dataset.id
    });
    if (resultData["status"] == "OK") {
        if (this.dataset.deliver == 0) {
            this.parentElement.querySelector(".user-role").textContent = "Доставщик";
            this.textContent = "Убрать из доставщика";
            this.dataset.deliver = 1;
        } else {
            this.parentElement.querySelector(".user-role").textContent = "Пользователь";
            this.textContent = "Сделать доставщиком";
            this.dataset.deliver = 0;
        }
    } else {
        showModal("Не удалось");
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