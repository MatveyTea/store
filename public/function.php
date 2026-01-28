<?php
include_once __DIR__ . "/config/config.php";

const FOLDER_PROFILE = "profile";
const FOLDER_INDEX = "index";
const FOLDER_IMG = "img";
const FOLDER_MAIN = "main";

function redirect($path = "index.php")
{
    if (!file_exists(__DIR__ . "/$path")) {
        header("Location: /");
    } else {
        header("Location: $path");
    }
    exit;
}
function getValidImage($folder, $img)
{
    if ($img == "" || !file_exists(__DIR__ . "/" . FOLDER_IMG . "/$folder/$img")) {
        return "/" . FOLDER_IMG . "/$folder/default.png";
    }
    return "/" . FOLDER_IMG . "/$folder/$img";
}
function isUserAuth()
{
    return !empty(getUserID());
}
function isAdmin()
{
    return getUserID() == 1;
}
function getUserInfo()
{
    $stmt = $GLOBALS["link"]->prepare("SELECT * FROM `users` WHERE `id_users` = ?");
    $stmt->execute([getUserID()]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function clearValidatedSession()
{
    unset($_SESSION["errorField"], $_SESSION["data"]);
}

function getUserID()
{
    return $_SESSION["id_user"] ?? null;
}

function makeSelectQuery($query, $params, $getOne = false) {
    $stmt = $GLOBALS["link"]->prepare($query);
    try {
        $stmt->execute($params); 
    } catch (Throwable $e) {
        return [];
    }
    if ($getOne) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

function makeInsertQuery($query, $params) {
    $stmt = $GLOBALS["link"]->prepare($query);
    try {
        $stmt->execute($params);
        return true;
    } catch (Throwable $e) {
        return false;
    }
}

function getItems($limit = 50, $offset = 0, $where = "")
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
            $userItems->execute([getUserID()]);
            $userItems = $userItems->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {}
    }

    foreach ($items as $item) {
        $img = getValidImage(FOLDER_INDEX, $item["image_items"]);
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
            $basket = "<button class='button basket' data-type='add'>Добавить в корзину</button>
            <span class='hidden'>
                <button class='minus'>-</button>
                <p>В корзине: <b>0</b></p>
                <button class='plus'>+</button>
            </span>";
        }

        $result .= "<a href ='aboutItem.php?id_item=$item[id_items]' data-id='$item[id_items]' data-count='$item[count_items]'>
        <p>ID: $item[id_items]<p>
            <img src='$img'>
            <p>$item[name_items]</p>
            <p>Количество: $item[count_items]</p>
            <p>Стоимость: $item[cost_items]р</p>
            $basket
        </a>";
    }

    return $result;
}
function getItemHTML($item)
{
    $result = "";
    if ($item == null) return $result;
    $img = getValidImage(FOLDER_INDEX, $item["image_items"]);
    $result .= "<div class='item' data-id='$item[id_items]' data-count='$item[count_baskets]'>
        <img src='$img'>
        <p>$item[name_items]</p>
        <p>Количество: $item[count_baskets]</p>
        <p>Стоимость: $item[cost_items]р</p>
    </div>";
    return $result;
}

