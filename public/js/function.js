"use strict";

function changeProperty(select, dependencies) {
    const selectedValues = select.dataset?.value?.split("|") ?? [];
    for (const idAttribute in dependencies) {
        if (idAttribute == select.value) {
            document.querySelectorAll(`.field.property:has(#${select.id}) .field:has(.input[type='checkbox']) label`).forEach((label) => {
                const input = label.querySelector(".input");
                const includesProperty = dependencies[idAttribute].includes(+input.value);
                label.classList.toggle("hidden", !includesProperty);
                input.checked = selectedValues.includes(input.value) ? "checked" : "";
                input.dispatchEvent(new Event("change"));
            });
            break;
        }
    }
}

function setProperties(form) {
    const templateProperties = document.querySelector("template[data-max-count][data-current-count]");
    const wrapperProperties = templateProperties.content.firstElementChild;
    const dependenciesProperties = JSON.parse(templateProperties.content.lastElementChild.textContent);
    const maxCountProperties = templateProperties.dataset.maxCount;
    let currentCountProperties = parseInt(templateProperties.dataset.currentCount);

    const addProperties = form.querySelector(".add-properties");
    const fieldProperties = form.querySelector(".field-properties");
    const isEditFile = "/adminEditItem.php" == window.location.pathname;

    const allSelectProperty = document.querySelectorAll(".field.property select");
    allSelectProperty.forEach((select) => {
        changeProperty(select, dependenciesProperties);
        select.addEventListener("change", () => changeProperty(select, dependenciesProperties));
    });

    addProperties.addEventListener("click", (event) => {
        event.preventDefault();
        if (currentCountProperties >= maxCountProperties) return;
        currentCountProperties++;
        const clone = wrapperProperties.cloneNode(true);
        fieldProperties.prepend(clone);

        clone.querySelector(".delete-property").addEventListener("click", () => {
            currentCountProperties--;
            clone.remove();
        });

        setBasicSettingInput([...clone.querySelectorAll(".field .input")], form);
        const select = clone.querySelector("select");
        select.addEventListener("change", () => changeProperty(select, dependenciesProperties));
    });

    if (isEditFile) {
        const allDeleteProperty = form.querySelectorAll(".delete-property");
        allDeleteProperty.forEach((button) => {
            button.addEventListener("click", async (event) => {
                event.preventDefault();
                const resultData = await sendToServer({
                    "server_type": "delete_item_properties",
                    "id_properties": button.dataset.idProperty
                });
                if (resultData["status"] == "OK") {
                    const parent = form.querySelector(`.field.property:has(.button[data-id-property='${button.dataset.idProperty}'])`);
                    parent.remove();
                    currentCountProperties--;
                } else {
                    showModal("Не удалось удалить свойство");
                }
            });
        });
    }

    form.addEventListener("submit", (event) => {
        const itemsProperties = document.querySelector(".input[data-name='items_properties']");
        const allProperty = document.querySelectorAll(".field.property");
        const attributes = [];
        allProperty.forEach((field) => {
            const valuesProperty = Array.from(field.querySelectorAll(".input[data-name='attributes_select_value'][data-is-insert-server='0']"));
            valuesProperty.forEach((valueProperty) => {
                attributes.push({
                    "type": valueProperty.checked ? "add" : "remove",
                    "id_attributes": valueProperty.value,
                    "id_properties": document.querySelector(`.field.property:has(#${valueProperty.id}) select`).value
                });
            });
        });
        itemsProperties.value = JSON.stringify(attributes ?? "");
        itemsProperties.dispatchEvent(new Event("change"));
    });
}

