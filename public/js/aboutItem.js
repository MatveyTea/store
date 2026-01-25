"use strict";

const addComment = document.querySelector(".add-comment");
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
        addComment.insertAdjacentHTML("afterend", resultData["data"]);
        document.querySelector(".about h1 b").innerHTML = resultData["rating"];
    } else {

    }
});