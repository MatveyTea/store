"use strict";
const items = document.querySelectorAll("div[data-id]");
const isAuth = items[0]?.querySelector("button.basket");
if (isAuth) {
    items.forEach((item) => clickableItem(item));
}

function clickableItem(item) {
    const basketButton = item.querySelector("button.basket");
    const counter = item.querySelector("span");
    const minusButton = counter.querySelector("button.minus");
    const counterText = counter.querySelector("p b");
    const plusButton = counter.querySelector("button.plus");

    basketButton.addEventListener("click", async () => {
        changeButtonBasket(basketButton.dataset.type == "add", counter, counterText, basketButton);
        await sendItem(item, counter, counterText, basketButton);
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

let maxScroll = document.documentElement.offsetHeight - window.innerHeight - items[0]?.clientHeight;
const sectionItems = document.querySelector("section");
addEventListener("scroll", async () => {
    if (scrollY >= maxScroll) {
        const lastItemsId = parseInt(document.querySelector("div[data-id]:last-of-type")?.dataset?.id ?? 0);
        const result = await fetch("server.php", {
            "method": "POST",
            "headers": {
                "Content-Type": "application/json"
            },
            "body": JSON.stringify({
                "server_type": "items",
                "offset": lastItemsId
            })
        });
        const dataResult = await result.json();

        if (dataResult["status"] == "OK") {
            const tempContainer = document.createElement("div");
            tempContainer.innerHTML = dataResult["items"];
            Array.from(tempContainer.children).forEach((item) => {
                if (isAuth) {
                    clickableItem(item);
                }
                sectionItems.appendChild(item);
            });
        }
        maxScroll = document.documentElement.offsetHeight - window.innerHeight - items[0]?.clientHeight;
    }
    console.log(scrollY, maxScroll);
});