function checkInput(input, rule) {
    let textMessage = "";
    let isCorrect = true;

    if (rule.required || (!rule.required && (input.value != "" || input?.files?.length > 0))) {
        const result = rule.check(input);
        if (result !== false) {
            textMessage = result;
            isCorrect = false;
        }
    }

    if (rule.placeMsg[input.id]) {
        if (textMessage == "") {
            rule.timerId = setTimeout(() => {
                clearTimeout(rule.timerId);
                rule.placeMsg[input.id].textContent = textMessage;
            }, 300);
        } else {
            rule.placeMsg[input.id].textContent = textMessage;
        }
        rule.placeMsg[input.id].classList.toggle("invisible", isCorrect);
    }

    if (isCorrect && rule.oldValue[input.id] == rule.currentValue[input.id]) {
        input.dataset.isInsertServer = 1;
    } else if (isCorrect && rule.oldValue[input.id] != rule.currentValue[input.id]) {
        input.dataset.isInsertServer = 0;
    }
    rule.isInsertServer[input.id] = input.dataset.isInsertServer;

    const isUpdate = rule.isInsertServer[input.id] == 0 && (rule.oldValue[input.id] != input.value || input?.files?.length > 0);
    const isConnectedInputChange = rule.connectedInputs[input.id]?.some((input) => input.hasAttribute("name"));
    if (isCorrect && rule.hasName == true && (isUpdate || isConnectedInputChange)) {
        input.setAttribute("name", rule.nameRule);
    } else {
        input.removeAttribute("name");
    }

    return isCorrect;
}

function setBasicSettingInput(inputs, form) {
    if (validationRules == [] || inputs == [] || form == null) return;

    Array.from(inputs).forEach((input) => {
        input.id = `input_${countInput++}`;
        input.removeAttribute("name");
        const rule = validationRules[input.dataset.name];

        if (rule.inputs == null) {
            rule.inputs = [];
            rule.oldValue = []
            rule.isInsertServer = [];
            rule.placeMsg = [];
            rule.connectedInputs = [];
            rule.currentValue = [];
        }

        rule.inputs.push(input);
        if (!input.hasAttribute("is-insert-server")) {
            input.dataset.isInsertServer = 1;
        }
        rule.isInsertServer[input.id] = input.dataset.isInsertServer;
        if (rule.isInsertServer[input.id] == 1) {
            rule.oldValue[input.id] = rule.wayDefineValue(input);
        } else {
            rule.oldValue[input.id] = "";
        }
        rule.currentValue[input.id] = rule.wayDefineValue(input);

        const placeMsg = form.querySelector(`.field:not(.property):has(#${input.id}) .error`);
        if (placeMsg) {
            placeMsg.classList.toggle("invisible", placeMsg.textContent == "");
            rule.placeMsg[input.id] = placeMsg;
        }

        if (rule.connectedRules != null) {
            rule.connectedInputs[input.id] = Array.from(document.querySelectorAll(`.form:has(#${input.id}) .input[data-name~=${rule.connectedRules.join("-")}]`));
        }

        if (rule.nameInput != null) {
            const label = form.querySelector(`.field:not(.property):has(#${input.id}) .label`);
            if (label) {
                label.setAttribute("for", input.id);
                if (label.children.length > 0) {
                    label.innerHTML = rule.nameInput.slice(0, 1).toUpperCase() + rule.nameInput.slice(1) + label.innerHTML;
                } else {
                    label.innerHTML = rule.nameInput.slice(0, 1).toUpperCase() + rule.nameInput.slice(1);
                }
                if (rule.required) {
                    label.innerHTML += "<b>*</b>";
                }
            }
        }

        if (rule.length != null) {
            input.setAttribute("maxlength", rule.length);
        }

        if (rule.nameInput != null) {
            input.setAttribute("placeholder", `Введите ${rule.nameInput}`);
        }

        if (input.tagName != "SPAN" && (input.value != "" || input?.selectedIndex)) {
            checkInput(input, rule);
        }

        const action = input.tagName == "SPAN" ? "click" : "change";
        input.addEventListener(action, () => {
            rule.currentValue[input.id] = rule.wayDefineValue(input);
            checkInput(input, rule);
        });
    });

    return validationRules;
}

function setValidationForm(form) {
    if (form == null) return;

    const inputs = form.querySelectorAll(".input");
    const validatedRules = setBasicSettingInput(inputs, form);

    if (["/adminEditItem.php", "/adminAddItem.php"].includes(window.location.pathname)) {
        setProperties(form);
    }

    form.addEventListener("submit", (event) => {
        let hasError = false;
        let hasUpdate = false;
        Array.from(inputs).forEach((input) => {
            let isCorrect = checkInput(input, validatedRules[input.dataset.name]);
            if (!isCorrect) {
                hasError = true;
            } else if (isCorrect && input.dataset.isInsertServer == 0) {
                hasUpdate = true;
            }
        });
        // if (hasError || !hasUpdate) {
        //     event.preventDefault();
        // }
    });
}

