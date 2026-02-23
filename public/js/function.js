"use strict";

/*
    connectedInputs - связанные поля по правилам | Array<String, Array<DOM>>
    connectedRules - связанные правила | Array<String>
    hasName - нужен ли аттрибут name | Bool
    isInsertServer - было ли значение вставлено из сессии (или при вводе пользователя) или из базы данных | Int = 0, 1
    placeholder - подсказка что вводить | String
    inputs - поля которые подходят под это правило | Array<DOM>
    nameInput - имя поля (для placeholder и label.textContent) | String
    nameRule - имя правила | String
    oldValue - значение которое пришло с сервера | Array<String, String>
    files - файлы в которых это правило используется | Array<String>
    required - обязательно ли это поли | Bool
    timerId - id таймера для показа ошибок | Int
    placeMsg - место куда выводить ошибки, виде  | Array<String, DOM>
    length - длина вводимых символов | ?Int
    check - функция валидации | function(input): Bool, String 
*/

function getValidationRules() {
    let result = [];
    const file = window.location.pathname == "/" ? "index.php" : window.location.pathname.split("/")[1];

    const rules = {
        // Пользователь
        "name_users": {
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
        "password_users": {
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "пароль",
            "inputs": null,
            "nameRule": "password_users",
            "oldValue": null,
            "files": ["reg.php", "auth.php", "profile.php"],
            "required": true,
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
            "hasName": true,
            "connectedRules": null,
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "повтор пароля",
            "inputs": null,
            "nameRule": "re_password_users",
            "oldValue": null,
            "files": ["reg.php", "profile.php"],
            "required": true,
            "timerId": null,
            "length": 80,
            "placeMsg": null,
            "check": function (input) {
                if (!/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/.test(input.value)) {
                    return /^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (@#$%^&*()_+-={}\:;\"'<>,.?\/), 1-40 символов.";
                }

                const password = document.querySelector("input[data-name=password_users]");
                console.log(password);
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
                return  "Введите число до 9 999 999";
            }
        },
        "cost_items": {
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
                return  "Введите число до 9 999 999";
            }
        },
        "image_items": {
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
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-255 символов.";
            }
        },
        // Поиск товара
        "name_search_items": {
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
        "items_type_id_search_items": {
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
                return  "Введите число до 9 999 999";
            }
        },
        "max_cost_items": {
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
                return  "Введите число до 9 999 999";
            }
        },
        "min_count_items": {
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
                return  "Введите число до 9 999 999";
            }
        },
        "max_count_items": {
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
                return  "Введите число до 9 999 999";
            }
        },
        "strict_search": {
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
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-255 символов.";
            }
        },
        "rating_comments": {
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
                return /^[1-5]$/.test(input.value) ? false : "Введите число от 1 до 5";
            }
        },
        // Свойство у товаров
        "items_properties": {
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
        "id_items_properties": {
            "hasName": false,
            "connectedRules": ["properties_id_items_properties", "description_items_properties"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": null,
            "inputs": null,
            "nameRule": "id_items_properties",
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
                return  "Введите число до 9 999 999";
            }
        },
        "properties_id_items_properties": {
            "hasName": false,
            "connectedRules": ["id_items_properties"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "свойство",
            "inputs": null,
            "nameRule": "properties_id_items_properties",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timerId": null,
            "length": 255,
            "placeMsg": null,
            "check": function (input) {
                return input.selectedIndex > 0 ? false : "Выберите элемент";
            }
        },
        "description_items_properties": {
            "hasName": false,
            "connectedRules": ["id_items_properties"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "описание",
            "inputs": null,
            "nameRule": "description_items_properties",
            "oldValue": null,
            "files": ["adminEditItem.php", "adminAddItem.php"],
            "required": true,
            "timerId": null,
            "length": 255,
            "placeMsg": null,
            "check": function (input) {
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-255 символов.";
            }
        },
        // Свойства товаров
        "id_properties": {
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
                return  "Введите число до 9 999 999";
            }
        },
        "name_properties": {
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
                if (/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/.test(input.value)) {
                    return false;
                }
                return "Введите латинские, кириллические символы, цифры или допустимые символы (-().,:\"'%), 1-80 символов.";
            }
        },
        // Типы товаров
        "id_items_type": {
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
                return  "Введите число до 9 999 999";
            }
        },
        "name_items_type": {
            "hasName": true,
            "connectedRules": ["id_items_type"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "свойство",
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
                return  "Введите число до 9 999 999";
            }
        },
        "name_status": {
            "hasName": true,
            "connectedRules": ["id_status"],
            "connectedInputs": null,
            "isInsertServer": null,
            "nameInput": "свойство",
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
        }
    };

    for (const key in rules) {
        if (Array.from(rules[key]["files"]).includes(file)) {
            result[key] = rules[key];
        }
    }
    return result;
}

