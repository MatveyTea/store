"use strict";

const buyButton = document.querySelector("button.buy");
const historyBasket = document.querySelector(".orders:not(.current)");
const currentBasket = document.querySelector(".orders.current");
const cancelBasket = document.querySelector(".cancel-basket");

buyButton?.addEventListener("click", async () => {
    const makeOrder = document.querySelector(".make-order");
    makeOrder.classList.remove("hidden");
    makeOrder.style.top = `${scrollY}px`;

    makeOrder.querySelector(".form").addEventListener("submit", async (event) => {
        event.preventDefault();
        const resultData = await sendToServer({
            "server_type": "buy_items",
            "address_orders": {
                "street": makeOrder.querySelector(".input[data-name='street_address_orders']").value,
                "home": makeOrder.querySelector(".input[data-name='home_address_orders']").value,
                "number": makeOrder.querySelector(".input[data-name='number_address_orders']").value,
            },
            "note_orders": makeOrder.querySelector(".input[data-name='note_orders']").value,
            "datetime_plan_orders": makeOrder.querySelector(".input[data-name='datetime_plan_orders']").value.split(", ")[1]
        });
        if (resultData["status"] == "OK") {
            historyBasket.querySelector("h2").textContent = "История покупок";
            historyBasket.querySelector("h2").insertAdjacentHTML("afterend", resultData["data"]["historyHTML"]);
            currentBasket.innerHTML = "<h2 class='notfound'>В данный момент в корзине пусто.</h2>";
            makeOrder.classList.remove();
            showModal("Успешно")
        } else {
            showModal("Не удалось купить");
        }
    });
});

cancelBasket?.addEventListener("click", (event) => {
    event.preventDefault();
    const makeOrder = document.querySelector(".make-order");
    makeOrder.classList.add("hidden");
    makeOrder.querySelector(".form").reset();
});