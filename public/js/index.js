"use strict";
const items = document.querySelectorAll("div[data-id]");
const isAuth = items[0]?.querySelector("button.basket");
if (isAuth) {
    items.forEach((item) => clickableItem(item));
}

function clickableItem(item) {
    const basketButton = item.querySelector("button.basket");
    const deleteButton = item.querySelector("button.delete");
    const counter = item.querySelector("span");
    const minusButton = counter.querySelector("button.minus");
    const counterText = counter.querySelector("p b");
    const plusButton = counter.querySelector("button.plus");

    basketButton.addEventListener("click", async () => {
        changeButtonBasket(basketButton.dataset.type == "add", counter, counterText, basketButton);
        await sendItem(item, counter, counterText, basketButton);
    });
    deleteButton?.addEventListener("click", async() => {
        const dataItem = {
            "server_type": "delete_items",
            "id_item": item.dataset.id
        };
        item.classList.add("hidden");
        const result = await fetch("server.php", {
            "method": "POST",
            "headers": {
                "Content-Type": "application/json"
            },
            "body": JSON.stringify(dataItem)
        });
        const dataResult = await result.json();

        if (dataResult["status"] == "OK") {
            item.remove();
        } else {
            item.classList.remove("hidden");
        }
    });

    minusButton.addEventListener("click", async () => {
        if (parseInt(counterText.textContent) <= 1) {
            changeButtonBasket(false, counter, counterText, basketButton);
        } else {
            counterText.textContent = parseInt(counterText.textContent) - 1;
        }
        await sendItem(item, counter, counterText, basketButton);
    });
    plusButton.addEventListener("click", async () => {
        if (item.dataset.count > parseInt(counterText.textContent)) {
            counterText.textContent = parseInt(counterText.textContent) + 1;
            await sendItem(item, counter, counterText, basketButton);
        }
    });
}

function changeButtonBasket(status, counter, counterText, basketButton) {
    if (status == null || counterText == null || counter == null || basketButton == null) return;

    if (status === true) {
        counterText.textContent = 1;
        counter.classList.remove("hidden");
        basketButton.textContent = "Убрать из корзины";
        basketButton.dataset.type = "remove";
    } else {
        counterText.textContent = 0;
        counter.classList.add("hidden");
        basketButton.textContent = "Добавить в корзину";
        basketButton.dataset.type = "add";
    }
}

async function sendItem(item, counter, counterText, basketButton) {
    const dataItem = {
        "server_type": "basket",
        "id_item": item.dataset.id,
        "count_item": parseInt(counterText.textContent),
        "action_item": basketButton.dataset.type == "add" ? "remove" : "add"
    };
    const result = await fetch("server.php", {
        "method": "POST",
        "headers": {
            "Content-Type": "application/json"
        },
        "body": JSON.stringify(dataItem)
    });
    const dataResult = await result.json();

    if (dataResult["status"] != "OK") {
        changeButtonBasket(basketButton.dataset.type == "add", counter, counterText, basketButton);
    }
}

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