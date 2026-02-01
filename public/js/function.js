"use strict";

function getValidationRules() {
    let result = [];
    const file = window.location.pathname == "/" ? "/" : window.location.pathname.split("/")[1];

    const rules = {
        "name_users" : {
            "oldValue": null,
            "files" : ["reg.php", "profile.php"], 
            "required" : file == "profile.php" ? false : true,
            "timer": null,
            "placeMsg": null,
            "length" : 30,
            "pattern" : function(input) {
                return /^[а-яёА-ЯЁ-]{1,30}$/u.test(input.value) ? false : "Введите только кириллические символы, 1-30 символов.";
            }
        },
        "email_users" : {
            "oldValue": null,
            "files" : ["reg.php", "auth.php"], 
            "required" : true,
            "timer": null,
            "length" : 30,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[A-Za-z0-9._%+-]{1,50}@[A-Za-z0-9.-]{1,15}\.[A-Za-z]{1,15}$/.test(input.value) ? false : "Введите действительный email-адрес до 80 символов.";
            },
        },
        "password_users" : {
            "oldValue": null,
            "files" : ["reg.php", "auth.php", "profile.php"], 
            "required" : file == "profile.php" ? false : true,
            "timer": null,
            "length" : 40,
            "placeMsg": null,
            "pattern" : function(input) {
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
        "re_password_users" : {
            "oldValue": null,
            "files" : ["reg.php", "profile.php"], 
            "required" : file == "profile.php" ? false : true,
            "timer": null,
            "length" : 40,
            "placeMsg": null,
            "additionalMsg" : function(input) {
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
        "name_items" : {
            "oldValue": null,
            "files" : ["adminAddItem.php"], 
            "required" : true,
            "timer": null,
            "length" : 40,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (-().,:\"'%), 1-40 символов.";
            }
        },
        "name_search_items" : {
            "oldValue": null,
            "files" : ["/", "index.php"], 
            "required" : false,
            "timer": null,
            "length" : 40,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (-().,:\"'%), 1-40 символов.";
            }
        },
        "count_items" : {
            "oldValue": null,
            "files" : ["adminAddItem.php"], 
            "required" : true,
            "timer": null,
            "length" : 6,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "cost_items" : {
            "oldValue": null,
            "files" : ["adminAddItem.php"], 
            "required" : true,
            "timer": null,
            "length" : 6,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "min_cost_items" : {
            "oldValue": null,
            "files" : ["/", "index.php"], 
            "required" : false,
            "timer": null,
            "length" : 6,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "max_cost_items" : {
            "oldValue": null,
            "files" : ["/", "index.php"], 
            "required" : false,
            "timer": null,
            "length" : 6,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "image_items" : {
            "oldValue": null,
            "files" : ["adminAddItem.php"], 
            "required" : false,
            "timer": null,
            "length" : 1,
            "placeMsg": null,
            "pattern" : function(input) {
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
        "avatar_users" : {
            "oldValue": null,
            "files" : ["profile.php"], 
            "required" : false,
            "timer": null,
            "length" : 1,
            "placeMsg": null,
            "pattern" : function(input) {
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
            "files" : ["aboutItem.php"], 
            "required" : false,
            "timer": null,
            "length" : 255,
            "placeMsg": null,
            "pattern" : function(input) {
                return false; ///^.{1-255}$/.test(input.value) ? false : "Введите текст до 255 символов";
            }
        },
        "rating_comments": {
            "oldValue": null,
            "files" : ["aboutItem.php"], 
            "required" : true,
            "timer": null,
            "length" : 255,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[1-5]$/.test(input.value) ? false : "Введите число от 1 до 5";
            }
        },
        "items_properties_name": {
            "oldValue": null,
            "files" : ["adminAddItem.php"], 
            "required" : true,
            "timer": null,
            "length" : 255,
            "placeMsg": null,
            "pattern" : function(input) {
                return input.selectedIndex > 0 ? false : "Выберите элемент";
            }
        },
        "items_properties_description": {
            "oldValue": null,
            "files" : ["adminAddItem.php"], 
            "required" : true,
            "timer": null,
            "length" : 255,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (-().,:\"'%), 1-40 символов.";
            }
        },
        "items_type_id_items": {
            "oldValue": null,
            "files" : ["adminAddItem.php"], 
            "required" : true,
            "timer": null,
            "length" : 255,
            "placeMsg": null,
            "pattern" : function(input) {
                return input.selectedIndex > 0 ? false : "Выберите элемент";
            }
        },
        "table": {
            "oldValue": null,
            "files" : ["adminTable.php"], 
            "required" : true,
            "timer": null,
            "length" : 255,
            "placeMsg": null,
            "pattern" : function(input) {
                return false;
            }
        },
        "id_properties": {
            "oldValue": null,
            "files" : ["adminTable.php"], 
            "required" : true,
            "timer": null,
            "length" : 255,
            "placeMsg": null,
            "pattern" : function(input) {
                return false;
            }
        },
        "name_properties": {
            "oldValue": null,
            "files" : ["adminTable.php"], 
            "required" : true,
            "timer": null,
            "length" : 255,
            "placeMsg": null,
            "pattern" : function(input) {
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
            rule.placeMsg.textContent = textMessage;
        }, 300);
    } else {
        placeMsg.textContent = textMessage;
    }

    placeMsg.classList.toggle("hidden", isCorrect);

    if (isCorrect && (oldValue != input.value || (input?.files != null && input?.files?.length != 0))) {
        input.setAttribute("name", input.name);
    } else {
        input.removeAttribute("name");
    }

    return isCorrect;
}

function setValidatedForm(form) {
 
    if (form == null) return;

    const validatedRules = getValidationRules();
    if (validatedRules == []) return;

    const errorPlaces = form.querySelectorAll("p.error");
    const inputs = form.querySelectorAll(".input:not(input[type=submit])");
    Array.from(inputs).forEach((input, index) => {
        const id = input.name;

        // console.log(id, input);
        const rule = validatedRules[id];
        // console.log(id, input, rule);
        rule.oldValue = input.value
        rule.placeMsg = errorPlaces[index];

        if (rule.required) {
            const label = form.querySelector(`.field:not(.additional):has(.input[name=${id}]) .label`);
            label.innerHTML += "<b>*</b>";
        }

        if (rule.length != null) {
            input.setAttribute("maxlength", rule.length);
        }

        if (errorPlaces[index].dataset?.hasError == 1) {
            checkInput(input, rule);
        }
        errorPlaces[index].removeAttribute("data-has-error");

        if (input.value != "") {
            input.setAttribute("name", id);
        }

        input.addEventListener("change", () => checkInput(input, rule));
    });

    Array.from(errorPlaces).forEach((place) => {
        place.classList.toggle("hidden", place.textContent == "");
    });

    form.addEventListener("submit", (event) => {
        let hasUpdate = false;
        let hasError = false;
        Array.from(inputs).forEach((input) => {
            let isCorrect = checkInput(input,  validatedRules[input.name]);
            if (!isCorrect) {
                hasError = true;
            } else if (isCorrect && input?.name){
                hasUpdate = true;
            }
        });
        //if (hasError || !hasUpdate) event.preventDefault();
    });
}

setValidatedForm(document.querySelector(".form"));