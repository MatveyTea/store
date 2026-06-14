"use strict";

const cache = {};
const talks = document.querySelector(".talks");
const chats = document.querySelector(".chats");
const allChat = chats.querySelectorAll(".chat");
allChat.forEach((chat) => chat.addEventListener("click", () => chatAction(chat)));
let lastId = null;
let currentActiveChat = null;

const startTalk = document.querySelector(".start-talk");
startTalk?.addEventListener("submit", async (event) => {
    event.preventDefault();
    const text = startTalk.querySelector(".input[data-name='text_supports']").value;
    const title = startTalk.querySelector(".input[data-name='title_talks']").value;
    const image = startTalk.querySelector(".input[data-name='image_supports']")?.files[0];
    let base64String = null;
    if (image != null) {
        base64String = await fileToBase64(image);
    }
    const dataResult = await sendToServer({
        "server_type": "start_talk",
        "text_supports": text,
        "image_supports": base64String,
        "title_talks": title
    });
    if (dataResult["status"] == "OK") {
        startTalk.reset();
        let tempContainer = document.createElement("div");
        tempContainer.insertAdjacentHTML("afterbegin", dataResult["data"]["message"]);
        const form = tempContainer.querySelector(".form");
        const inputs = form?.querySelectorAll(".form .input");
        setBasicSettingInput(inputs, form);
        talkAction(tempContainer.querySelector(".talk"));
        talks.innerHTML = "";
        cache[tempContainer.querySelector(".input[data-name='talks_id_supports']").value] = tempContainer.children[0];
        talks.appendChild(tempContainer.children[0]);
        chats.querySelector(".notfound")?.remove();
        tempContainer = document.createElement("div");
        tempContainer.insertAdjacentHTML("afterbegin", dataResult["data"]["chat"]);
        const chat = tempContainer.querySelector(".chat");
        chat.addEventListener("click", () => chatAction(chat));
        chats.prepend(tempContainer.children[0]);
    } else {
        showModal("Не удалось отправить сообщение.");
    }
});


setInterval(async () => {
    if (lastId == null) return;
    const dataResult = await sendToServer({
        "server_type": "ping_message",
        "id_supports": lastId
    });
    if (dataResult["status"] == "OK") {
        const messages = cache[currentActiveChat].querySelector(".messages");
        messages.insertAdjacentHTML("beforeend", dataResult["data"]["message"]);
        messages.scrollTop = messages.scrollHeight;
        lastId = messages.lastElementChild.dataset.idSupport;
    }
}, 5000);

async function chatAction(chat) {
    if (cache[chat.dataset.idTalks] == null) {
        const dataResult = await sendToServer({
            "server_type": "get_talk_html",
            "id_talks": chat.dataset.idTalks
        });
        if (dataResult["status"] == "OK") {
            const tempContainer = document.createElement("div");
            tempContainer.insertAdjacentHTML("afterbegin", dataResult["data"]["messages"]);
            const form = tempContainer.querySelector(".form");
            const inputs = form?.querySelectorAll(".input");
            setBasicSettingInput(inputs, form);
            talkAction(tempContainer.querySelector(".talk"));
            talks.innerHTML = "";
            cache[chat.dataset.idTalks] = tempContainer.children[0];
            talks.appendChild(tempContainer.children[0]);
        } else {
            showModal("Не удалось открыть чат.");
        }
    } else {
        talks.innerHTML = "";
        talks.appendChild(cache[chat.dataset.idTalks]);
    }
    lastId = cache[chat.dataset.idTalks].querySelector(".messages").lastElementChild.dataset.idSupport;
    currentActiveChat = chat.dataset.idTalks;
}

function talkAction(talk) {
    const form = talk.querySelector(".form");
    const messages = talk.querySelector(".messages");
    setTimeout(() => {
        messages.scrollTop = messages.scrollHeight;
    }, 3);
    form?.addEventListener("submit", async (event) => {
        event.preventDefault();
        const talkIds = form.querySelector(".input[data-name='talks_id_supports']").value;
        const text = form.querySelector(".input[data-name='text_supports']").value;
        const image = form.querySelector(".input[data-name='image_supports']")?.files[0];
        let base64String = null;
        if (image != null) {
            base64String = await fileToBase64(image);
        }

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
            lastId = cache[chat.dataset.idTalks].querySelector(".messages").lastElementChild.dataset.idSupport;
        } else {
            showModal("Не удалось отправить");
        }
    });

    const button = form?.querySelector(".end-talk");
    button?.addEventListener("click", (event) => entTalkAction(event, button, form));
}

async function entTalkAction(event, button, form) {
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

async function fileToBase64(file) {
    return await new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
    });
}