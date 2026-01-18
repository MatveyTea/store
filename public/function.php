<?php
include_once __DIR__ . "/config/config.php";
function redirect($path = "index.php")
{
    if (!file_exists(__DIR__ . "/$path")) {
        header("Location: /");
    } else {
        header("Location: $path");
    }
    exit;
}
function checkImage($img)
{
    if (!file_exists(__DIR__ . "/img/index/$img")) {
        return "/img/index/base.png";
    }
    return "/img/index/$img";
}
function isUserAuth()
{
    return !empty($_SESSION["id_user"]);
}
function getItems($limit = 20, $offset = 0, $where = "")
{
    $result = "";
    $items = [];
    $userItems = [];

    try {
        $items = $GLOBALS["link"]->prepare("SELECT
            `id_items`,
            `name_items`,
            `count_items`,
            `image_items`,
            `cost_items`
            FROM `items`
            $where
            ORDER BY `id_items` DESC, `id_items` DESC
            LIMIT :limit
            OFFSET :offset
        ");
        $items->bindParam(':limit', $limit, PDO::PARAM_INT);
        $items->bindParam(':offset', $offset, PDO::PARAM_INT);
        $items->execute();
        $items = $items->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {}

    if (isUserAuth()) {
        try {
            $userItems = $GLOBALS["link"]->prepare("SELECT `items_id_baskets`, `count_baskets` FROM `baskets` WHERE `users_id_baskets` = ? AND `status_id_baskets` = 1");
            $userItems->execute([$_SESSION["id_user"]]);
            $userItems = $userItems->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {}
    }

    foreach ($items as $item) {
        $img = checkImage($item["image_items"]);
        $basket = "";

        foreach ($userItems as $userItem) {
            if ($userItem["items_id_baskets"] == $item["id_items"]) {
                $basket = "<button class='basket' data-type='remove'>Убрать из корзины</button>
                <span class=''>
                    <button class='minus'>-</button>
                    <p>В корзине: <b>$userItem[count_baskets]</b></p>
                    <button class='plus'>+</button>
                </span>";
                break;
            }
        }

        if ($basket == "" && isUserAuth()) {
            $basket = "<button class='basket' data-type='add'>Добавить в корзину</button>
            <span class='hidden'>
                <button class='minus'>-</button>
                <p>В корзине: <b>0</b></p>
                <button class='plus'>+</button>
            </span>";
        }

        $result .= "<div data-id='$item[id_items]' data-count='$item[count_items]'>
        <p>ID: $item[id_items]<p>
            <img src='$img'>
            <p>$item[name_items]</p>
            <p>Количество: $item[count_items]</p>
            <p>Стоимость: $item[cost_items]р</p>
            $basket
        </div>";
    }

    return $result;
}
function getItemHTML($item)
{
    $result = "";
    if ($item == null) return $result;
    $img = checkImage($item["image_items"]);
    $result .= "<div class='item' data-id='$item[items_id_baskets]' data-count='$item[count_baskets]'>
        <img src='$img'>
        <p>$item[name_items]</p>
        <p>Количество: $item[count_baskets]</p>
        <p>Стоимость: $item[cost_items]р</p>
    </div>";
    return $result;
}

function dateformat($datetime = null)
{
    $months = ["января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"];
    if ($datetime == null) {
        $datetime = time();
    }
    preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/", $datetime, $array);
    return "$array[4]:$array[5], " . intval($array[3]) . " " . $months[$array[2] - 1] . " $array[1]";
}

function getValidationRules(): array
{
    $result = [];
    $file = basename($_SERVER["SCRIPT_NAME"]);

    $rules = [
        "name_users" => [
            "files" => ["reg.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/^[а-яёА-Я Ё-]{1,30}$/u", $value);
            }
        ],
        "email_users" => [
            "files" => ["reg.php", "auth.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/^[A-Za-z0-9._%+-]{1,50}@[A-Za-z0-9.-]{1,15}\.[A-Za-z]{1,15}$/", $value);
            },
        ],
        "password_users" => [
            "files" => ["reg.php", "auth.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/", $value);
            }
        ],
        "re_password_users" => [
            "files" => ["reg.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/", $value);
            }
        ],
        "name_items" => [
            "files" => ["admin.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/^[a-zA-Z0-9 -().,:\"'%]{1,40}$/", $value);
            }
        ],
        "count_items" => [
            "files" => ["admin.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/^[0-9]{1,6}$/", $value);
            }
        ],
        "cost_items" => [
            "files" => ["admin.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/^[0-9]{1,6}$/", $value);
            }
        ],
        "image_items" => [
            "files" => ["admin.php"],
            "required" => false,
            "pattern" => function($value) {
                $extension = pathinfo($value["name"], PATHINFO_EXTENSION);
                if (in_array($extension, ["jpg", "png", "webp"]) && $value["size"] < 2_000_000) {
                    $datetime = date("Y-m-d H-i-s") . ".$extension";
                    if (move_uploaded_file($value["tmp_name"], __DIR__ . "/img/index/$datetime")) {
                        return $datetime;
                    }
                }
                return false;
            }
        ]
    ];

    foreach ($rules as $key => $rule) {
        if (in_array($file, $rule["files"])) {
            $result[$key] = $rule;
        }
    }
    return $result;
}
function getValidatedData($array): array
{
    $result = [
        "data" => [],
        "errorField" => [],
        "isCorrect" => false
    ];

    if ($array == null) return $result;

    $validationRules = getValidationRules();
    if (empty($validationRules)) return $result;

    $currentCountCorrect = 0;
    foreach ($array as $key => $value) {
        if (empty($validationRules[$key])) {
            continue;
        }

        $rule = $validationRules[$key];

        if ($key == "image_items" && is_callable($rule["pattern"])) {
            $image = $rule["pattern"]($value);
            if ($image !== false) {
                $result["errorField"][$key] = 0;
                $result["data"][$key] = $image;
                $currentCountCorrect++;
            } else {
                $result["errorField"][$key] = 1;
            }
            continue;
        }

        $value = trim($value);
        $result["data"][$key] = $value;

        if (!$rule["required"] && $value == "") {
            $currentCountCorrect++;
            $result["errorField"][$key] = 0;
            continue;
        }

        if ($rule["required"] && $value == "" ||
            !empty($array["re_password_users"]) &&
            ($key == "re_password_users" && $value != $array["password_users"] || $key == "password_users" && $value != $array["re_password_users"]) ||
            is_callable($rule["pattern"]) && !$rule["pattern"]($value))
        {
            $result["errorField"][$key] = 1;
            continue;
        } else {
            $result["errorField"][$key] = 0;
        }

        $currentCountCorrect++;
    }

    $result["isCorrect"] = $currentCountCorrect == count($array);
    return $result;
}


// На тест
function createRandomItem()
{
    $id = $GLOBALS["link"]->query("SELECT MAX(`id_items`) AS `max_id` FROM `items`")->fetch(PDO::FETCH_ASSOC)["max_id"] ?? 0;
    for ($i = $id + 1; $i < $id + 100; $i++) {
        $r1 = rand(5, 30);
        $r2 = rand(10, 1000);
        $time = date("Y-m-d");
        $GLOBALS["link"]->query("
            INSERT INTO `items`
            (`id_items`, `name_items`, `count_items`, `image_items`, `cost_items`, `date_add_items`)
            VALUES
            ($i, 'Товар $i', $r1, 'default.png', $r2, '$time')
        ");
    }
}