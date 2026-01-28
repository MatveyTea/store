"use strict";

const additional = document.querySelector("div.additional.hidden");
const additionalButton = document.querySelector("button.additional");
const form = document.querySelector("form");
const insertPlace = form.querySelector(".form input.button").parentElement;
let count = additional.dataset.count;

additionalButton.addEventListener("click", (event) => {
    event.preventDefault();
    count++;
    const clone = additional.cloneNode(true);
    clone.classList.remove("hidden");

    const [firstLabel, lastLabel] = clone.querySelectorAll(".field .label");
    firstLabel.textContent = `Имя ${count}`

    lastLabel.textContent = `Описание ${count}`

    const [firstInput, lastInput] = clone.querySelectorAll(".field .input");
    firstInput.name = `items_properties_name_${count}`;
    lastInput.name = `items_properties_description_${count}`;

    insertPlace.insertAdjacentElement("beforebegin", clone);
});


form.addEventListener("submit", () => {
    let additional = [];
    const name = form.querySelectorAll(".input[name^='items_properties_name']");
    const description = form.querySelectorAll(".input[name^='items_properties_description']");

    for (let i = 0; i < name.length; i++) {
        name[i].removeAttribute("name");
        description[i].removeAttribute("name");
        additional.push({
            "name": name[i].value,
            "description": description[i].value
        });
    }
    document.getElementById("items_properties").value = JSON.stringify(additional);
});