function toggleNameInput(input, rule, isAdd) {
    const isUpdate = rule.isInsertServer[input.id] == 0 && (rule.oldValue[input.id] != input.value || input?.files?.length > 0);
    const isConnectedInputChange = rule.connectedInputs[input.id]?.some((input) => input.hasAttribute("name"));
    if (isAdd && rule.hasName == true && (isUpdate || isConnectedInputChange)) {
        input.setAttribute("name", rule.nameRule);
    } else {
        input.removeAttribute("name");
    }
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

    if (textMessage == "") {
        rule.timerId = setTimeout(() => {
            clearTimeout(rule.timerId);
            rule.placeMsg[input.id].textContent = textMessage;
        }, 300);
    } else {
        rule.placeMsg[input.id].textContent = textMessage;
    }

    if (isCorrect && (rule.oldValue[input.id] == input.value || rule.oldValue[input.id] == input.selectedIndex)) {
        input.dataset.isInsertServer = 1;
    } else if (isCorrect && (rule.oldValue[input.id] != input.value || rule.oldValue[input.id] == input.selectedIndex)) {
        input.dataset.isInsertServer = 0;
    }

    rule.isInsertServer[input.id] = input.dataset.isInsertServer;

    rule.placeMsg[input.id].classList.toggle("hidden", isCorrect);

    toggleNameInput(input, rule, isCorrect);
    return isCorrect;
}

function setBasicSettingInput(inputs, form) {
    const validatedRules = getValidationRules();
    if (validatedRules == []) return;

    Array.from(inputs).forEach((input) => {
        input.id = `input_${countInput++}`;
        input.removeAttribute("name");
        const rule = validatedRules[input.dataset.name];

        if (rule.inputs == null) {
            rule.inputs = [];
            rule.oldValue = []
            rule.isInsertServer = [];
            rule.placeMsg = [];
            rule.connectedInputs = [];
        }

        rule.inputs.push(input);
        rule.isInsertServer[input.id] = input.dataset?.isInsertServer ?? 1;
        if (rule.isInsertServer[input.id] == 1) {
            rule.oldValue[input.id] = input.value
        } else {
            rule.oldValue[input.id] = "";
        }

        const placeMsg = form.querySelector(`.field:not(.additional):has(#${input.id}) .error`);
        placeMsg.classList.toggle("hidden", placeMsg.textContent == "");
        rule.placeMsg[input.id] = placeMsg;

        if (rule.connectedRules != null) {
            rule.connectedInputs[input.id] = Array.from(document.querySelectorAll(`
                .field.additional:has(#${input.id}) .input[data-name~=${rule.connectedRules.join("-")}],
                .form:has(#${input.id}) .input[data-name~=${rule.connectedRules.join("-")}]
            `));
        }

        if (rule.nameInput != null) {
            const label = form.querySelector(`.field:not(.additional):has(#${input.id}) .label`);
            label.setAttribute("for", input.id)
            label.innerHTML = rule.nameInput.slice(0, 1).toUpperCase() + rule.nameInput.slice(1);

            if (rule.required) {
                label.innerHTML += "<b>*</b>";
            }
        }

        if (rule.length != null) {
            input.setAttribute("maxlength", rule.length);
        }

        if (rule.nameInput != null) {
            input.setAttribute("placeholder", `Введите ${rule.nameInput}`);
        }

        if (input.value != "" || input?.selectedIndex) {
            checkInput(input, rule);
        }

        input.addEventListener("change", () => checkInput(input, rule));
    });

    return validatedRules;
}

