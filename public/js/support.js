"use strict";

const startTalk = document.querySelector(".start-talk");
startTalk?.addEventListener("submit", async (event) => {
    event.preventDefault();
    const dataResult = await sendToServer({
        "server_type": "start_talk",
        "text_supports": startTalk.querySelector(".input[data-name='text_supports']").value,
        "image_supports": startTalk.querySelector(".input[data-name='image_supports']").files[0]?.name,
        "title_talks": startTalk.querySelector(".input[data-name='title_talks']").value
    });
    if (dataResult["status"] == "OK") {
        startTalk.reset();
        const tempContainer = document.createElement("div");
        tempContainer.insertAdjacentHTML("afterbegin", dataResult["data"]["message"]);
        const form = tempContainer.querySelector(".form");
        const inputs = form.querySelectorAll(".form .input");
        setBasicSettingInput(inputs, form);
        form.querySelector(".end-talk").addEventListener("click", (event) => entTalkAction(event, button, form));
        document.querySelector(".talk").insertAdjacentElement("beforebegin", tempContainer);
    } else {
        showModal("Не удалось отправить");
    }
});

const continueTalk = document.querySelectorAll(".continue-talk");
continueTalk.forEach((form) => {
    const messages = document.querySelector(".messages");
    messages.scrollTop = messages.scrollHeight;
    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const talkIds = form.querySelector(".input[data-name='talks_id_supports']").value;
        const text = form.querySelector(".input[data-name='text_supports']").value;
        const image = form.querySelector(".input[data-name='image_supports']").files[0];
        const fileToBase64 = (file) => new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = (error) => reject(error);
        });
        const base64String = await fileToBase64(image);
        const dataResult = await sendToServer({
            "server_type": "continue_talk",
            "talks_id_supports": talkIds,
            "text_supports": text,
            "image_supports": base64String
        });
        if (dataResult["status"] == "OK") {
            form.reset();
            messages.insertAdjacentHTML("beforeend", dataResult["data"]["message"]);
            messages.scrollTop = messages.scrollHeight;
        } else {
            showModal("Не удалось отправить");
        }
    });

    const button = form.querySelector(".end-talk");
    button?.addEventListener("click", (event) => entTalkAction(event, button, form));
});

async function entTalkAction(event,button, form) {
    event.preventDefault();
    const dataResult = await sendToServer({
        "server_type": "end_talk",
        "talks_id_supports": button.dataset.id,
    });
    if (dataResult["status"] == "OK") {
        form.remove();
    } else {
        showModal("Не удалось удалить");
    }
}