function getBasketHTML($basket)
{
    $historyHTML = "";
    $currentHTML = "";
    $currentCost = 0;
    $historyCost = 0;

    $datetimeBuy = null;
    $lastUserItem = end($basket);
    foreach ($basket as $item) {
        if (empty($item["datetime_buy_baskets"])) {
            $currentHTML .= getItemHTML($item);
            $currentCost += $item["cost_items"] * $item["count_baskets"];
        } else {
            if ($datetimeBuy != $item["datetime_buy_baskets"]) {
                if ($datetimeBuy != null) {
                    $historyHTML .= "<p>Всего: {$historyCost}р</p></article>";
                    $historyCost = 0;
                }
                $historyHTML .= "<article class='basket'><h2>Время покупки: " . dateformat($item["datetime_buy_baskets"]) . "</h2>";
                $datetimeBuy = $item["datetime_buy_baskets"];
            }
            $historyHTML .= getItemHTML($item);
            $historyCost += $item["cost_items"] * $item["count_baskets"];
            if ($item["id_baskets"] == $lastUserItem["id_baskets"]) {
                $historyHTML .= "<p>Всего: {$historyCost}р</p></article>";
            }
        }
    }
    return ["currentHTML" => $currentHTML, "historyHTML" => $historyHTML, "currentCost" => $currentCost];
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
            "files" => ["reg.php", "profile.php"], 
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
                return preg_match("/^[а-яА-Яa-zA-Z0-9 -().,:\"'%]{1,40}$/", $value);
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
                    $datetime = date("Y-m-d-H-i-s") . ".$extension";
                    if (move_uploaded_file($value["tmp_name"], __DIR__ . "/img/index/$datetime")) {
                        return $datetime;
                    }
                }
                return false;
            }
        ],
        "avatar_users" => [
            "files" => ["profile.php"],
            "required" => false,
            "pattern" => function($value) {
                $extension = pathinfo($value["name"], PATHINFO_EXTENSION);
                if (in_array($extension, ["jpg", "png", "webp"]) && $value["size"] < 2_000_000) {
                    $datetime = date("y-m-d-H-i-s") . ".$extension";
                    if (move_uploaded_file($value["tmp_name"], __DIR__ . "/img/profile/$datetime")) {
                        return $datetime;
                    }
                }
                return false;
            }
        ],
        "text_comments" => [
            "files" => ["server.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/.{1,255}$/", $value);
            }
        ],
        "rating_comments" => [
            "files" => ["server.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/[1-5]$/", $value);
            }
        ],
        "id_items" => [
            "files" => ["server.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/[0-9]{1,}$/", $value);
            }
        ],
        "items_properties" => [
            "files" => ["admin.php"], 
            "required" => false,
            "pattern" => function($value) {
                $isCorrect = true;
                $properties = json_decode($value, true);
                foreach ($properties as $property) {
                    if (!preg_match("/[0-9]{1,}$/", $property["name"]) ||
                        !preg_match("/^[а-яА-Яa-zA-Z0-9 -().,:\"'%]{1,40}$/", $property["description"])) {
                        $isCorrect = false;
                        break;
                    }
                }
                if ($isCorrect) {
                    return $properties;
                }
                return false;
            }
        ],
        "items_type_id_items" => [
            "files" => ["admin.php"], 
            "required" => true,
            "pattern" => function($value) {
                return preg_match("/[0-9]{1,}$/", $value);
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
            $result["emptyRule"] = $key; // дебаг
            continue;
        }

        $rule = $validationRules[$key];

        if (($key == "image_items" || $key == "avatar_users" || $key == "items_properties") && is_callable($rule["pattern"])) {  
            $image = $rule["pattern"]($value);
            if ($image !== false) {
                $result["errorField"][$key] = 0;
                $result["data"][$key] = $image;
                $currentCountCorrect++;
            } else {
                $result["errorField"][$key] = 1;
                if ($key == "items_properties") {
                    $result["data"][$key] = json_decode($value, true);
                }
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


    $hasAllRule = true;
    foreach ($validationRules as $key => $rule) {
        if (!key_exists($key, $array) && $rule["required"]) {
            $result["errorField"][$key] = 1;
            $hasAllRule = false;
        }
    }

    $result["isCorrect"] = $currentCountCorrect == count($array) && $hasAllRule;
    return $result;
}


function getUpdateSQL($array) {
    $result = [
        "params" => [],
        "sql" => []
    ];


    if ($array == null) return $result;

    foreach ($array as $key => $value) {
        if (empty($value)) continue;
        $result["params"][] = $value;
        $result["sql"][] = "`$key` = ?";
    }

    $result["sql"] = join(",", $result["sql"]);
    
    return $result;
}

function getInsertSQL($array)
{
    $result = [
        "question" => [],
        "params" => [],
        "sql" => []
    ];

    foreach ($array as $key => $value) {
        if (!empty($value)) {
            $result["params"][] = $value;
        } else {
            $result["params"][] = 123;
        }
        $result["question"][] = "?";

        $result["sql"][] = "`$key`";
    }

    $result["question"] = join(",", $result['question']);
    $result["sql"] = join(",", $result["sql"]);
    
    return $result;
}


function getCommentsHTML($comments)
{
    $commentsHTML = "";
    $star = "<svg width='24' height='24' fill='#FFD700'>
    <use xlink:href='" . FOLDER_IMG . "/" . FOLDER_MAIN . "/star.svg#star'></use>
    </svg>";

    foreach ($comments as $comment) {
        $img = getValidImage(FOLDER_PROFILE, $comment["avatar_users"]);
        $rating = str_repeat($star, $comment["rating_comments"]);
        $commentsHTML .= "<div class='comment'>
            <img class='avatar' src='$img'>
            <p class='comment-username'>$comment[name_users]</p>
            <p class='comment-date'>$comment[date_add_comments]</p>
            <p class='comment-text'>$comment[text_comments]</p>
            <span class='comment-rating'>$rating</span>
        </div>";
    }

    return $commentsHTML;
}

function getRatingItem($idItem)
{
    return makeSelectQuery("SELECT
        ROUND(AVG(`rating_comments`), 1) AS `rating`
        FROM `comments`
        WHERE `items_id_comments` = ?
    ", [$idItem], true)["rating"];
}

// На тест
// function createRandomItem()
// {
//     $id = $GLOBALS["link"]->query("SELECT MAX(`id_items`) AS `max_id` FROM `items`")->fetch(PDO::FETCH_ASSOC)["max_id"] ?? 0;
//     for ($i = $id + 1; $i < $id + 100; $i++) {
//         $r1 = rand(5, 30);
//         $r2 = rand(10, 1000);
//         $time = date("Y-m-d");
//         $GLOBALS["link"]->query("
//             INSERT INTO `items`
//             (`id_items`, `name_items`, `count_items`, `image_items`, `cost_items`, `date_add_items`)
//             VALUES
//             ($i, 'Товар $i', $r1, 'default.png', $r2, '$time')
//         ");
//     }
// }