function setValidatedForm(form) {
    if (form == null) return;

    const inputs = form.querySelectorAll(".input:not(input[type=submit])");
    const validatedRules = setBasicSettingInput(inputs, form);

    if (["/adminEditItem.php", "/adminAddItem.php"].includes(window.location.pathname)) {
        setAdditional(form);
    }

    form.addEventListener("submit", (event) => {
        let hasUpdate = false;
        let hasError = false;

        Array.from(inputs).forEach((input) => {
            let isCorrect = checkInput(input, getInputRule(input, validatedRules));
            if (!isCorrect) {
                hasError = true;
            } else if (isCorrect && input?.name) {
                hasUpdate = true;
            }
            // input.name = getInputRule(input, validatedRules).nameRule;
        });

        // if (hasError || !hasUpdate) event.preventDefault();
    });
}

function getInputRule(input, validatedRules) {
    for (let keyRule in validatedRules) {
        if (validatedRules[keyRule]?.inputs?.includes(input)) {
            return validatedRules[keyRule];
        }
    }
}

function setAdditional(form) {
    const template = document.querySelector("template[data-max-count][data-count]");
    const additional = template.content.firstElementChild;
    const additionalButton = document.querySelector("button.additional");
    const insertPlace = form.querySelector(".field:has(.button.input)");
    const isEditFile = ["/adminEditItem.php"].includes(window.location.pathname);
    const maxCount = template.dataset.maxCount;
    let count = parseInt(template.dataset.count);

    additionalButton.addEventListener("click", (event) => {
        event.preventDefault();
        if (count >= maxCount) return;
        count++;
        const clone = additional.cloneNode(true);
        insertPlace.insertAdjacentElement("beforebegin", clone);

        const currentH2 = clone.querySelector("h2 b");
        currentH2.textContent = count;
        clone.querySelector(".button").addEventListener("click", () => {
            document.querySelectorAll(".additional.field h2 b").forEach((h2) => {
                if (h2.textContent > currentH2.textContent) {
                    h2.textContent--;
                }
            });
            count--;
            clone.remove();
        });

        setBasicSettingInput([...clone.querySelectorAll(".field .input")], form);
    });

    if (isEditFile) {
        const buttonsDelete = document.querySelectorAll(".field.additional .button");
        buttonsDelete?.forEach((button) => {
            const parent = document.querySelector(`.field.additional:has(.field[data-id='${button.parentElement.dataset.id}'])`);
            button.addEventListener("click", async () => {
                parent.classList.add("hidden");
                const resultData = await sendToServer({
                    "server_type": "delete_item_properties",
                    "id_items_properties": button.parentElement.dataset.id
                });
                if (resultData["status"] == "OK") {
                    parent.remove();
                    count--;
                    let tempCount = 1;
                    document.querySelectorAll(".field.additional h2 b").forEach((h2) => {
                        h2.textContent = tempCount++;
                    });
                } else {
                    parent.classList.remove("hidden");
                }
            });
        });
    }

    form.addEventListener("submit", (event) => {
        let additional = [];
        const id = form.querySelectorAll(".input[data-name='id_items_properties']");
        const name = form.querySelectorAll(".input[data-name='properties_id_items_properties']");
        const description = form.querySelectorAll(".input[data-name='description_items_properties']");

        for (let i = 0; i < name.length; i++) {
            let array = {};
            if (id[i] && id[i].value != "") {
                array["id_items_properties"] = id[i].value;
            }
            if (name[i].dataset.isInsertServer == 0 && name[i].value != "") {
                array["properties_id_items_properties"] = name[i].value;
            }
            if (description[i].dataset.isInsertServer == 0 && description[i].value != "") {
                array["description_items_properties"] = description[i].value;
            }

            if (Object.keys(array).length > 1) {
                additional.push(array);
            }
        }

        if (additional.length > 0) {
            const itemsProperties = form.querySelector(".input[data-name='items_properties']");
            itemsProperties.value = JSON.stringify(additional);
            itemsProperties.setAttribute("name", "items_properties");
        }
    });
}

let countInput = 0;
const formValidated = document.querySelectorAll(".form");
formValidated?.forEach((form) => {
    setValidatedForm(form);
});

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

function showModal(text) {
    const template = document.querySelector("template[data-is-show-modal]");
    if (!template || text == "") return;

    const modal = template.content.firstElementChild.cloneNode(true);
    const modalText = modal.querySelector("p");
    const modalButton = modal.querySelector(".button");
    
    document.body.appendChild(modal);
    modal.classList.remove("hidden");
    
    setTimeout(() => {
        modal.classList.remove("invisible");
    }, 0);

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