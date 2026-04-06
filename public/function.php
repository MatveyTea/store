<?php
include_once __DIR__ . "/config/config.php";

const FOLDER_IMG = "img";
const FOLDER_MAIN = "main";
const FOLDER_UPLOAD = "upload";
const FOLDER_AVATARS = "avatars";
const FOLDER_ITEMS = "items";

function getModalHTML()
{
    $text = $_SESSION["server"] ?? "";
    clearValidatedSession();
    echo "<template data-is-show-modal='" . ($text == "" ? 0 : 1) . "' data-text='$text'>
        <div class='modal hidden invisible'>
            <span>
                <h1>Уведомление</h1>
                <p></p>
                <button class='button'>Закрыть</button>
            </span>
        </div>
    </template>";
}

function getAdditionalSelectHTML($isInsertServer, $allPropertiesHTML, $allAttributesHTML, $value, $dataValue = [])
{
    $startIndexValue = strpos($allPropertiesHTML, "value='$value'");
    $selectSelected = substr($allPropertiesHTML, 0, $startIndexValue) . " selected " . substr($allPropertiesHTML, $startIndexValue);

    if (!empty($dataValue)) {
        $dataValue = "data-value='" . join("|", $dataValue) . "'";
    }
    return "<div class='field additional'>
                <div class='field'>
                    <label class='label'></label>
                    <select class='input' data-name='attributes_select_property' data-is-insert-server='$isInsertServer' $dataValue>
                        $selectSelected
                    </select>
                    <p class='error'></p>
                </div>
                <div class='field'>
                    $allAttributesHTML
                </div>
                <div class='field'>
                    <button class='button' data-id-property='$value'>Удалить</button>
                </div>
            </div>
        ";
}


function getAdditionalTemplateHTML($allPropertiesHTML, $allAttributesHTML, $idItem = -1)
{
    $max = makeSelectQuery("SELECT COUNT(*) as `count` FROM `properties`", [], true);
    if ($max == "FAIL") {
        $max = [];
        $max["count"] = 0;
    }

    $current = [];
    if ($idItem != -1) {
        $current = makeSelectQuery("SELECT
            COUNT(DISTINCT `attributes`.`properties_id_attributes`) as `count`
            FROM `items_properties`
            JOIN `attributes` ON `attributes`.`id_attributes` = `items_properties`.`attributes_id_items_properties`
            JOIN `properties` ON `properties`.`id_properties` = `attributes`.`properties_id_attributes`
            WHERE `items_properties`.`items_id_items_properties` = ?
            ", [$idItem], true
        );
        if ($current == "FAIL") {
            $current = [];
            $current["count"] = 0;
        }
    } else {
        $current["count"] = 0;
    }

    echo "<template data-max-count='$max[count]' data-current-count='$current[count]'>
        <div class='field additional'>
            <div class='field'>
                <label class='label'></label>
                <select class='input' data-name='attributes_select_property' data-is-insert-server='1'>
                    <option selected disabled>Выбрать</option>
                    $allPropertiesHTML
                </select>
            </div>
            <div class='field'>
                $allAttributesHTML
            </div>
            <div class='field'>
                <button class='button'>Удалить</button>
            </div>
        </div>
    </template>";
}

function redirect($path = "index.php", $params = "")
{
    if (!file_exists(__DIR__ . "/$path")) {
        header("Location: /");
    } else {
        $fullPath = $params == "" ? $path : "$path?$params";
        header("Location: $fullPath");
    }
    exit;
}

function redirectYourself($params = "")
{
    redirect(basename($_SERVER["SCRIPT_FILENAME"]), $params);
}

