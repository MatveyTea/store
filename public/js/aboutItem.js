"use strict";

const addComment = document.querySelector(".add-comment");

if (addComment) {
    const textComment = addComment.querySelector("textarea.textarea");
    const ratingComments = Array.from(addComment.querySelectorAll("svg"));
    const commentButton = addComment.querySelector(".button");

    ratingComments.forEach((svg) => {
        const currentIndexStar = ratingComments.indexOf(svg);
        svg.addEventListener("click", () => selectStar(ratingComments, currentIndexStar));
    });


    commentButton?.addEventListener("click", async (event) => {
        event.preventDefault();
        const resultData = await sendToServer({
            "server_type": "add_comment",
            "id_items": addComment.dataset?.id,
            "text_comments": textComment.value,
            "rating_comments": ratingComments.reduce((result, svg) => result + svg.classList.contains("active"), 0)
        });
        if (resultData["status"] == "OK") {
            document.querySelector(".form").reset();
            textComment.textContent = "";
            selectStar(ratingComments, -1);
            addComment.insertAdjacentHTML("afterend", resultData["data"]["comments"]);
            document.querySelector(".about h2 b").innerHTML = resultData["data"]["rating"];
            document.querySelector(".comment:first-of-type .button").addEventListener("click", commentAction);
        } else {
            ratingComments[0].parentElement.click();
            showModal("Не удалось добавить комментарий");
        }
    });
}

document.querySelectorAll(".items .item, .about .basket").forEach((item) => clickableItem(item));

document.querySelectorAll("div .button[data-id]").forEach((button) => button.addEventListener("click", commentAction));

const idItem = window.location.search.split("?id_item=")[1];
addEventListener("DOMContentLoaded", async () => {
    if (performance.getEntriesByType("navigation")[0].type != "reload") {
        await sendToServer({
            "server_type": "add_view",
            "id_item": idItem
        });
    }
});

function selectStar(allStars, currentIndexStar) {
    allStars.forEach((star, index) => star.classList.toggle("active", index <= currentIndexStar));
}

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