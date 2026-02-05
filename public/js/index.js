"use strict";
async function getSearchItems() {
    const result = await fetch("server.php", {
        "method": "POST",
        "headers": {
            "Content-Type": "application/json"
        },
        "body": JSON.stringify({
            "server_type": "search_items",
            "offset": isResetSearch ? 0 : offset,
            "name_search_items": searchSection.querySelector("input[id=name_search_items]").value,
            "min_cost_items": searchSection.querySelector("input[id=min_cost_items]").value,
            "max_cost_items": searchSection.querySelector("input[id=max_cost_items]").value
        })
    });
    const dataResult = await result.json();
    if (dataResult["status"] == "OK") {
        const tempContainer = document.createElement("div");
        tempContainer.innerHTML = dataResult["items"];
        if (isResetSearch) {
            itemsSection.innerHTML = "";
            offset = countGetMaxItems;
            isResetSearch = false;
        }
        Array.from(tempContainer.children).forEach((item) => {
            if (isAuth) {
                clickableItem(item);
            }
            itemsSection.appendChild(item);
            offset++;
        });
    } else if (dataResult["status"] == "NOTFOUND") {
        itemsSection.innerHTML = "<p class='notfound'>Ничего не найдено<p>";
    }
    maxScroll = document.documentElement.offsetHeight - window.innerHeight - items[0]?.clientHeight;
    setTimeout(() => {
        isCanGet = true;
    }, 500);
}

const countGetMaxItems = 50;
let isCanGet = true;
let maxScroll = document.documentElement.offsetHeight - window.innerHeight;
let offset = parseInt(document.querySelector("section").children.length ?? 0);
let isResetSearch = false; 
const itemsSection = document.querySelector(".items");
addEventListener("scroll", async () => {
    if (scrollY >= maxScroll - items[0]?.clientHeight && isCanGet) {
        isCanGet = false;
        getSearchItems(false);
    }
});

const searchSection = document.querySelector(".search");
searchSection.addEventListener("submit", async(event) => {
    event.preventDefault();
    isResetSearch = true;
    getSearchItems(true);
});

const items = document.querySelectorAll("span[data-id]");
const isAuth = items[0]?.querySelector("button.basket");
if (isAuth) {
    items.forEach((item) => clickableItem(item));
}