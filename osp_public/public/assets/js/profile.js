"use strict";

const deleteAvatar = document.querySelector(".delete-avatar");
deleteAvatar?.addEventListener("click", async (event) => {
    event.preventDefault();
    const dataResult = await sendToServer({
        "server_type": "delete_avatar"
    });
    if (dataResult?.isValid == false) return;
    if (dataResult["status"] == "OK") {
        deleteAvatar.remove();
        document.querySelectorAll(".avatar").forEach((avatar) => avatar.src = dataResult["data"]["src"]);
    } else {
        showModal("Не удалось удалить аватар");
    }
});

const removeAvatar = document.querySelector(".remove-avatar");
const avatarInput = document.querySelector(".input[data-name='avatar_users']");
const avatarImg = document.querySelector(".form .avatar");
let canRemoveClass = true;
avatarInput.addEventListener("change", () => {
    if (!canRemoveClass) return;
    removeAvatar.classList.remove("hidden");
});
removeAvatar.addEventListener("click", (event) => {
    event.preventDefault();
    removeAvatar.classList.add("hidden");
    avatarImg.src = avatarImg.dataset.baseSrc;
    avatarInput.value = "";
    canRemoveClass = false;
    avatarInput.dispatchEvent(new Event("change"));
    canRemoveClass = true;
});

const passwordField = document.querySelector(".field:has(.input[data-name='password_users'])");
const rePasswordField = document.querySelector(".field:has(.input[data-name='re_password_users'])");
const passwordInput = passwordField.querySelector(".input");
const rePasswordInput = rePasswordField.querySelector(".input");
const passwordAppear = document.querySelector(".password-appear");
passwordAppear.addEventListener("click", (event) => {
    event.preventDefault();
    if (passwordAppear.classList.contains("close")) {
        passwordAppear.textContent = "Отмена пароля";
    } else {
        passwordAppear.textContent = "Сменить пароль";
        passwordInput.value = "";
        rePasswordInput.value = "";
        passwordInput.dispatchEvent(new Event("change"));
        rePasswordInput.dispatchEvent(new Event("change"));
    }
    passwordField.classList.toggle("close");
    rePasswordField.classList.toggle("close");
    passwordAppear.classList.toggle("close");
});