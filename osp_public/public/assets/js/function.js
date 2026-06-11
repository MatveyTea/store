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
    const isEditFile = "/admin/editItem.php" == window.location.pathname;

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
                    "id_items_properties": button.dataset.idItemsProperties
                });
                if (resultData["status"] == "OK") {
                    const parent = form.querySelector(`.field.property:has(.button[data-id-items-properties='${button.dataset.idItemsProperties}'])`);
                    parent.remove();
                    currentCountProperties--;
                } else {
                    showModal("Не удалось удалить свойство");
                }
            });
        });
    }
}

function checkInput(input, rule) {
    let textMessage = "";
    let isCorrect = true;
    if (rule.required || (!rule.required && (input.value != "" || input?.files?.length > 0 || rule.nameRule == "image_items_update"))) {
        const result = rule.regExp == null ? rule.check(input) : rule.check(input, rule.regExp);
        if (result !== false) {
            textMessage = result;
            isCorrect = false;
        }
    }

    if (rule.placeMsg[input.id]) {
        if (isCorrect) {
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
        if (!input.hasAttribute("data-is-insert-server")) {
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
            placeMsg.classList.add("invisible");
            rule.placeMsg[input.id] = placeMsg;
        }

        if (rule.connectedRules != null) {
            rule.connectedInputs[input.id] = Array.from(document.querySelectorAll(`.form:has(#${input.id}) .input[data-name~=${rule.connectedRules.join("-")}]`));
        }

        if (rule.nameInput != null) {
            const label = form.querySelector(`.field:not(.property):has(#${input.id}) .label`);
            if (label) {
                label.setAttribute("for", input.id);
                let required = "";
                if (rule.required) {
                    required = "<b>*</b>";
                }
                label.insertAdjacentHTML("afterBegin", rule.nameInput.slice(0, 1).toUpperCase() + rule.nameInput.slice(1) + required);
            }
        }

        if (rule.length != null) {
            input.setAttribute("maxlength", rule.length);
        }

        if (rule.nameInput != null) {
            input.setAttribute("placeholder", `Введите ${rule.nameInput}`);
        }

        if (input?.selectedIndex != 0 && input.tagName != "SPAN" && (input.checked || (input.value != "" && !input?.checked && input.value != "on") || input.isInsertServer == 0)) {
            checkInput(input, rule);
        }

        const action = input.tagName == "SPAN" ? "click" : "change";
        input.addEventListener(action, (event) => {
            rule.currentValue[input.id] = rule.wayDefineValue(input);
            if (event.isTrusted) {
                checkInput(input, rule);
            }
        });
    });

    return validationRules;
}

