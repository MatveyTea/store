"use strict";

const addComment = document.querySelector(".add-comment");

if (addComment) {
    const textComment = addComment.querySelector("textarea.textarea");
    const ratingComment = addComment.querySelector("input.input");
    const commentButton = addComment.querySelector(".button");

    commentButton?.addEventListener("click", async (event) => {
        event.preventDefault();
        const result = await fetch("server.php", {
            "method": "POST",
            "headers": {
                "Content-type": "application/json"
            },
            "body": JSON.stringify({
                "server_type": "add_comment",
                "id_items": addComment.dataset?.id,
                "text_comments": textComment.value,
                "rating_comments": ratingComment.value
            })
        });
        const resultData = await result.json();
        if (resultData["status"] == "OK") {
            document.querySelector(".form").reset();
            textComment.textContent = "";
            ratingComment.textContent = "";
            addComment.insertAdjacentHTML("afterend", resultData["data"]["comments"]);
            document.querySelector(".about h2 b").innerHTML = resultData["data"]["rating"];
            document.querySelector(".comment:first-of-type .button").addEventListener("click", commentAction);
            showModal("Комментарий добавлен");
        } else {
            showModal("Не удалось добавить комментарий");
        }
    });

    document.querySelectorAll(".item").forEach((item) => clickableItem(item));

    document.querySelectorAll("div .button[data-id]").forEach((button) => button.addEventListener("click", commentAction));
}

const idItem = window.location.search.split("?id_item=")[1];
addEventListener("DOMContentLoaded", async () => {
    if (performance.getEntriesByType("navigation")[0].type != "reload") {
        await sendToServer({
            "server_type": "add_view",
            "id_item": idItem
        });
    }
});


async function commentAction() {
    const parent = document.querySelector(`div:has(.button[data-id='${this.dataset.id}'])`);
    parent.classList.add("hidden");
    const resultData = await sendToServer({
        "server_type": "delete_comment",
        "id_comment": this.dataset.id
    });
    if (resultData["status"] == "OK") {
        parent.remove();
    } else {
        showModal("Не удалось удалить комментарий");
        parent.classList.remove("hidden");
    }
}