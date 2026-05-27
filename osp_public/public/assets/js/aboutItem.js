"use strict";

const addComment = document.querySelector(".add-comment");
const commentsSection = document.querySelector(".comments");
const commentNotFound = commentsSection.querySelector(".notfound");
const commentTitle = commentsSection.querySelector(".title");

if (addComment) {
    const ratingItem = document.querySelector(".about .rating");
    const textComment = addComment.querySelector("textarea.textarea");
    const ratingComments = Array.from(addComment.querySelectorAll("svg"));
    const commentButton = addComment.querySelector(".button[name='submit_button']");

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
            commentTitle.insertAdjacentHTML("afterend", resultData["data"]["comments"]);
            ratingItem.innerHTML = resultData["data"]["rating"];
            document.querySelector(".comment .button").addEventListener("click", deleteComment);
            commentTitle.classList.remove("hidden");
            commentNotFound.classList.add("hidden");
        } else {
            ratingComments[0].parentElement.click();
            showModal("Не удалось добавить комментарий");
        }
    });
}

const items = document.querySelectorAll(".items .item:has(.item-basket), .about .basket");
if (items != null) {
    items.forEach((item) => clickableItem(item));
}

document.querySelectorAll("div .button[data-id]")?.forEach((button) => button.addEventListener("click", deleteComment));

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

async function deleteComment() {
    const parent = document.querySelector(`div:has(.button[data-id='${this.dataset.id}'])`);
    if (commentsSection.children.length < 4) {
        commentTitle.classList.add("hidden");
        commentNotFound.classList.remove("hidden");
    }
    const resultData = await sendToServer({
        "server_type": "delete_comment",
        "id_comment": this.dataset.id
    });
    if (resultData["status"] == "OK") {
        parent.remove();
    } else {
        showModal("Не удалось удалить комментарий");
    }
}