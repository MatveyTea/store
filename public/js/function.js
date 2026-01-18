"use strict";

function getValidationRules() {
    let result = [];
    const file = window.location.pathname == "/" ? "/" : window.location.pathname.split("/")[1];

    const rules = {
        "name_users" : {
            "files" : ["reg.php"], 
            "required" : true,
            "timer": null,
            "placeMsg": null,
            "length" : 30,
            "pattern" : function(input) {
                return /^[а-яёА-ЯЁ-]{1,30}$/u.test(input.value) ? false : "Введите только кириллические символы, 1-30 символов.";
            }
        },
        "email_users" : {
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
            "files" : ["reg.php", "auth.php"], 
            "required" : true,
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
            "files" : ["reg.php"], 
            "required" : true,
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
            "files" : ["admin.php"], 
            "required" : true,
            "timer": null,
            "length" : 40,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (-().,:\"'%), 1-40 символов.";
            }
        },
        "name_search_items" : {
            "files" : [ "/"], 
            "required" : false,
            "timer": null,
            "length" : 40,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/.test(input.value) ? false : "Введите латинские символы, цифры или допустимые символы (-().,:\"'%), 1-40 символов.";
            }
        },
        "count_items" : {
            "files" : ["admin.php"], 
            "required" : true,
            "timer": null,
            "length" : 6,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "cost_items" : {
            "files" : ["admin.php"], 
            "required" : false,
            "timer": null,
            "length" : 6,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "min_cost_items" : {
            "files" : ["/"], 
            "required" : false,
            "timer": null,
            "length" : 6,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "max_cost_items" : {
            "files" : ["/"], 
            "required" : false,
            "timer": null,
            "length" : 6,
            "placeMsg": null,
            "pattern" : function(input) {
                return /^[0-9]{1,6}$/.test(input.value) ? false : "Введите число до 999 999";
            }
        },
        "image_items" : {
            "files" : ["admin.php"], 
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
    const { required, pattern, placeMsg } = rule;

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

    return isCorrect;
}

function setValidatedForm(form) {
    if (form == null) return;

    const validatedRules = getValidationRules();
    if (validatedRules == []) return;

    const errorPlaces = form.querySelectorAll("p.error");
    const inputs = form.querySelectorAll("input:not(input[type=submit])");
    Array.from(inputs).forEach((input, index) => {
        const id = input.id;

        validatedRules[id].placeMsg = errorPlaces[index];
        const rule = validatedRules[id];

        if (rule.required) {

            const label = form.querySelector(`.field:has(#${input.id}) .label`);
            label.innerHTML += "<b>*</b>";
        }

        if (rule.length != null) {
            input.setAttribute("maxlength", rule.length);
        }

        if (errorPlaces[index].dataset.hasError == 1) {
            checkInput(input, rule);
        }
        errorPlaces[index].removeAttribute("data-has-error");

        input.addEventListener("change", () => checkInput(input, rule));
    });

    Array.from(errorPlaces).forEach((place) => {
        place.classList.toggle("hidden", place.textContent == "");
    });

    form.addEventListener("submit", () => {

    });
}
setValidatedForm(document.querySelector(".form"));