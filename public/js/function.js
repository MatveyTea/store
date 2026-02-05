"use strict";

function getValidationRules() {
    let result = [];
    const file = window.location.pathname == "/" ? "/" : window.location.pathname.split("/")[1];

    const rules = {
        "name_users": {
            "oldValue": null,
            "files": ["reg.php", "profile.php"],
            "required": file == "profile.php" ? false : true,
            "timer": null,
            "placeMsg": null,
            "length": 30,
            "pattern": function (input) {
                return /^[а-яёА-ЯЁ-]{1,30}$/u.test(input.value) ? false : "Введите только кириллические символы, 1-30 символов.";
            }
        },
        "email_users": {
            "oldValue": null,
            "files": ["reg.php", "auth.php"],
            "required": true,
            "timer": null,
            "length": 30,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[A-Za-z0-9._%+-]{1,50}@[A-Za-z0-9.-]{1,15}\.[A-Za-z]{1,15}$/.test(input.value) ? false : "Введите действительный email-адрес до 80 символов.";
            },
        },
        "password_users": {
            "oldValue": null,
            "files": ["reg.php", "auth.php", "profile.php"],
            "required": file == "profile.php" ? false : true,
            "timer": null,
            "length": 40,
            "placeMsg": null,
            "pattern": function (input) {
                if (!/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/.test(input.value)) {
                    return "Введите латинские символы, цифры или допустимые символы (@#$%^&*()_+-={}\:;\"'<>,.?\/), 1-40 символов.";
                }

                const rePassword = document.querySelector("input[name=re_password_users");
                if (rePassword) {
                    const rePasswordPlaceError = rePassword.parentElement.querySelector(".error");
                    if (input.value != rePassword.value && rePassword.value != "") {
                        rePasswordPlaceError.textContent = "Пароли должны совпадать!";
                        rePasswordPlaceError.classList.remove("hidden");
                        return "Пароли должны совпадать!";
                    } else if (rePassword.value == input.value && input.value != "") {
                        rePasswordPlaceError.textContent = "";
                        rePasswordPlaceError.classList.add("hidden");
                    }
                }
                return false;
            }
        },
        "re_password_users": {
            "oldValue": null,
            "files": ["reg.php", "profile.php"],
            "required": file == "profile.php" ? false : true,
            "timer": null,
            "length": 40,
            "placeMsg": null,
            "additionalMsg": function (input) {
                if (!/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/.test(input.value)) {
                    return /^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (@#$%^&*()_+-={}\:;\"'<>,.?\/), 1-40 символов.";
                }

                const password = document.querySelector("input[name=password_users");
                if (password) {
                    const passwordPlaceError = password.parentElement.querySelector(".error");
                    if (input.value != password.value && password.value != "") {
                        passwordPlaceError.textContent = "Пароли должны совпадать!";
                        passwordPlaceError.classList.remove("hidden");
                        return "Пароли должны совпадать!";
                    } else if (password.value == input.value && input.value != "") {
                        passwordPlaceError.textContent = "";
                        passwordPlaceError.classList.add("hidden");
                    }
                }
                return false;
            }
        },
        "id_items": {
            "oldValue": null,
            "files": ["adminEditItem.php"],
            "required": true,
            "timer": null,
            "length": 40,
            "placeMsg": null,
            "pattern": function (input) {
                return false;
            }
        },
        "name_items": {
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timer": null,
            "length": 40,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (-().,:\"'%), 1-40 символов.";
            }
        },
        "name_search_items": {
            "oldValue": null,
            "files": ["/", "index.php"],
            "required": false,
            "timer": null,
            "length": 40,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (-().,:\"'%), 1-40 символов.";
            }
        },
        "count_items": {
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timer": null,
            "length": 6,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "cost_items": {
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timer": null,
            "length": 6,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "min_cost_items": {
            "oldValue": null,
            "files": ["/", "index.php"],
            "required": false,
            "timer": null,
            "length": 6,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "max_cost_items": {
            "oldValue": null,
            "files": ["/", "index.php"],
            "required": false,
            "timer": null,
            "length": 6,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "image_items": {
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": false,
            "timer": null,
            "length": 1,
            "placeMsg": null,
            "pattern": function (input) {
                if (input.files.length > 0) {
                    let extension = input.files[0].name.split(".");
                    extension = extension[extension.length - 1];
                    if (input.files[0].size > 2_000_000) {
                        return "Размер файла не должен превышать 2МБ";
                    }

                    if (!["jpg", "png", "webp"].includes(extension)) {
                        return "Некорректный тип файла. Файл должен быть jpg, png или webp";
                    }

                    let reader = new FileReader();
                    reader.readAsDataURL(input.files[0]);
                    reader.addEventListener("load", (event) => {
                        if (event.target.result != null) {
                            let img = input.parentElement.querySelector("img");
                            img.classList.remove("hidden");
                            img.src = event.target.result;
                        } else {
                            return "Не удалось загрузить картинку";
                        }
                    });
                }
                return false;
            }
        },
        "avatar_users": {
            "oldValue": null,
            "files": ["profile.php"],
            "required": false,
            "timer": null,
            "length": 1,
            "placeMsg": null,
            "pattern": function (input) {
                if (input.files.length > 0) {
                    let extension = input.files[0].name.split(".");
                    extension = extension[extension.length - 1];
                    if (input.files[0].size > 2_000_000) {
                        return "Размер файла не должен превышать 2МБ";
                    }
                    if (!["jpg", "png", "webp"].includes(extension)) {
                        return "Некорректный тип файла. Файл должен быть jpg, png или webp";
                    }
                    let reader = new FileReader();
                    reader.readAsDataURL(input.files[0]);
                    reader.addEventListener("load", (event) => {
                        if (event.target.result != null) {
                            let img = input.parentElement.querySelector("img");
                            img.classList.remove("hidden");
                            img.src = event.target.result;
                        } else {
                            return "Не удалось загрузить картинку";
                        }
                    });
                }
                return false;
            }
        },
        "text_comments": {
            "oldValue": null,
            "files": ["aboutItem.php"],
            "required": false,
            "timer": null,
            "length": 255,
            "placeMsg": null,
            "pattern": function (input) {
                return false; ///^.{1-255}$/.test(input.value) ? false : "Введите текст до 255 символов";
            }
        },
        "rating_comments": {
            "oldValue": null,
            "files": ["aboutItem.php"],
            "required": true,
            "timer": null,
            "length": 255,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[1-5]$/.test(input.value) ? false : "Введите число от 1 до 5";
            }
        },
        "items_properties": {
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": false,
            "timer": null,
            "length": 255,
            "placeMsg": null,
            "pattern": function (input) {
                return false;
            }
        },
        "id_items_properties": {
            "oldValue": null,
            "files": ["adminEditItem.php"],
            "required": false,
            "timer": null,
            "length": 255,
            "placeMsg": null,
            "pattern": function (input) {
                return false;
            }
        },
        "properties_id_items_properties": {
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timer": null,
            "length": 255,
            "placeMsg": null,
            "pattern": function (input) {
                return input.selectedIndex > 0 ? false : "Выберите элемент";
            }
        },
        "description_items_properties": {
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timer": null,
            "length": 255,
            "placeMsg": null,
            "pattern": function (input) {
                return /^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (-().,:\"'%), 1-40 символов.";
            }
        },
        "items_type_id_items": {
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timer": null,
            "length": 255,
            "placeMsg": null,
            "pattern": function (input) {
                return input.selectedIndex > 0 ? false : "Выберите элемент";
            }
        }
    };


    for (const key in rules) {
        if (Array.from(rules[key]["files"]).includes(file)) {
            result[key] = rules[key];
        }
    }
    return result;
}

function checkInput(input, rule) {
    let textMessage = "";
    let isCorrect = true;
    const { required, pattern, placeMsg, oldValue } = rule;

    if (pattern != null && (required || (!required && input.value != ""))) {
        const result = pattern(input);
        if (result !== false) {
            textMessage = result;
            isCorrect = false;
        }
    }

    if (textMessage == "") {
        rule.timer = setTimeout(() => {
            clearTimeout(rule.timer);
            placeMsg[input.dataset.uid].textContent = textMessage;
        }, 300);
    } else {
        placeMsg[input.dataset.uid].textContent = textMessage;
    }

    placeMsg[input.dataset.uid].classList.toggle("hidden", isCorrect);

    if (isCorrect && (oldValue[input.dataset.uid] != input.value || (input.type == "file" && input?.files?.length > 0))) {
        input.setAttribute("name", rule.name);
    } else {
        input.removeAttribute("name");
    }

    return isCorrect;
}


function setBasicSettingInput(inputs, form) {
    const validatedRules = getValidationRules();
    if (validatedRules == []) return;

    Array.from(inputs).forEach((input) => {
        input.dataset.uid = `${input.name}_${countInput++}`;
        const id = input.name;

        // console.log(id, input);
        const rule = validatedRules[id];
        rule.name = id; // переписать в Json
        rule.oldValue = {
            [input.dataset.uid]: input.value
        };
        const placeMsg = form.querySelector(`.field:has(.input[data-uid='${input.dataset.uid}']) p.error:not(.server-error)`);
        placeMsg.toggleAttribute("hidden", placeMsg.textContent == "");
        rule.placeMsg = {
            [input.dataset.uid]: placeMsg
        };

        if (rule.required) {
            const label = form.querySelector(`.field:not(.additional):has(.input[name=${id}]) .label`);
            label.innerHTML += "<b>*</b>";
        }

        if (rule.length != null) {
            input.setAttribute("maxlength", rule.length);
        }

        checkInput(input, rule);

        input.addEventListener("change", () => checkInput(input, rule));
    });
}

function setValidatedForm(form) {
    if (form == null) return;

    const inputs = form.querySelectorAll(".input:not(input[type=submit])");
    setBasicSettingInput(inputs, form);

    if (["/adminEditItem.php", "/adminAddItem.php"].includes(window.location.pathname)) {
        setAdditional(form);
    }

    form.addEventListener("submit", (event) => {
        // let hasUpdate = false;
        // let hasError = false;
        // Array.from(inputs).forEach((input) => {
        //     let isCorrect = checkInput(input,  validatedRules[input.name]);
        //     if (!isCorrect) {
        //         hasError = true;
        //     } else if (isCorrect && input?.name){
        //         hasUpdate = true;
        //     }
        // });
        //if (hasError || !hasUpdate) event.preventDefault();
    });
}

function setAdditional(form) {
    const additional = document.querySelector("div.additional.hidden");
    const additionalButton = document.querySelector("button.additional");
    const insertPlace = form.querySelector("input.button").parentElement;
    let count = additional.dataset.count;

    additionalButton?.addEventListener("click", (event) => {
        event.preventDefault();
        count++;
        const clone = additional.cloneNode(true);
        clone.classList.remove("hidden");
        clone.removeAttribute("data-count");

        const [idLabel, nameLabel, descriptionLabel] = clone.querySelectorAll(".field .label");
        idLabel.textContent = `ID ${count}`;
        nameLabel.textContent = `Свойство ${count}`;
        descriptionLabel.textContent = `Описание ${count}`;

        const [idInput, nameInput, descriptionInput] = clone.querySelectorAll(".field .input");
        idInput.name = `id_items_properties`;
        nameInput.name = `properties_id_items_properties`;
        descriptionInput.name = `description_items_properties`;

        const button = clone.querySelector(".button");
        button.addEventListener("click", () => {
            count--;
            clone.remove();
        });

        insertPlace.insertAdjacentElement("beforebegin", clone);
        setBasicSettingInput([idInput, nameInput, descriptionInput], form);
    });

    document.querySelectorAll(".additional .button").forEach((button) => {
        button.addEventListener("click", async () => {
            button.parentElement.classList.add("hidden");
            const result = await fetch("server.php", {
                "method": "POST",
                "body": JSON.stringify({
                    "server_type": "delete_item_properties",
                    "id_items_properties": button.parentElement.querySelector(".input:first-of-type").value
                }),
                "headers": {
                    "Content-type": "application/json"
                }
            });
            const resultData = await result.json();
            if (resultData["status"] == "OK") {
                button.parentElement.remove();
                count--;
            } else {
                button.parentElement.classList.remove("hidden");
            }
        });
    });

    form.addEventListener("submit", (event) => {
        let additional = [];
        const id = form.querySelectorAll(".input[data-uid^='id_items_properties']");
        const name = form.querySelectorAll(".input[data-uid^='properties_id_items_properties']");
        const description = form.querySelectorAll(".input[data-uid^='description_items_properties']");

        for (let i = 0; i < name.length; i++) {
            let array = {
                "id_items_properties": id[i]?.value ?? ""
            };
            if (name[i].hasAttribute("name")) {
                array["properties_id_items_properties"] = name[i].value;
            }
            if (description[i].hasAttribute("name")) {
                array["description_items_properties"] = description[i].value;
            }
            id[i]?.removeAttribute("name");
            name[i].removeAttribute("name");
            description[i].removeAttribute("name");

            if (Object.keys(array).length > 1) {
                additional.push(array);
            }
        }
        console.log(additional);
        if (additional != []) {
            const itemsProperties = document.getElementById("items_properties");
            itemsProperties.value = JSON.stringify(additional);
            itemsProperties.name = "items_properties";
        }
    });
}

let countInput = 0;
const formValidated = document.querySelector(".form");
setValidatedForm(formValidated);


function clickableItem(item) {
    const basketButton = item.querySelector("button.basket");
    const counterWrapper = item.querySelector(".counter-wrapper");
    const minusButton = counterWrapper.querySelector("button.minus");
    const counterText = counterWrapper.querySelector(".counter .counterText");
    const plusButton = counterWrapper.querySelector("button.plus");

    basketButton.addEventListener("click", async () => {
        changeButtonBasket(basketButton.dataset.type == "add", counterWrapper, counterText, basketButton);
        await sendItem(item, counterWrapper, counterText, basketButton);
    });

    minusButton.addEventListener("click", async () => {
        if (parseInt(counterText.textContent) <= 1) {
            changeButtonBasket(false, counterWrapper, counterText, basketButton);
        } else {
            counterText.textContent = parseInt(counterText.textContent) - 1;
        }
        await sendItem(item, counterWrapper, counterText, basketButton);
    });
    plusButton.addEventListener("click", async () => {
        if (item.dataset.count > parseInt(counterText.textContent)) {
            counterText.textContent = parseInt(counterText.textContent) + 1;
            await sendItem(item, counterWrapper, counterText, basketButton);
        }
    });
}

function changeButtonBasket(status, counterWrapper, counterText, basketButton) {
    if (status == null || counterText == null || counterWrapper == null || basketButton == null) return;

    if (status === true) {
        counterText.textContent = 1;
        counterWrapper.classList.remove("hidden");
        basketButton.textContent = "Убрать из корзины";
        basketButton.dataset.type = "remove";
    } else {
        counterText.textContent = 0;
        counterWrapper.classList.add("hidden");
        basketButton.textContent = "Добавить в корзину";
        basketButton.dataset.type = "add";
    }
}

async function sendItem(item, counterWrapper, counterText, basketButton) {
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
        changeButtonBasket(basketButton.dataset.type == "add", counterWrapper, counterText, basketButton);
    }
}