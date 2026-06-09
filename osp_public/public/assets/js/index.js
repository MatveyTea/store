"use strict";
async function getSearchItems() {
    const data = formInputs.reduce((result, input) => {
        if (input.dataset.name != "id_search_attributes" && (input.type == "checkbox" && input.checked || input.type !== "checkbox" && input.value != "")) {
            result[input.dataset.name] = input.value;
        }
        return result;
    }, {
        "server_type": "search_items",
        "offset_search_items": isResetSearch ? 0 : offset
    });

    const attributes = Array.from(document.querySelectorAll(".input[data-name='id_search_attributes']:checked")).map((checkbox) => checkbox.value);
    if (attributes.length > 0) {
        data["id_search_attributes"] = JSON.stringify(attributes);
    }

    const types = Array.from(document.querySelectorAll(".input[data-name='items_type_id_search_items']:checked")).map((checkbox) => checkbox.value);
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
        notFound.classList.add("hidden");
        title.classList.remove("hidden");
    } else if (dataResult["status"] == "NOTFOUND" && isResetSearch) {
        itemsSection.innerHTML = "";
        notFound.classList.remove("hidden");
        title.classList.add("hidden");
    } else if (dataResult["status"] == "FAIL") {
        showModal("Не удалось выполнить запрос");
    }

    maxScroll = document.body.scrollHeight - window.innerHeight * 2;
    setTimeout(() => isCanGet = true, 100);
}

function uploadItems() {
    if (items[0] == null) return;
    const itemsSectionRect = itemsSection.getBoundingClientRect();
    const itemRect = items[0].getBoundingClientRect();
    const distance = footer.getBoundingClientRect().top - itemsSectionRect.bottom;
    const countRow = distance / itemRect.height;
    const countInRow = parseInt(itemsSectionRect.width / itemRect.width);
    const countTotalItems = countRow * countInRow;
    const countRequest = Math.ceil(countTotalItems / countGetMaxItems);
    for (let i = 1; i < countRequest; i++) {
        isCanGet = false;
        getSearchItems();
    }
    maxScroll = document.body.scrollHeight - window.innerHeight * 2;
}

const itemsSection = document.querySelector(".items");
const notFound = document.querySelector(".items-container .notfound");
const title = document.querySelector(".items-container .title");
const items = itemsSection.querySelectorAll(".item");
const isAuth = items[0]?.querySelector(".basket");

const countGetMaxItems = 50;
let isCanGet = true;
let maxScroll = 0;
let offset = items.length ?? 0;
let isResetSearch = false;

const form = document.querySelector(".form");
const formInputs = Array.from(form.querySelectorAll(".input[data-name]"));
const searchButton = form.querySelector(".button");
const footer = document.querySelector("footer");

const url = new URL(document.location);
const idType = url.searchParams.get("items_type_id_items");
url.searchParams.delete("items_type_id_items");
window.history.pushState({}, "", url.toString());
if (idType != null) {
    const needInputItemsType = form.querySelector(`.input[data-name='items_type_id_search_items'][value='${idType}']`);
    needInputItemsType.checked = true;
    needInputItemsType.dispatchEvent(new Event("change"));
}


if (isAuth) {
    items.forEach((item) => clickableItem(item));
}

window.addEventListener("DOMContentLoaded", uploadItems);

let timerId = null;
window.addEventListener("resize", () => {
    clearTimeout(timerId);
    timerId = setTimeout(uploadItems, 200);
});

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

const formWrapper = document.querySelector(".form-wrapper");
const formSwitches = formWrapper.querySelector(".form-switches");
const formSwitchesImg = formWrapper.querySelector(".form-switches img");
const formAppear = formWrapper.querySelector(".form-appear");
formSwitches.addEventListener("click", () => {
    formSwitchesImg.classList.toggle("close");
    formAppear.classList.toggle("close");
});