function getValidImage($folder, $img)
{
    if ($img == "" || !file_exists(__DIR__ . "/" . FOLDER_IMG . "/$folder/$img")) {
        return "/" . FOLDER_IMG . "/" . FOLDER_MAIN . "/default.png";
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
    $userInfo = makeSelectQuery("SELECT * FROM `users` WHERE `id_users` = ?", [getUserID()], true);
    return $userInfo == "FAIL" ? [] : $userInfo;
}

function clearValidatedSession()
{
    unset($_SESSION["server"], $_SESSION["data"]);
}

function getUserID()
{
    return $_SESSION["id_user"] ?? null;
}

function makeSelectQuery($query, $params = [], $getOne = false)
{
    $stmt = $GLOBALS["link"]->prepare($query);
    try {
        $stmt->execute($params);
    } catch (Throwable $e) {
        return "FAIL";
    }
    if ($getOne) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

function makeSafeQuery($query, $params)
{
    if ($query == "") return false;
    try {
        $GLOBALS["link"]->prepare($query)->execute($params);
        return true;
    } catch (Throwable $e) {
        return false;
    }
}

function getItems($offset = 0, $whereSQL = "", $whereParams = [], $isPopularItems = false, $limit = 50)
{
    $result = "";
    $items = [];
    $userItems = [];

    $orderBy = $isPopularItems ? "`views_items` DESC," : "";
    $items = makeSelectQuery(
        "SELECT
        `id_items`,
        `name_items`,
        `count_items`,
        `image_items`,
        `cost_items`
        FROM `items`
        LEFT JOIN `items_properties` ON `items_id_items_properties` = `id_items`
        LEFT JOIN `attributes` ON `id_attributes` = `attributes_id_items_properties`
        $whereSQL
        GROUP BY `id_items`
        ORDER BY $orderBy `id_items` DESC
        LIMIT $limit OFFSET $offset
        ", $whereParams, false
    );
    if ($items == "FAIL") return $result;

    if (isUserAuth()) {
        $userItems = makeSelectQuery(
            "SELECT
            `items_id_baskets`, `count_baskets`
            FROM `baskets`
            WHERE `users_id_baskets` = ? AND `status_id_baskets` = ?
            ", [getUserID(), 1], false
        );
        if ($userItems == "FAIL") return $result;
    }

    $result = getItemsHTML($items, $userItems);

    return $result;
}

function getItemsHTML($items, $userItems)
{
    $result = "";
    foreach ($items as $item) {
        $basket = "";
        foreach ($userItems as $userItem) {
            if ($userItem["items_id_baskets"] == $item["id_items"]) {
                $basket = "<button class='item-basket button green' data-type='remove'>Убрать из корзины</button>
                    <span class='item-counter-container'>
                        <button class='item-counter-minus button'>-</button>
                        <p>В корзине: <b class='item-counter-text'>$userItem[count_baskets]</b></p>
                        <button class='item-counter-plus button'>+</button>
                    </span>
                ";
                break;
            }
        }

        if ($basket == "" && isUserAuth()) {
            $basket = "<button class='item-basket button green' data-type='add'>Добавить в корзину</button>
                <span class='invisible item-counter-container'>
                    <button class='item-counter-minus button'>-</button>
                    <p>В корзине: <b class='item-counter-text'>0</b></p>
                    <button class='item-counter-plus button'>+</button>
                </span>
            ";
        }

        $result .= "<span class='item' data-id='$item[id_items]' data-count='$item[count_items]'>
                <a href ='aboutItem.php?id_item=$item[id_items]' class='item-link' >
                    <p>№ $item[id_items]</p>
                    <img src='" . getValidImage(FOLDER_UPLOAD . "/" . FOLDER_ITEMS, $item["image_items"]) . "' class='item-image'>
                    <p class='item-name'>$item[name_items]</p>
                    <p class='item-cost'>Стоимость: $item[cost_items]р</p>
                </a>
                $basket
            </span>
        ";
    }
    return $result;
}

function getItemHTML($item)
{
    $result = "";
    if ($item == null) return $result;
    $img = getValidImage(FOLDER_UPLOAD . "/" . FOLDER_ITEMS, $item["image_items"]);
    $result .= "<a href='aboutItem.php?id_item=$item[id_items]' class='item' data-id='$item[id_items]' data-count='$item[count_baskets]'>
        <img src='$img' class='item-image'>
        <p class='item-name'>$item[name_items]</p>
        <p class='item-count'>Количество: $item[count_baskets]</p>
        <p class='item-cost'>Стоимость: $item[cost_items]р</p>
    </a>";
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
                    $historyHTML .= "</div>
                        <p>Всего: {$historyCost}р</p>
                        <a href='deliveryItem.php?datetime=$datetimeBuy' class='button'>Информация о доставке</a>
                        </article>
                    ";
                    $historyCost = 0;
                }
                $historyHTML .= "<article class='basket'>
                    <h2>Время покупки: " . dateformat($item["datetime_buy_baskets"]) . "</h2>
                    <div class='items'>
                ";
                $datetimeBuy = $item["datetime_buy_baskets"];
            }
            $historyHTML .= getItemHTML($item);
            $historyCost += $item["cost_items"] * $item["count_baskets"];
            if ($item["id_baskets"] == $lastUserItem["id_baskets"]) {
                $historyHTML .= "</div>
                    <p>Всего: {$historyCost}р</p>
                    <a href='deliveryItem.php?datetime=$item[datetime_buy_baskets]' class='button'>Информация о доставке</a>
                    </article>
                ";
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

function searchUsers($json)
{
    unset($json["server_type"]);
    $validatedData = getValidatedData($json, "adminEditUser.php");
    if (!$validatedData["isCorrect"] && !empty($json)) setAnswer("FAIL");

    $where = [];
    $params = [];

    foreach ($validatedData["data"] as $data) {
        $where[] = $data["sql"];
        array_push($params, ...$data["params"]);
    }

    $users = null;
    if (!empty($where) && !empty($params)) {
        $users = getUsers("WHERE `id_users` != ? AND " . join(" AND ", $where), [1, ...$params]);
    } else {
        $users = getUsers("WHERE `id_users` != ?", [1]);
    }
    
    if ($users == "FAIL") setAnswer("FAIL");
    else if (empty($users)) setAnswer("NOTFOUND");

    setAnswer("OK", $users);
}
function getUsers($where = "", $params = [])
{
    $users = makeSelectQuery("SELECT * FROM `users` $where ORDER BY `is_banned_users` DESC", $params, false);
    $usersHTML = "";

    if ($users == "FAIL") {
        return "FAIL";
    }

    foreach ($users as $user) {
        $isBanned = $user["is_banned_users"] === 1;
        $usersHTML .= "<div class='user'>
            <p>Имя:<br>$user[name_users]</p>
            <p>Почта:<br>$user[email_users]</p>
            <p class='user-status'>Статус:<br><span>" . ($isBanned ? "Заблокирован" : "Разблокирован") . "</span></p>
            <button class='button banned' data-id='$user[id_users]' data-is-banned='$user[is_banned_users]'>" . ($isBanned ? "Разблокировать" : "Заблокировать") . "</button>
            <button class='button delete' data-id='$user[id_users]'>Удалить</button> 
        </div>";
    }

    return $usersHTML;
}

function getValidationRules($file = "")
{
    $result = [];
    if ($file == "") {
        $file = basename($_SERVER["SCRIPT_NAME"]);
    }

    $rules = [
        // Пользователь
        "id_users" => [
            "files" => ["adminEditUser.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/[0-9]{1,7}$/u", $value);
            }
        ],
        "name_users" => [
            "files" => ["reg.php", "profile.php"],
            "required" => true,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[а-яёА-Я Ё-]{1,30}$/u", $value);
            }
        ],
        "email_users" => [
            "files" => ["reg.php", "auth.php"],
            "required" => true,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[A-Za-z0-9._%+-]{1,50}@[A-Za-z0-9.-]{1,15}\.[A-Za-z]{1,15}$/u", $value);
            },
        ],
        "email_search_users" => [
            "files" => ["adminEditUser.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if (preg_match("/^[A-Za-z0-9._%+-@]{1,80}$/u", $value)) {
                    return ["sql" => "`email_users` LIKE ?", "params" => ["%$value%"]];
                }
                return false;
            },
        ],
        "is_banned_search_users" => [
            "files" => ["adminEditUser.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if (preg_match("/[0-1]$/u", $value)) {
                    return ["sql" => "`is_banned_users` = ?", "params" => [$value]];
                } else if ($value == 2) {
                    return ["sql" => "`is_banned_users` IN (?, ?)", "params" => [0, 1]];
                }
                return false;
            }
        ],
        "password_users" => [
            "files" => ["reg.php", "auth.php", "profile.php"],
            "required" => true,
            "canUpdate" => true,
            "returned_value" => true,
            "pattern" => function ($value) use ($file) {
                if (preg_match("/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/u", $value)) {
                    return $file == "auth.php" ? $value : password_hash($value, PASSWORD_DEFAULT);
                }
                return false;
            }
        ],
        "re_password_users" => [
            "files" => ["reg.php", "profile.php"],
            "required" => true,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[a-zA-Z0-9!@#\$%^&*()_+\-\=\{\}\\:;\"\'<>,\.?\/]{1,40}$/u", $value);
            }
        ],
        "avatar_users" => [
            "files" => ["profile.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => true,
            "pattern" => function ($value) {
                $extension = pathinfo($value["name"], PATHINFO_EXTENSION);
                if (in_array($extension, ["jpg", "png", "webp"]) && $value["size"] < 3_000_000) {
                    $datetime = date("y-m-d-H-i-s") . ".$extension";
                    if (move_uploaded_file($value["tmp_name"], __DIR__ . "/" . FOLDER_IMG . "/" . FOLDER_UPLOAD . "/" . FOLDER_AVATARS . "/$datetime")) {
                        return $datetime;
                    }
                }
                return false;
            }
        ],
        "is_banned_users" => [
            "files" => ["adminEditUser.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/[0-1]$/u", $value);
            }
        ],
        // Товар
        "id_items" => [
            "files" => ["index.php", "adminEditItem.php", "aboutItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/[0-9]{1,7}$/u", $value);
            }
        ],
        "name_items" => [
            "files" => ["adminEditItem.php", "adminAddItem.php"],
            "required" => true,
            "canUpdate" => $file == "adminEditItem.php",
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[а-яА-Яa-zA-Z0-9 -().,:\"'%]{1,80}$/u", $value);
            }
        ],
        "count_items" => [
            "files" => ["index.php", "adminEditItem.php", "adminAddItem.php"],
            "required" => "index.php" != $file,
            "canUpdate" => $file == "adminEditItem.php",
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[0-9]{1,7}$/u", $value);
            }
        ],
        "cost_items" => [
            "files" => ["adminEditItem.php", "adminAddItem.php"],
            "required" => true,
            "canUpdate" => $file == "adminEditItem.php",
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[0-9]{1,7}$/u", $value);
            }
        ],
        "image_items" => [
            "files" => ["adminEditItem.php", "adminAddItem.php"],
            "required" => false,
            "canUpdate" => $file == "adminEditItem.php",
            "returned_value" => false,
            "pattern" => function ($value) {
                $extension = pathinfo($value["name"], PATHINFO_EXTENSION);
                if (in_array($extension, ["jpg", "png", "webp"]) && $value["size"] < 3_000_000) {
                    $datetime = date("Y-m-d-H-i-s") . ".$extension";
                    if (move_uploaded_file($value["tmp_name"], __DIR__ . "/" . FOLDER_IMG . "/" . FOLDER_UPLOAD . "/" . FOLDER_ITEMS . "/$datetime")) {
                        return $datetime;
                    }
                }
                return false;
            }
        ],
        "items_type_id_items" => [
            "files" => ["adminEditItem.php", "adminAddItem.php"],
            "required" => true,
            "canUpdate" => $file == "adminEditItem.php",
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/[0-9]{1,7}$/u", $value);
            }
        ],
        "description_items" => [
            "files" => ["adminEditItem.php", "adminAddItem.php"],
            "required" => false,
            "canUpdate" => $file == "adminEditItem.php",
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[а-яА-Яa-zA-Z0-9 -().,:\"'%]{1,255}$/u", $value);
            }
        ],
        // Поиск товара
        "name_search_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if (preg_match("/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/u", $value)) {
                    return ["sql" => "`name_items` LIKE ?", "param" => "%$value%"];
                }
                return false;
            }
        ],
        "description_search_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if (preg_match("/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,255}$/u", $value)) {
                    return ["sql" => "`description_items` LIKE ?", "param" => "%$value%"];
                }
                return false;
            }
        ],
        "items_type_id_search_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                $isCorrect = true;
                $properties = json_decode($value, true) ?? [];
                // foreach ($properties as $property) {
                //     $isCorrectId = preg_match("/^[0-9]{1,}$/u", $property["id_items_properties"] ?? "0");
                //     $isCorrectName = preg_match("/^[0-9]{1,}$/u", $property["properties_id_items_properties"] ?? "!");
                //     $isCorrectDescription = preg_match("/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/u", $property["description_items_properties"] ?? "!");
                //     $count = count($property);
                //     if (
                //         $count < 1 ||
                //         $count == 2 && !$isCorrectId && ($isCorrectName || $isCorrectDescription) ||
                //         $count == 3 && !$isCorrectId && !$isCorrectName && !$isCorrectDescription
                //     ) {
                //         $isCorrect = false;
                //         break;
                //     }
                // }
                if ($isCorrect) {
                    return $properties;
                }
                return false;
            }
        ],
        "min_cost_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if (preg_match("/^[0-9]{1,7}$/u", $value)) {
                    return ["sql" => "`cost_items` > ?", "param" => $value];
                }
                return false;
            }
        ],
        "max_cost_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                    if (preg_match("/^[0-9]{1,7}$/u", $value)) {
                    return ["sql" => "`cost_items` < ?", "param" => $value];
                }
                return false;
            }
        ],
        "min_count_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if (preg_match("/^[0-9]{1,7}$/u", $value)) {
                    return ["sql" => "`count_items` > ?", "param" => $value];
                }
                return false;
            }
        ],
        "max_count_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                    if (preg_match("/^[0-9]{1,7}$/u", $value)) {
                    return ["sql" => "`count_items` < ?", "param" => $value];
                }
                return false;
            }
        ],
        "offset_search_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[0-9]{1,7}$/u", $value);
            }
        ],
        "strict_search" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return $value == "on";
            }
        ],
        "popular_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if ($value == "on") {
                    return ["sql" => "`views_items` > ?", "param" => 0];
                }
                return false;
            }
        ],
        // Комментарии
        "id_comments" => [
            "files" => ["aboutItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[0-9]{1,7}$/u", $value);
            }
        ],
        "text_comments" => [
            "files" => ["aboutItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/u", $value);
            }
        ],
        "rating_comments" => [
            "files" => ["aboutItem.php"],
            "required" => true,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[1-5]$/u", $value);
            }
        ],
        // Свойство у товаров
        "id_items_properties" => [
            "files" => ["adminEditItem.php", "adminAddItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[0-9]{1,7}$/u", $value);
            }
        ],
        "items_properties" => [
            "files" => ["adminEditItem.php", "adminAddItem.php"],
            "required" => false,
            "canUpdate" => $file == "adminEditItem.php",
            "returned_value" => true,
            "pattern" => function ($value) {
                $isCorrect = true;
                $properties = json_decode($value, true) ?? [];
                // foreach ($properties as $property) {
                //     $isCorrectId = preg_match("/^[0-9]{1,}$/u", $property["id_items_properties"] ?? "0");
                //     $isCorrectName = preg_match("/^[0-9]{1,}$/u", $property["properties_id_items_properties"] ?? "!");
                //     $isCorrectDescription = preg_match("/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,40}$/u", $property["description_items_properties"] ?? "!");
                //     $count = count($property);
                //     if (
                //         $count < 1 ||
                //         $count == 2 && !$isCorrectId && ($isCorrectName || $isCorrectDescription) ||
                //         $count == 3 && !$isCorrectId && !$isCorrectName && !$isCorrectDescription
                //     ) {
                //         $isCorrect = false;
                //         break;
                //     }
                // }
                if ($isCorrect) {
                    return $properties;
                }
                return false;
            }
        ],
        // Свойства товаров
        "id_properties" => [
            "files" => ["adminEditTable.php", "adminEditItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/[0-9]{1,7}$/u", $value);
            }
        ],
        "name_properties" => [
            "files" => ["adminEditTable.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[А-Яа-яa-zA-Z0-9 ().,:\"'%-]{1,80}$/u", $value);
            }
        ],
        //
        "attributes" => [
            "files" => ["adminEditTable.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                $isCorrect = true;
                $attributes = json_decode($value, true) ?? [];
                foreach ($attributes as $attribute) {
                    if (false) {
                        $isCorrect = false;
                        break;
                    }
                }
                if ($isCorrect) {
                    return $attributes;
                }
                return false;
            }
        ],
        "id_attributes" => [
            "files" => ["adminEditTable.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/[0-9]{1,7}$/u", $value);
            }
        ],
        "attributes_search" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                $isCorrect = true;
                $attributes = json_decode($value, true) ?? [];
                foreach ($attributes as $id => $values) {
                    if (false) {
                        $isCorrect = false;
                        break;
                    }
                }
                if ($isCorrect) {
                    return $attributes;
                }
                return false;
            } 
        ],
        "attributes_select_property" => [
            "files" => ["adminEditItem.php"],
            "required" => false,
            "canUpdate" => $file == "adminEditItem.php",
            "returned_value" => true,
            "pattern" => function ($value) {
                $isCorrect = true;
                $attributes = json_decode($value, true) ?? [];
                foreach ($attributes as $id => $values) {
                    if (false) {
                        $isCorrect = false;
                        break;
                    }
                }
                if ($isCorrect) {
                    return $attributes;
                }
                return false;
            } 
        ],
        // Типы товаров
        "id_items_type" => [
            "files" => ["adminEditTable.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/[0-9]{1,7}$/", $value);
            }
        ],
        "name_items_type" => [
            "files" => ["adminEditTable.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/u", $value);
            }
        ],
        // Статус покупки
        "id_status" => [
            "files" => ["adminEditTable.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/[0-9]{1,7}$/", $value);
            }
        ],
        "name_status" => [
            "files" => ["adminEditTable.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) {
                return preg_match("/^[А-Яа-яa-zA-Z0-9 -().,:\"'%]{1,80}$/u", $value);
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
function getValidatedData($array, $file = ""): array
{
    $result = [
        "data" => [],
        "errorField" => [],
        "isCorrect" => false
    ];

    if ($array == null) return $result;

    $validationRules = getValidationRules($file);
    if (empty($validationRules)) return $result;

    $currentCountCorrect = 0;
    foreach ($array as $key => $value) {
        if (empty($validationRules[$key])) {
            $result["emptyRule"] = $key;
            continue;
        }

        $rule = $validationRules[$key];

        if ($value != "" && $rule["returned_value"]) {
            $returnedValue = $rule["pattern"]($value);
            if ($returnedValue !== false) {
                if ($key == "password_users" && !empty($array["re_password_users"]) && $value != $array["re_password_users"]) {
                    $result["errorField"][$key] = 1;
                } else {
                    $result["errorField"][$key] = 0;
                }
                $result["data"][$key] = $returnedValue;
                $currentCountCorrect++;
            } else {
                $result["errorField"][$key] = 1;
                if ($key == "items_properties" || $key == "attributes") {
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

        if (!$rule["pattern"]($value)) {
            $result["errorField"][$key] = 1;
            continue;
        } else {
            $result["errorField"][$key] = 0;
        }

        $currentCountCorrect++;
    }

    $hasAllRule = true;
    foreach ($validationRules as $key => $rule) {
        if (!key_exists($key, $array) && $rule["required"] && !$rule["canUpdate"]) {
            $result["errorField"][$key] = 1;
            $hasAllRule = false;
        }
    }

    $result["isCorrect"] = $currentCountCorrect == count($array) && $hasAllRule;
    return $result;
}

function getUpdateSQL($array)
{
    $result = [
        "params" => [],
        "sql" => []
    ];

    foreach ($array as $key => $value) {
        if (empty($value)) {
            $result["params"][] = null;
        } else {
            $result["params"][] = $value;
        }

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
        $deleteButton = "";
        if ($comment["users_id_comments"] == getUserID() || isAdmin()) {
            $deleteButton .= "<button class='button' data-id='$comment[id_comments]'>Удалить</button>";
        }

        $img = getValidImage(FOLDER_UPLOAD . "/" . FOLDER_AVATARS, $comment["avatar_users"]);
        $rating = str_repeat($star, $comment["rating_comments"]);
        $commentsHTML .= "<div class='comment'>
            <img class='avatar' src='$img'>
            <p class='comment-username'>$comment[name_users]</p>
            <p class='comment-date'>" . dateformat($comment["date_add_comments"]) . "</p>
            <p class='comment-text'>$comment[text_comments]</p>
            <span class='comment-rating'>$rating</span>
            $deleteButton
        </div>";
    }

    return $commentsHTML;
}

function getRatingItem($idItem)
{
    $rating = makeSelectQuery("SELECT
        ROUND(AVG(`rating_comments`), 1) AS `rating`
        FROM `comments`
        WHERE `items_id_comments` = ?
    ", [$idItem], true);
    return $rating == "FAIL" || $rating["rating"] == null ? 0.0 : $rating["rating"];
}

function setAnswer($status, $data = [])
{
    echo json_encode(["status" => $status, "data" => $data]);
    exit;
}

function changeBasket($idItem, $countItem, $actionItem)
{
    $validatedData = getValidatedData(["id_items" => $idItem, "count_items" => $countItem], "index.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $item = makeSelectQuery("SELECT `count_items` FROM `items` WHERE `id_items` = ?", [$idItem], true);

    if ($item == "FAIL" || empty($item)) setAnswer("FAIL");

    if ($actionItem == "add" && $item["count_items"] >= $countItem) {
        $check = makeSelectQuery("SELECT `id_baskets` FROM `baskets` WHERE `items_id_baskets` = ? AND `status_id_baskets` = ? AND `users_id_baskets` = ?", [$idItem, 1, getUserID()], true);

        if ($check == "FAIL") setAnswer("FAIL");

        $isSuccess = false;
        if (!empty($check["id_baskets"])) {
            $isSuccess = makeSafeQuery("UPDATE `baskets` SET `count_baskets` = ? WHERE `id_baskets` = ?", [$countItem, $check["id_baskets"]]);
        } else {
            $isSuccess = makeSafeQuery("INSERT INTO `baskets` (`items_id_baskets`, `count_baskets`, `status_id_baskets`, `users_id_baskets`) VALUES (?, ?, ?, ?)", [$idItem, $countItem, 1, getUserID()]);
        }
        setAnswer($isSuccess ? "OK" : "FAIL");
    } else if ($actionItem == "remove") {
        $isSuccess = makeSafeQuery("DELETE FROM `baskets` WHERE `items_id_baskets` = ? AND `status_id_baskets` = ? AND `users_id_baskets` = ?", [$idItem, 1, getUserID()]);
        setAnswer($isSuccess ? "OK" : "FAIL");
    } else {
        setAnswer("FAIL");
    }
}

function buyItems()
{
    $datetime = date("y-m-d H:i:s");
    $isSuccess = makeSafeQuery("UPDATE `baskets` SET `status_id_baskets` = ?, `datetime_buy_baskets` = ? WHERE `users_id_baskets` = ? AND `status_id_baskets` = ? AND `datetime_buy_baskets` IS NULL ", [2, $datetime, 1, getUserID()]);

    if (!$isSuccess) setAnswer("FAIL");

    $basketInfo = makeSelectQuery("SELECT
        `baskets`.`id_baskets`,
        `baskets`.`count_baskets`,
        `baskets`.`users_id_baskets`,
        `baskets`.`datetime_buy_baskets`,
        `items`.`id_items`,
        `items`.`name_items`,
        `items`.`image_items`,
        `items`.`cost_items`
        FROM `baskets`
        JOIN `items` ON `items`.`id_items` = `items_id_baskets`
        WHERE `users_id_baskets` = ? AND `status_id_baskets` = ? AND `datetime_buy_baskets` = ?
    ", [getUserID(), 2, $datetime], false);

    if ($basketInfo == "FAIL" || empty($basketInfo)) setAnswer("FAIL");

    setAnswer("OK", getBasketHTML($basketInfo));
}

function deleteItem($idItem)
{
    $validatedData = getValidatedData(["id_items" => $idItem,  "name_items" => 1, "count_items" => 1, "cost_items" => 1, "items_type_id_items" => 1], "adminEditItem.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("DELETE FROM `items` WHERE `id_items` = ?", [$idItem]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function searchItems($json)
{
    unset($json["server_type"]);
    $data = getValidatedData($json, "index.php");
    if (!$data["isCorrect"]) setAnswer("FAIL");

    $data = $data["data"];
    $isPopularItems = !empty($data["popular_items"]);
    $offset = $data["offset_search_items"];
    $attributes = $data["attributes_search"] ?? [];
    $types = $data["items_type_id_search_items"] ?? [];
    $strictType = empty($data["strict_search"]) ? "OR" : "AND";
   
    $whereSQL = "";
    $whereParams = [];

    if (!empty($data) && count($data) > 1) {
        unset($data["items_type_id_search_items"], $data["attributes_search"], $data["offset_search_items"], $data["strict_search"]);
        
        $whereSQL = "WHERE ";
        $isSpecificSearch = count($data) > 1 && $isPopularItems;
        $isOtherParams = !empty($data);
        if ($isSpecificSearch) {
            $whereSQL .= $data["popular_items"]["sql"] . $isOtherParams ? " AND (" : "";
            $whereParams[] = $data["popular_items"]["param"];
            unset($data["popular_items"]);
        }
        $lastKey = array_key_last($data);
        foreach ($data as $key => $value) {
            $whereSQL .= $value["sql"];
            $whereParams[] = $value["param"];
            if ($key != $lastKey) {
                $whereSQL .= " $strictType ";
            }
        }

        if (!empty($attributes) && !empty($whereParams)) {
            $whereSQL .= " $strictType ";
        }
        $lastIndex = count($attributes) - 1;
        foreach ($attributes as $index => $attribute) {
            $whereSQL .= "`id_attributes` = ?";
            $whereParams[] = $attribute;
            if ($index != $lastIndex) {
                $whereSQL .= " $strictType ";
            }
        }

        if (!empty($types) && !empty($whereParams)) {
            $whereSQL .= " $strictType ";
        }
        $lastIndex = count($types) - 1;
        foreach ($types as $index => $type) {
            $whereSQL .= "`items_type_id_items` = ?";
            $whereParams[] = $type;
            if ($index != $lastIndex) {
                $whereSQL .= " $strictType ";
            }
        }

        if ($isSpecificSearch && $isOtherParams) {
            $whereSQL .= ")";
        }
    }

    $items = getItems($offset, $whereSQL, $whereParams, $isPopularItems);
    setAnswer(empty($items) ? "NOTFOUND" : "OK", $items);
}

function addComment($idItem, $rating, $text)
{
    $validatedData = getValidatedData(["id_items" => $idItem, "rating_comments" => $rating, "text_comments" => $text], "aboutItem.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $validatedData = $validatedData["data"];
    $dateTime = date("y-m-d H:i:s");
    $isSuccess = makeSafeQuery(
        "INSERT INTO `comments`
        (`users_id_comments`, `text_comments`, `rating_comments`, `date_add_comments`, `items_id_comments`)
        VALUES (?, ?, ?, ?, ?)
        ",
        [getUserID(), $validatedData["text_comments"], $validatedData["rating_comments"], $dateTime, $validatedData["id_items"]]
    );

    if (!$isSuccess) setAnswer("FAIL");

    $comment = makeSelectQuery(
        "SELECT
        `comments`.`users_id_comments`,
        `comments`.`id_comments`,
        `comments`.`text_comments`,
        `comments`.`rating_comments`,
        `comments`.`date_add_comments`,
        `users`.`name_users`,
        `users`.`avatar_users`
        FROM `comments`
        JOIN `users` ON `comments`.`users_id_comments` = `users`.`id_users`
        WHERE `comments`.`date_add_comments` = ? AND `users`.`id_users` = ?
        ",
        [$dateTime, getUserID()],
        false
    );

    if ($comment == "FAIL") setAnswer("FAIL");

    setAnswer("OK", ["comments" => getCommentsHTML($comment), "rating" => getRatingItem($validatedData["id_items"])]);
}

function deleteItemProperties($idProperties)
{
    $validatedData = getValidatedData(["id_properties" => $idProperties], "adminEditItem.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("DELETE FROM `items_properties`
        WHERE EXISTS (
            SELECT 1 FROM `attributes`
            WHERE `attributes`.`id_attributes` = `items_properties`.`attributes_id_items_properties`
            AND `properties_id_attributes` = ?
        )
    ", [$idProperties]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function deleteFromTable($table, $id)
{
    $validatedData = getValidatedData(["id_$table" => $id], "adminEditTable.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    if (!in_array($table, ["properties", "status", "items_type"])) setAnswer("FAIL");
    $isSuccess = makeSafeQuery("DELETE FROM `$table` WHERE `id_$table` = ?", [$id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function deleteComment($id)
{
    $validatedData = getValidatedData(["id_comments" => $id, "id_items" => 1, "rating_comments" => 1], "aboutItem.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $check = makeSelectQuery("SELECT `users_id_comments` FROM `comments` WHERE `id_comments` = ?", [$id], true);

    if ($check == "FAIL" || $check["users_id_comments"] != getUserID() && !isAdmin()) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("DELETE FROM `comments` WHERE `id_comments` = ?", [$id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function addView($idItem)
{
    $validatedData = getValidatedData(["id_items" => $idItem, "rating_comments" => 1], "aboutItem.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `items` SET `views_items` = `views_items` + 1 WHERE `id_items` = ?", [$idItem]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function deleteOneFromTable($id)
{
    $validatedData = getValidatedData(["id_attributes" => $id], "adminEditTable.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("DELETE FROM `attributes` WHERE `id_attributes` = ?", [$id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function bannedUser($id, $idBanned)
{
    if ($id == 1) setAnswer("FAIL");

    $validatedData = getValidatedData(["id_users" => $id, "is_banned_users" => $idBanned], "adminEditUser.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `users` SET `is_banned_users` = ? WHERE `id_users` = ?", [$idBanned, $id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function deleteUser($id)
{
    if ($id == 1) setAnswer("FAIL");

    $validatedData = getValidatedData(["id_users" => $id], "adminEditUser.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("DELETE FROM `users` WHERE `id_users` = ?", [$id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}