"use strict";
async function getSearchItems() {
    const data = formInputs.reduce((result, input) => {
        if (input.dataset.name != "attributes_search" && (input.type == "checkbox" && input.checked || input.type !== "checkbox" && input.value != "")) {
            result[input.dataset.name] = input.value;
        }
        return result;
    }, {
        "server_type": "search_items",
        "offset_search_items": isResetSearch ? 0 : offset
    });

    const attributes = [];
    document.querySelectorAll(".input[data-name='attributes_search']").forEach((input) => {
        if (input.checked) {
            attributes.push(input.value);
        }
    });
    if (attributes.length > 0) {
        data["attributes_search"] = JSON.stringify(attributes);
    }

    const types = [];
    document.querySelectorAll(".input[data-name='items_type_id_search_items']").forEach((input) => {
        if (input.checked) {
            types.push(input.value);
        }
    });
    if (types.length > 0) {
        data["items_type_id_search_items"] = JSON.stringify(types);
    }

    const dataResult = await sendToServer(data);

    if (dataResult["status"] == "OK") {
        if (isResetSearch) {
            itemsSection.innerHTML = "";
            offset = countGetMaxItems;
            isResetSearch = false;
        }
        const tempContainer = document.createElement("div");
        tempContainer.innerHTML = dataResult["data"];
        Array.from(tempContainer.children).forEach((item) => {
            if (isAuth) {
                clickableItem(item);
            }
            itemsSection.appendChild(item);
            offset++;
        });
    } else if (dataResult["status"] == "NOTFOUND" && isResetSearch) {
        itemsSection.innerHTML = "<p class='notfound'>Ничего не найдено</p>";
    } else if (dataResult["status"] == "FAIL") {
        showModal("Не удалось выполнить запрос");
    }

    maxScroll = document.body.scrollHeight - window.innerHeight * 2;
    setTimeout(() => {
        isCanGet = true;
    }, 500);
}

const itemsSection = document.querySelector(".items");
const items = itemsSection.querySelectorAll("span[data-id]");
const isAuth = items[0]?.querySelector("button.basket");

const countGetMaxItems = 50;
let isCanGet = true;
let maxScroll = document.body.scrollHeight - window.innerHeight * 2;
let offset = items.length ?? 0;
let isResetSearch = false;

const form = document.querySelector(".form");
const formInputs = Array.from(form.querySelectorAll(".input[data-name]"));
const searchButton = form.querySelector(".button");

if (isAuth) {
    items.forEach((item) => clickableItem(item));
}

window.addEventListener("scroll", async () => {
    if (isCanGet && scrollY >= maxScroll) {
        isCanGet = false;
        getSearchItems();
    }
});

searchButton.addEventListener("click", (event) => {
    event.preventDefault();
    isResetSearch = true;
    getSearchItems();
});