function setValidationForm(form) {
    if (form == null) return;

    const inputs = form.querySelectorAll(".input");
    const validatedRules = setBasicSettingInput(inputs, form);

    if (["/admin/editItem.php", "/admin/addItem.php"].includes(window.location.pathname)) {
        setProperties(form);
        setSliderImageItem(true);
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
        if (false && (hasError || !hasUpdate)) {
            event.preventDefault();
        } else {
            const updateInput = form.querySelectorAll(".input[data-is-insert-server='0']");
            const imgsView = form.querySelectorAll(".image-view img");
            const imgOne = form.querySelector("img:not(.image-view img)");
            if (event.defaultPrevented) {
                updateInput.forEach((input) => {
                    input.value = "";
                    input.checked = !input.checked;
                    input.dispatchEvent(new Event("change"));
                });
            }
            imgsView?.forEach((img) => img.remove());
            if (imgOne) {
                imgOne.src = "";
                const timerId = setInterval(() => {
                    if (imgOne.src != "") {
                        imgOne.classList.add("hidden");
                        imgOne.src = "";
                        clearInterval(timerId);
                    }
                }, 10);
            }
        }
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
        regExp - регулярное выражение | ?RegExp
        check - функция валидации | function(input): Bool, String 
    */
    let result = [];
    const splitFile = window.location.pathname.split("/");
    const file = window.location.pathname == "/" ? "index.php" : splitFile[splitFile.length - 1];

    const symbols = {
        "id": "^[0-9]{1,10}$",
        "num": "0-9",
        "space": " ",
        "ru": "А-Яа-яеЁ",
        "eng": "A-Za-z",
        "special": "!@#\$%^&*()\-+=_\{\}\\[\\]|:;\"'<>?/\\.,",
        "simple": "().,:\"'!?\-"
    };

    const rules = {
        // Пользователь
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
            "regExp": new RegExp(`^[${symbols.eng}${symbols.num}._%+-]{1,50}@[${symbols.eng}${symbols.num}.-]{1,15}\\.[${symbols.eng}]{2,15}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите действительный email-адрес, 4-80 символов.";
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
            "length": 64,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.eng}${symbols.num}${symbols.special}]{8,64}$`, "u"),
            "check": function (input, regExp) {
                if (!regExp.test(input.value)) {
                    return `Введите латинские символы, цифры или допустимые символы (${symbols.special}), 8-64 символов.`;
                }

                const rePassword = document.querySelector("input[data-name^=re_password_users]");
                if (rePassword) {
                    const rePasswordPlaceError = rePassword.parentElement.querySelector(".error");
                    if (input.value != rePassword.value && rePassword.value != "") {
                        rePasswordPlaceError.textContent = "Пароли должны совпадать!";
                        rePasswordPlaceError.classList.remove("hidden");
                        rePassword.removeAttribute("name");
                        return "Пароли должны совпадать.";
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
            "length": 64,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.eng}${symbols.num}${symbols.special}]{8,64}$`, "u"),
            "check": function (input, regExp) {
                if (!regExp.test(input.value)) {
                    return `Введите латинские символы, цифры или допустимые символы (${symbols.special}), 8-64 символов.`;
                }

                const password = document.querySelector("input[data-name=password_users]");
                if (password) {
                    const passwordPlaceError = password.parentElement.querySelector(".error");
                    if (input.value != password.value && password.value != "") {
                        passwordPlaceError.textContent = "Пароли должны совпадать!";
                        passwordPlaceError.classList.remove("hidden");
                        password.removeAttribute("name");
                        return "Пароли должны совпадать.";
                    } else if (password.value == input.value && input.value != "") {
                        passwordPlaceError.textContent = "";
                        passwordPlaceError.classList.add("hidden");
                        password.toggleAttribute("name", true);
                    }
                }
                return false;
            }
        },
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
            "length": 100,
            "regExp": new RegExp(`^$|^[${symbols.ru}${symbols.eng}${symbols.space}-]{1,100}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите только кириллические символы, 1-100 символов.";
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
            "regExp": new RegExp(" "),
            "check": function (input) {
                if (input.files.length > 0) {
                    let extension = input.files[0].name.split(".");
                    extension = extension[extension.length - 1];
                    if (input.files[0].size > 2_000_000) {
                        return "Размер изображения не должен превышать 2МБ.";
                    }
                    if (!["jpg", "png", "webp"].includes(extension)) {
                        return "Изображение должно быть в формате jpg, png или webp.";
                    }
                    let reader = new FileReader();
                    reader.readAsDataURL(input.files[0]);
                    reader.addEventListener("load", (event) => {
                        if (event.target.result != null) {
                            let img = input.parentElement.querySelector("img");
                            img.classList.remove("hidden");
                            img.src = event.target.result;
                        } else {
                            return "Не удалось сделать предварительный просмотр изображения.";
                        }
                    });
                }
                return false;
            }
        },
        "tel_users": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "телефон",
            "inputs": null,
            "nameRule": "tel_users",
            "oldValue": null,
            "files": ["profile.php"],
            "required": false,
            "timerId": null,
            "placeMsg": null,
            "regExp": new RegExp(`^$|^\\+[${symbols.num}]{11}$`, "u"),
            "length": 12,
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите корректный номер телефона начиная с плюса.";
            }
        },
        "privacy_users": {
            "wayDefineValue": function(input) {
                return input.checked;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "согласие на обработку персональных данных",
            "inputs": null,
            "nameRule": "privacy_users",
            "oldValue": null,
            "files": ["reg.php"],
            "required": true,
            "timerId": null,
            "placeMsg": null,
            "regExp": null,
            "length": null,
            "check": function (input) {
                if (input.checked) {
                    return false;
                }
                return "Нужно согласиться.";
            }
        },
        // Поиск пользователя
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
            "files": ["editUser.php"],
            "required": false,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.eng}${symbols.num}._%+-]{1,50}@[${symbols.eng}${symbols.num}.-]{1,15}\\.[${symbols.eng}]{2,15}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите действительный email-адрес, 4-80 символов.";
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
            "files": ["editUser.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                return input.selectedIndex > 0 ? false : "Выберите другой элемент из списка.";
            },
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
            "files": ["editItem.php"],
            "required": true,
            "timerId": null,
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input,regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return null;
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
            "files": ["editItem.php", "addItem.php"],
            "required": true,
            "timerId": null,
            "length": 100,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,100}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-100 символов.";
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
            "files": ["editItem.php", "addItem.php"],
            "required": false,
            "timerId": null,
            "length": 3000,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,3000}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-3000 символов.";
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
            "files": ["editItem.php", "addItem.php"],
            "required": true,
            "timerId": null,
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input, regExp) {
                if (/^[0-9]{1,10}$/.test(input.value)) {
                    return false;
                }
                return "Введите число, 1-10 символов.";
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
            "files": ["editItem.php", "addItem.php"],
            "required": true,
            "timerId": null,
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input, regExp) {
                if (/^[0-9]{1,10}$/.test(input.value)) {
                    return false;
                }
                return "Введите число, 1-10 символов.";
            }
        },
        "discount_items": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "процент скидки",
            "inputs": null,
            "nameRule": "discount_items",
            "oldValue": null,
            "files": ["editItem.php", "addItem.php"],
            "required": false,
            "timerId": null,
            "length": 3,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                if (input.value <= 100 && input.value > 0) {
                    return false;
                }
                return "Введите число от 1 до 100.";
            }
        },
        "image_items_images": {
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
            "nameRule": "image_items_images[]",
            "oldValue": null,
            "files": ["editItem.php", "addItem.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": new RegExp(" "),
            "check": function (input) {
                const imagesContainer = document.querySelector(".images-container");
                const templateImage = document.querySelector(".template-image").content.firstElementChild;
                Array.from(input.files).forEach((file) => {
                    let extension = file.name.split(".");
                    extension = extension[extension.length - 1];
                    if (file.size > 2_000_000) {
                        return "Размер файла не должен превышать 3МБ";
                    }

                    if (!["jpg", "png", "webp"].includes(extension)) {
                        return "Некорректный тип файла. Файл должен быть jpg, png или webp";
                    }

                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.addEventListener("load", (event) => {
                        if (event.target.result != null) {
                            let image = templateImage.cloneNode(true);
                            image.querySelector("img").src = event.target.result;
                            imagesContainer.appendChild(image);
                        } else {
                            return "Не удалось загрузить картинку";
                        }
                    });
                });
                return false;
            }
        },
        "image_items_update": {
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
            "nameRule": "image_items_update",
            "oldValue": null,
            "files": ["editItem.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": new RegExp(`${symbols.id}|^${symbols.num}{13,14}\\.|png|jpg|webp$`, "u"),
            "check": function (input, regExp) {
                const result = [];
                const deleteImages = document.querySelectorAll(".images-container .hidden[data-id-items-images][data-path]");
                deleteImages.forEach((image) => {
                    if (regExp.test(image.dataset.idItemsImages) && regExp.test(image.dataset.path)) {
                        result.push({
                            "id": image.dataset.idItemsImages,
                            "path": image.dataset.path
                        });
                    } else {
                        return false;
                    }
                });
                input.value = JSON.stringify(result);
                input.dispatchEvent(new Event("change"));
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
            "files": ["editItem.php", "addItem.php"],
            "required": true,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                return input.selectedIndex > 0 ? false : "Выберите другой элемент из списка.";
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
            "length": 100,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,100}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-100 символов.";
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
            "length": 3000,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,3000}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-3000 символов.";
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
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input, regExp) {
                if (/^[0-9]{1,10}$/.test(input.value)) {
                    return false;
                }
                return "Введите число, 1-10 символов.";
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
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input, regExp) {
                if (/^[0-9]{1,10}$/.test(input.value)) {
                    return false;
                }
                return "Введите число, 1-10 символов.";
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
            "timerId": null,
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input, regExp) {
                if (/^[0-9]{1,10}$/.test(input.value)) {
                    return false;
                }
                return "Введите число, 1-10 символов.";
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
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input, regExp) {
                if (/^[0-9]{1,10}$/.test(input.value)) {
                    return false;
                }
                return "Введите число, 1-10 символов.";
            }
        },
        "discount_search_items": {
            "wayDefineValue": function(input) {
                return input.checked;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "искать товары со скидкой",
            "inputs": null,
            "nameRule": "discount_search_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                return false;
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
            "length": null,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                return false;
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
            "regExp": null,
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
            "nameInput": "искать среди популярных товаров",
            "inputs": null,
            "nameRule": "popular_items",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                return false;
            }
        },
        "id_search_attributes": {
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
            "nameRule": "id_search_attributes",
            "oldValue": null,
            "files": ["index.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": null,
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
            "length": 1500,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,1500}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-1500 символов.";
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
            "regExp": new RegExp(" "),
            "check": function (input) {
                const activeStars = input.querySelectorAll("svg.active").length;
                return activeStars > 0 && activeStars < 6 ? false : "Выберите количество звёзд от 1 до 5.";
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
            "files": ["editItem.php", "addItem.php"],
            "required": true,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id, "u"),
            "check": function (input, regExp) {
                const attributesIds = document.querySelectorAll(".field.property .input[data-name='attributes_select_value'][data-is-insert-server='0']");
                if (attributesIds.length < 1) {
                    input.value = "";
                    input.dispatchEvent(new Event("change"));
                    return false;
                }
                const attributes = [];
                attributesIds.forEach((attributesId) => {
                    const idProperty = document.querySelector(`.field.property:has(#${attributesId.id}) select`).value;
                    if (!regExp.test(attributesId.value) || !regExp.test(idProperty)) {
                        return false;
                    }
                    attributes.push({
                        "type": attributesId.checked ? "add" : "remove",
                        "id_attributes": attributesId.value,
                        "id_properties": idProperty
                    });
                });
                input.value = JSON.stringify(attributes);
                input.dispatchEvent(new Event("change"));
                return false;
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
            "files": ["editItem.php", "addItem.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                return false;
            }
        },
        "attributes_select_value": {
            "wayDefineValue": function(input) {
                return input.checked ? input.value : null;
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
            "files": ["editItem.php", "addItem.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": null,
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
            "files": ["editTable.php"],
            "required": true,
            "timerId": null,
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input,regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return null;
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
            "files": ["editTable.php"],
            "required": true,
            "timerId": null,
            "length": 50,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,50}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-50 символов.";
            }
        },
        // Атрибуты
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
            "nameRule": "attributes",
            "oldValue": null,
            "files": ["editTable.php"],
            "required": true,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": new RegExp(`${symbols.id}|^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,50}$`, "u"),
            "check": function (input, regExp) {
                const form = document.querySelector(`.form:has(#${input.id})`);
                const values = form.querySelectorAll(".input[data-name='value_attributes'][data-is-insert-server='0']");
                if (values.length < 0) {
                    input.value = "";
                    input.dispatchEvent(new Event("change"));
                    return false;
                }
                const idProperty = form.querySelector(".input[data-name='id_properties']");
                if (idProperty && !regExp.test(idProperty.value)) return null;
                const result = [];
                values.forEach((value) => {
                    let temp = {};
                    if (value.hasAttribute("data-id-attributes")) {
                        temp["id_attributes"] = value.dataset.idAttributes;
                    }
                    if (idProperty) {
                        temp["properties_id_attributes"] = idProperty.value;
                    }
                    if (!regExp.test(value.value)) {
                        return null;
                    }
                    temp["value_attributes"] = value.value;
                    result.push(temp);
                });
                input.value = JSON.stringify(result);
                input.dispatchEvent(new Event("change"));
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
            "files": ["editTable.php"],
            "required": false,
            "timerId": null,
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input,regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return null;
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
            "files": ["editTable.php"],
            "required": false,
            "timerId": null,
            "length": 50,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,50}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-50 символов.";
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
            "files": ["editTable.php"],
            "required": true,
            "timerId": null,
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input,regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return null;
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
            "files": ["editTable.php"],
            "required": true,
            "timerId": null,
            "length": 50,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,50}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-50 символов.";
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
            "length": 180,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,180}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'), 1-180 символов.";
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
            "length": 50,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,50}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'), 1-50 символов.";
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
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите цифры, 1-10 символов.";
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
            "length": null,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                return input.selectedIndex > 0 ? false : "Выберите другой элемент из списка.";
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
            "length": 255,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,255}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'), 1-255 символов.";
            }
        },
        // Техподдержка
        "title_talks": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "заголовок",
            "inputs": null,
            "nameRule": "title_talks",
            "oldValue": null,
            "files": ["support.php"],
            "required": true,
            "timerId": null,
            "length": 100,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,100}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-100 символов.";
            }
        },
        "text_supports": {
            "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "сообщение",
            "inputs": null,
            "nameRule": "text_supports",
            "oldValue": null,
            "files": ["support.php"],
            "required": true,
            "timerId": null,
            "length": 1000,
            "placeMsg": null,
            "regExp": new RegExp(`^[${symbols.ru}${symbols.eng}${symbols.num}${symbols.space}${symbols.simple}]{1,1000}$`, "u"),
            "check": function (input, regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (().,:\"'-), 1-1000 символов.";
            }
        },
        "talks_id_supports": {
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
            "nameRule": "talks_id_supports",
            "oldValue": null,
            "files": ["support.php"],
            "required": true,
            "timerId": null,
            "length": 10,
            "placeMsg": null,
            "regExp": new RegExp(symbols.id),
            "check": function (input,regExp) {
                if (regExp.test(input.value)) {
                    return false;
                }
                return null;
            }
        },
        "image_supports": {
             "wayDefineValue": function(input) {
                return input.value;
            },
            "currentValue": null,
            "hasName": false,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "фото",
            "inputs": null,
            "nameRule": "image_supports",
            "oldValue": null,
            "files": ["support.php"],
            "required": false,
            "timerId": null,
            "length": null,
            "placeMsg": null,
            "regExp": null,
            "check": function (input) {
                if (input.files.length > 0) {
                    let extension = input.files[0].name.split(".");
                    extension = extension[extension.length - 1];
                    if (input.files[0].size > 2_000_000) {
                        return "Размер изображения не должен превышать 2МБ.";
                    }
                    if (!["jpg", "png", "webp"].includes(extension)) {
                        return "Изображение должно быть в формате jpg, png или webp.";
                    }

                    let reader = new FileReader();
                    reader.readAsDataURL(input.files[0]);
                    reader.addEventListener("load", (event) => {
                        if (event.target.result != null) {
                            let img = input.parentElement.querySelector("img");
                            img.classList.remove("hidden");
                            img.src = event.target.result;
                        } else {
                            return "Не удалось сделать предварительный просмотр изображения.";
                        }
                    });
                }
                return false;
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

const token = document.querySelector("meta[name='token']")?.getAttribute("content") ?? "notfound";
async function sendToServer(data) {
    data["token"] = token;
    const result = await fetch("/api/server.php", {
        "method": "POST",
        "body": JSON.stringify(data),
        "headers": {
            "Content-type": "application/json"
        }
    });
    return await result.json();
}

function clickableItem(item) {
    const basketButton = item.querySelector(".basket-action");
    const counterContainer = item.querySelector(".basket-container");
    const minusButton = counterContainer.querySelector(".basket-minus");
    const counterText = counterContainer.querySelector(".basket-text");
    const plusButton = counterContainer.querySelector(".basket-plus");
    const favoritesButton = item.querySelector(".item-favorites");

    basketButton.addEventListener("click", async () => {
        changeButtonBasket(basketButton.dataset.type == "add", counterContainer, counterText, basketButton);
        await sendItem(item, counterContainer, counterText, basketButton);
    });
    favoritesButton.addEventListener("click", async () => changeFavorites(favoritesButton, item));

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

async function changeFavorites(favoritesButton, item) {
    if (!favoritesButton.classList.contains("favorite")) {
        favoritesButton.textContent = "Убрать из избранного";
        favoritesButton.classList.add("favorite");
    } else {
        favoritesButton.textContent = "Добавить в избранное";
        favoritesButton.classList.remove("favorite");
    }
    const dataResult = await sendToServer({
        "server_type": "change_favorites",
        "id_items": item.dataset.id
    });
    if (dataResult["status"] != "OK") {
        if (!favoritesButton.classList.contains("favorite")) {
            favoritesButton.textContent = "Убрать из избранного";
            favoritesButton.classList.add("favorite");
        } else {
            favoritesButton.textContent = "Добавить в избранное";
            favoritesButton.classList.remove("favorite");
        }
    }
}

async function sendItem(item, counterWrapper, counterText, basketButton) {
    const dataResult = await sendToServer({
        "server_type": "change_basket",
        "id_items": item.dataset.id,
        "count_items": parseInt(counterText.textContent),
        "action_items": basketButton.dataset.type == "add" ? "remove" : "add"
    });

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

function setSliderImageItem(isAdminFile = false) {
    const imagesView = document.querySelector(".images-view");
    const imagesContainer = imagesView.querySelector(".images-container");

    let isTwoImage = document.body.offsetWidth > 768 && isAdminFile ? 2 : 1;
    let countImage = imagesContainer.children.length;
    let currentIndex = 0;
    let currentTranslate = 0;
    let step = imagesView.clientWidth / isTwoImage + imagesContainer.computedStyleMap().get("column-gap").value / isTwoImage;
    const leftSwitch = imagesView.querySelector(".images-switch-left");
    const rightSwitch = imagesView.querySelector(".images-switch-right");
    leftSwitch.addEventListener("click", (event) => {
        event.preventDefault();
        leftSwitch.classList.toggle("hidden", currentIndex - 2 < 0 || countImage < 2);
        rightSwitch.classList.toggle("hidden", currentIndex - 1 >= countImage || countImage < 2);
        if (currentIndex - 1 < 0) return;
        currentIndex--;
        currentTranslate += step;
        imagesContainer.style.transform = `translateX(${currentTranslate}px)`;
    });
    rightSwitch.addEventListener("click", (event) => {
        event.preventDefault();
        leftSwitch.classList.toggle("hidden", currentIndex + 1 < 0 || countImage < 2);
        rightSwitch.classList.toggle("hidden", currentIndex + isTwoImage + 1 >= countImage || countImage < 2);
        if (currentIndex + 1 >= countImage) return;
        currentIndex++;
        currentTranslate -= step;
        imagesContainer.style.transform = `translateX(${currentTranslate}px)`;
    });

    if (isTwoImage == 2 && countImage > 2 || isTwoImage == 1 && countImage > 1) {
        leftSwitch.click();
    }

    let timerResize = null;
    window.addEventListener("resize", () => {
        clearTimeout(timerResize);
        timerResize = setTimeout(() => {
            isTwoImage = document.body.offsetWidth > 768 && isAdminFile ? 2 : 1;
            step = imagesView.clientWidth / isTwoImage + imagesContainer.computedStyleMap().get("column-gap").value / isTwoImage;
            Array.from(imagesContainer.children).forEach((image) => {
                image.style.width = `${imagesView.clientWidth / isTwoImage - (isTwoImage == 2 ? imagesContainer.computedStyleMap().get("column-gap").value / 2 : 0)}px`;
            });
            currentTranslate = 0;
            imagesContainer.style.transform = `translateX(${currentTranslate}px)`;
            const tempIndex = isTwoImage == 2 && currentIndex + 1 >= countImage ? currentIndex - 1 : currentIndex;
            currentIndex = 0;
            for (let i = 0; i < tempIndex; i++) {
                rightSwitch.click();
            }
        }, 100);
    });

    setTimeout(() => {
        Array.from(imagesContainer.children).forEach((image) => {
            image.style.width = `${imagesView.clientWidth / isTwoImage - (isTwoImage == 2 ? imagesContainer.computedStyleMap().get("column-gap").value / 2 : 0)}px`;
        });
    }, 100);

    if (isAdminFile) {
        imagesContainer.addEventListener("click", (event) => {
            if (event.target.tagName == "BUTTON") {
                event.preventDefault();
                event.target.parentElement.classList.add("hidden");
                countImage = imagesContainer.querySelectorAll(".image:not(.hidden)").length;
                if (currentIndex >= countImage) {
                    leftSwitch.click();
                }
            }
        });
        const observer = new MutationObserver((mutationsList) => {
            if (mutationsList[0].addedNodes.length > 0) {
                mutationsList[0].addedNodes[0].style.width = `${step}px`;
                rightSwitch.click();
            }
        });
        observer.observe(imagesContainer, { childList: true });
    }
}