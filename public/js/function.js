"use strict";

function getValidationRules() {
    let result = [];
    const file = window.location.pathname.split("/")[1];

    const rules = {
        "name_users" : {
            "files" : ["reg.php"], 
            "required" : true,
            "field" : "имя",
            "timer": null,
            "length" : 30,
            "placeMsg": null,
            "pattern" : /^[а-яёА-ЯЁ-]{1,30}$/u,
            "patternMsg" : "Введите только кириллические символы, 1-30 символов.",
            "additional" : null,
            "additionalMsg" : null
        },
        "email_users" : {
            "files" : ["reg.php"], 
            "required" : true,
            "field" : "почта",
            "timer": null,
            "length" : 30,
            "placeMsg": null,
            "pattern" :  /^[A-Za-z0-9._%+-]{1,50}@[A-Za-z0-9.-]{1,15}\.[A-Za-z]{1,15}$/,
            "patternMsg" : "Введите действительный email-адрес до 80 символов.",
            "additional" : null,
            "additionalMsg" : null,
        },
        "password_users" : {
            "files" : ["reg.php", "auth.php"], 
            "required" : true,
            "field" : "пароль",
            "timer": null,
            "length" : 40,
            "placeMsg": null,
            "pattern" : /^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/,
            "patternMsg" : "Введите латинские символы, цифры или допустимые символы (@#$%^&*()_+-={}\:;\"'<>,.?\/), 1-40 символов.",
            "additional" : null,
            "additionalMsg" : null
        },
        "re_password_users" : {
            "files" : ["reg.php"], 
            "required" : true,
            "field" : "пароль",
            "timer": null,
            "length" : 40,
            "placeMsg": null,
            "pattern" : /^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/,
            "patternMsg" : "Введите латинские символы, цифры или допустимые символы (@#$%^&*()_+-={}\:;\"'<>,.?\/), 1-40 символов.",
            "additional" : null,
            "additionalMsg" : null,
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
    const id = input.id;
    const { required, pattern, placeMsg, patternMsg, additional, additionalMsg } = rule;

    if (required || (!required && input.value != "")) {
        if (pattern != null && !pattern.test(input.value)) {
            textMessage = patternMsg;
            isCorrect = false;
        } else if (additional != null && !additional(input)) {
            textMessage = additionalMsg;
            isCorrect = false;
        } 
    }

    if (id.includes("password_users")) {
        const rePassword = document.querySelector("input[name=re_password_users");
        const password = document.querySelector("input[name=password_users");
        const passwordPlaceError = password.parentElement.querySelector(".error");
        const rePasswordPlaceError = rePassword.parentElement.querySelector(".error");
        if (id == "password_users" && input.value != rePassword.value && rePassword.value != "" ||
            id == "re_password_users" && input.value != password.value
        ) {
            passwordPlaceError.textContent = "Пароли должны совпадать!";
            rePasswordPlaceError.textContent = "Пароли должны совпадать!";
            passwordPlaceError.classList.remove("hidden");
            rePasswordPlaceError.classList.remove("hidden");
                    return;
        } else if (rePassword.value == password.value && password.value != "") {
            passwordPlaceError.textContent = "";
            rePasswordPlaceError.textContent = "";
            passwordPlaceError.classList.add("hidden");
            rePasswordPlaceError.classList.add("hidden");
                    return;
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
            const label = input.parentElement.querySelector(".label");
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