function getValidationRules() {
    /*
        wayDefineValue - способ определения значения | function(input): String, Int
        connectedInputs - связанные поля по правилам | Array<String, Array<DOM>>
        connectedRules - связанные правила | Array<String>
        hasName - нужен ли аттрибут name | Bool
        isInsertServer - было ли значение вставлено из сессии (или при вводе пользователя) или из базы данных | Int = 0, 1
        placeholder - подсказка что вводить | String
        inputs - поля которые подходят под это правило | Array<DOM>
        nameInput - имя поля (для placeholder и label.textContent) | String
        nameRule - имя правила | String
        currentValue - текущее значение поля (или состояние отметки или индекс или значение) | Array<String, String>
        oldValue - значение которое пришло с сервера | Array<String, String>
        files - файлы в которых это правило используется | Array<String>
        required - обязательно ли это поли | Bool
        timerId - id таймера для показа ошибок | Int
        placeMsg - место куда выводить ошибки, виде  | Array<String, DOM>
        length - длина вводимых символов | ?Int
        check - функция валидации | function(input): Bool, String 
    */
    let result = [];
    const file = window.location.pathname == "/" ? "index.php" : window.location.pathname.split("/")[1];

    const rules = {
        // Пользователь
        "name_users": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "имя",
            "inputs": null,
            "nameRule": "name_users",
            "oldValue": null,
            "files": ["reg.php", "profile.php"],
            "required": true,
            "timerId": null,
            "placeMsg": null,
            "length": 30,
            "check": function (input) {
                if (/^[а-яёА-ЯЁ-]{1,30}$/u.test(input.value)) {
                    return false;
                }
                return "Введите только кириллические символы, 1-30 символов.";
            }
        },
        "email_users": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "email-адрес",
            "inputs": null,
            "nameRule": "email_users",
            "oldValue": null,
            "files": ["reg.php", "auth.php"],
            "required": true,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (/^[A-Za-z0-9._%+-]{1,50}@[A-Za-z0-9.-]{1,15}\.[A-Za-z]{1,15}$/.test(input.value)) {
                    return false;
                }
                return "Введите действительный email-адрес до 80 символов.";
            },
        },
        "email_search_users": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "email-адрес",
            "inputs": null,
            "nameRule": "email_search_users",
            "oldValue": null,
            "files": ["adminEditUser.php"],
            "required": false,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (/^[A-Za-z0-9._%+-@]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите действительный email-адрес до 80 символов.";
            },
        },
        "is_banned_search_users": {
            "wayDefineValue": function(input) {
                return input.selectedIndex;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "Искать среди",
            "inputs": null,
            "nameRule": "is_banned_search_users",
            "oldValue": null,
            "files": ["adminEditUser.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                return input.selectedIndex > -1 ? false : "Выберите элемент";
            },
        },
        "password_users": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "пароль",
            "inputs": null,
            "nameRule": "password_users",
            "oldValue": null,
            "files": ["reg.php", "auth.php", "profile.php"],
            "required": "profile.php" != file,
            "timerId": null,
            "length": 40,
            "placeMsg": null,
            "check": function (input) {
                if (!/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/.test(input.value)) {
                    return "Введите латинские символы, цифры или допустимые символы (@#$%^&*()_+-={}\:;\"'<>,.?\/), 1-40 символов.";
                }

                const rePassword = document.querySelector("input[data-name^=re_password_users]");
                if (rePassword) {
                    const rePasswordPlaceError = rePassword.parentElement.querySelector(".error");
                    if (input.value != rePassword.value && rePassword.value != "") {
                        rePasswordPlaceError.textContent = "Пароли должны совпадать!";
                        rePasswordPlaceError.classList.remove("hidden");
                        rePassword.removeAttribute("name");
                        return "Пароли должны совпадать!";
                    } else if (rePassword.value == input.value && input.value != "") {
                        rePasswordPlaceError.textContent = "";
                        rePasswordPlaceError.classList.add("hidden");
                        rePassword.toggleAttribute("name", true);
                    }
                }
                return false;
            }
        },
        "re_password_users": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "повтор пароля",
            "inputs": null,
            "nameRule": "re_password_users",
            "oldValue": null,
            "files": ["reg.php", "profile.php"],
            "required": "profile.php" != file,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (!/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/.test(input.value)) {
                    return /^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (@#$%^&*()_+-={}\:;\"'<>,.?\/), 1-40 символов.";
                }

                const password = document.querySelector("input[data-name=password_users]");
                if (password) {
                    const passwordPlaceError = password.parentElement.querySelector(".error");
                    if (input.value != password.value && password.value != "") {
                        passwordPlaceError.textContent = "Пароли должны совпадать!";
                        passwordPlaceError.classList.remove("hidden");
                        password.removeAttribute("name");
                        return "Пароли должны совпадать!";
                    } else if (password.value == input.value && input.value != "") {
                        passwordPlaceError.textContent = "";
                        passwordPlaceError.classList.add("hidden");
                        password.toggleAttribute("name", true);
                    }
                }
                return false;
            }
        },
        "avatar_users": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "аватар",
            "inputs": null,
            "nameRule": "avatar_users",
            "oldValue": null,
            "files": ["profile.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                if (input.files.length > 0) {
                    let extension = input.files[0].name.split(".");
                    extension = extension[extension.length - 1];
                    if (input.files[0].size > 3_000_000) {
                        return "Размер файла не должен превышать 3МБ";
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
        // Товар
        "id_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "id_items",
            "oldValue": null,
            "files": ["adminEditItem.php"],
            "required": true,
            "timerId": null,
            "length": 7,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "name_items": {
            "wayDefineValue": function(input) {
                return input.value
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "имя товара",
            "inputs": null,
            "nameRule": "name_items",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-80 символов.";
            }
        },
        "count_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "количество",
            "inputs": null,
            "nameRule": "count_items",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timerId": null,
            "length": 7,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "cost_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "стоимость",
            "inputs": null,
            "nameRule": "cost_items",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timerId": null,
            "length": 7,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "image_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "изображение товара",
            "inputs": null,
            "nameRule": "image_items",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                if (input.files.length > 0) {
                    let extension = input.files[0].name.split(".");
                    extension = extension[extension.length - 1];
                    if (input.files[0].size > 3_000_000) {
                        return "Размер файла не должен превышать 3МБ";
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
        "items_type_id_items": {
            "wayDefineValue": function(input) {
                return input.selectedIndex;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "тип товара",
            "inputs": null,
            "nameRule": "items_type_id_items",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                return input.selectedIndex > 0 ? false : "Выберите элемент";
            }
        },
        "description_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "описание товара",
            "inputs": null,
            "nameRule": "description_items",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": false,
            "timerId": null,
            "length": 255,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,255}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-255 символов.";
            }
        },
        // Поиск товара
        "name_search_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "имя товара",
            "inputs": null,
            "nameRule": "name_search_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-80 символов.";
            }
        },
        "description_search_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "описание товара",
            "inputs": null,
            "nameRule": "description_search_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": 255,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,255}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-255 символов.";
            }
        },
        "items_type_id_search_items": {
            "wayDefineValue": function(input) {
                return input.selectedIndex;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "тип товара",
            "inputs": null,
            "nameRule": "items_type_id_search_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": 7,
            "placeMsg": null,
            "check": function (input) {
                return false;
            }
        },
        "min_cost_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "мин. стоимость",
            "inputs": null,
            "nameRule": "min_cost_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": 7,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "max_cost_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "макс. стоимость",
            "inputs": null,
            "nameRule": "max_cost_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": 7,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "min_count_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "мин. количество",
            "inputs": null,
            "nameRule": "min_count_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": 7,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "max_count_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "макс. количество",
            "inputs": null,
            "nameRule": "max_cost_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": 7,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "strict_search": {
            "wayDefineValue": function(input) {
                return input.checked;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "искать товары подходящие по всем фильтрам",
            "inputs": null,
            "nameRule": "strict_search",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                return false;
            }
        },
        "popular_items": {
            "wayDefineValue": function(input) {
                return input.checked;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "искать только среди популярных товаров",
            "inputs": null,
            "nameRule": "popular_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                return false;
            }
        },
        // Комментарии
        "text_comments": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "комментарий",
            "inputs": null,
            "nameRule": "text_comments",
            "oldValue": null,
            "files": ["aboutItem.php"],
            "required": false,
            "timerId": null,
            "length": 255,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,255}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-255 символов.";
            }
        },
        "rating_comments": {
            "wayDefineValue": function(input) {
                return input.querySelectorAll("svg.active").length;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "рейтинг",
            "inputs": null,
            "nameRule": "rating_comments",
            "oldValue": null,
            "files": ["aboutItem.php"],
            "required": true,
            "timerId": null,
            "length": 5,
            "placeMsg": null,
            "check": function (input) {
                const activeStars = input.querySelectorAll("svg.active").length;
                return activeStars > 0 && activeStars < 6 ? false : "Введите число от 1 до 5";
            }
        },
        // Свойство у товаров
        "items_properties": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "items_properties",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                try {
                    JSON.stringify(input.value);
                    return false;
                } catch (Error) {
                    return "Некорректный JSON";
                }
            }
        },
        "attributes_select_property": {
            "wayDefineValue": function(input) {
                return input.selectedIndex;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "свойство",
            "inputs": null,
            "nameRule": "attributes_select_property",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                return false;
            }
        },
        "attributes_select_value": {
            "wayDefineValue": function(input) {
                return input.selectedIndex;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "attributes_select_value",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                return false;
            }
        },
        // Свойства товаров
        "id_properties": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": ["name_properties"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "id_properties",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": true,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "name_properties": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": ["id_properties"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "свойство",
            "inputs": null,
            "nameRule": "name_properties",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": true,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 ().,:\"'%-]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-80 символов.";
            }
        },
        //
        "attributes": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "items_properties",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                try {
                    JSON.stringify(input.value);
                    return false;
                } catch (Error) {
                    return "Некорректный JSON";
                }
            }
        },
        "attributes_search": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "attributes_search",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                return false;
            }
        },
        "id_attributes": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "id_attributes",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "value_attributes": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "значение свойства",
            "inputs": null,
            "nameRule": "value_attributes",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": false,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 ().,:\"'%-]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-80 символов.";
            }
        },
        // Типы товаров
        "id_items_type": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": ["name_items_type"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "id_items_type",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": true,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "name_items_type": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": ["id_items_type"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "тип товара",
            "inputs": null,
            "nameRule": "name_items_type",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": true,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-80 символов.";
            }
        },
        // Статус покупки
        "id_status": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": ["name_status"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "id_status",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": true,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,7}$/.test(input.value)) {
                    return false;
                }
                return "Введите число до 9 999 999";
            }
        },
        "name_status": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": ["id_status"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "статус",
            "inputs": null,
            "nameRule": "name_status",
            "oldValue": null,
            "files": ["adminEditTable.php"],
            "required": true,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-80 символов.";
            }
        },
        // Заказы
        "street_address_orders": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "улица",
            "inputs": null,
            "nameRule": "street_address_orders",
            "oldValue": null,
            "files": ["basket.php"],
            "required": true,
            "timerId": null,
            "length": 100,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"']{1,100}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'), 1-100 символов.";
            }
        },
        "home_address_orders": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "дом",
            "inputs": null,
            "nameRule": "home_address_orders",
            "oldValue": null,
            "files": ["basket.php"],
            "required": true,
            "timerId": null,
            "length": 100,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"']{1,100}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'), 1-100 символов.";
            }
        },
        "number_address_orders": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "квартира",
            "inputs": null,
            "nameRule": "number_address_orders",
            "oldValue": null,
            "files": ["basket.php"],
            "required": false,
            "timerId": null,
            "length": 100,
            "placeMsg": null,
            "check": function (input) {
                if (/^[0-9]{1,55}$/.test(input.value)) {
                    return false;
                }
                return "Введите цифры 1-55 символов.";
            }
        },
        "datetime_plan_orders": {
            "wayDefineValue": function(input) {
                return input.selectedIndex;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "время доставки",
            "inputs": null,
            "nameRule": "datetime_plan_orders",
            "oldValue": null,
            "files": ["basket.php"],
            "required": true,
            "timerId": null,
            "length": 100,
            "placeMsg": null,
            "check": function (input) {
                return input.selectedIndex > 0 ? false : "Выберите элемент";
            }
        },
        "note_orders": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "примечание",
            "inputs": null,
            "nameRule": "note_orders",
            "oldValue": null,
            "files": ["basket.php"],
            "required": false,
            "timerId": null,
            "length": 100,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"']{1,255}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'), 1-255 символов.";
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

let countInput = null;
let validationRules = null;
const validatedForm = document.querySelectorAll(".form");

if (validatedForm) {
    countInput = 0;
    validationRules = getValidationRules();
    validatedForm.forEach((form) => setValidationForm(form));
}


async function sendToServer(data) {
    const result = await fetch("server.php", {
        "method": "POST",
        "body": JSON.stringify(data),
        "headers": {
            "Content-type": "application/json"
        }
    });
    return await result.json();
}

function clickableItem(item) {
    const basketButton = item.querySelector(".item-basket");
    const counterContainer = item.querySelector(".item-counter-container");
    const minusButton = counterContainer.querySelector(".item-counter-minus");
    const counterText = counterContainer.querySelector(".item-counter-text");
    const plusButton = counterContainer.querySelector(".item-counter-plus");

    basketButton.addEventListener("click", async () => {
        changeButtonBasket(basketButton.dataset.type == "add", counterContainer, counterText, basketButton);
        await sendItem(item, counterContainer, counterText, basketButton);
    });

    minusButton.addEventListener("click", async () => {
        if (parseInt(counterText.textContent) <= 1) {
            changeButtonBasket(false, counterContainer, counterText, basketButton);
        } else {
            counterText.textContent = parseInt(counterText.textContent) - 1;
        }
        await sendItem(item, counterContainer, counterText, basketButton);
    });
    plusButton.addEventListener("click", async () => {
        if (item.dataset.count > parseInt(counterText.textContent)) {
            counterText.textContent = parseInt(counterText.textContent) + 1;
            await sendItem(item, counterContainer, counterText, basketButton);
        }
    });
}

function changeButtonBasket(status, counterContainer, counterText, basketButton) {
    if (status == null || counterText == null || counterContainer == null || basketButton == null) return;

    if (status === true) {
        counterText.textContent = 1;
        counterContainer.classList.remove("invisible");
        basketButton.textContent = "Убрать из корзины";
        basketButton.dataset.type = "remove";
    } else {
        counterText.textContent = 0;
        counterContainer.classList.add("invisible");
        basketButton.textContent = "Добавить в корзину";
        basketButton.dataset.type = "add";
    }
}

async function sendItem(item, counterWrapper, counterText, basketButton) {
    const dataItem = {
        "server_type": "change_basket",
        "id_items": item.dataset.id,
        "count_items": parseInt(counterText.textContent),
        "action_items": basketButton.dataset.type == "add" ? "remove" : "add"
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


let prevModal = null;
function showModal(text) {
    if (prevModal != null) prevModal.querySelector(".button").click();

    const template = document.querySelector("template[data-is-show-modal]");
    if (!template || text == "") return;

    const modal = template.content.firstElementChild.cloneNode(true);
    const modalText = modal.querySelector("p");
    const modalButton = modal.querySelector(".button");

    prevModal = modal;
    document.body.appendChild(modal);
    modal.classList.remove("hidden");
    setTimeout(() => {
        modal.classList.remove("invisible");
        setTimeout(() => modalButton.click(), 3000);
    }, 10);

    modalText.textContent = text;

    modalButton.addEventListener("click", () => {
        modal.classList.add("invisible");
        setTimeout(() => {
            modal.remove();
        }, 300);
    });
}

const template = document.querySelector("template[data-is-show-modal]");
if (template && template.dataset.isShowModal == 1) {
    showModal(template.dataset?.text);
}

function setBurgerMenu() {
    const burger = document.querySelector(".header-mobile-burger");
    const content = document.querySelector(".header-mobile-content");

    if (window.screen.width > 768) {
        burger.classList.remove("open");
        content.style.display = "none";
    } else {
        const vh = window.innerHeight - 100;
        document.querySelector(".header-mobile-content").style.height = `${vh}px`;
        burger.onclick = function () {
            burger.classList.toggle("open");
            if (burger.classList.contains("open")) {
                content.style.display = "flex";
                document.body.style.overflow = "hidden";
                setTimeout(() => content.style.opacity = "1", 10);
            } else {
                content.style.opacity = "0";
                document.body.style.overflow = "unset";
                setTimeout(() => content.style.display = "none", 300);
            }
        }
    }
}

window.addEventListener("resize", () => {
    setBurgerMenu();
});