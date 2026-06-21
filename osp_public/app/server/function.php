<?php
include_once __DIR__ . "/../config/config.php";

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

function getAdditionalSelectHTML($allPropertiesHTML, $allAttributesHTML, $value = 0, $idItemsProperty = 0, $dataValue = [])
{
    $startIndexValue = strpos($allPropertiesHTML, "value='$value'");
    $selectSelected = substr($allPropertiesHTML, 0, $startIndexValue) . " selected " . substr($allPropertiesHTML, $startIndexValue);

    if (count($dataValue) > 0) {
        $dataValue = "data-value='" . join("|", $dataValue) . "'";
    } else {
        $dataValue = "";
    }

    if ($idItemsProperty == 0) {
        $idItemsProperty = "";
    } else {
        $idItemsProperty = "data-id-items-properties='$idItemsProperty'";
    }

    return "<div class='field property'>
                <div class='field'>
                    <label class='label'></label>
                    <select class='input' data-name='attributes_select_property' $dataValue>
                        <option selected disabled>Выбрать</option>
                        $selectSelected
                    </select>
                    <p class='error'></p>
                </div>
                <div class='field'>
                    $allAttributesHTML
                </div>
                <div class='field'>
                    <button class='delete-property button' $idItemsProperty>Удалить</button>
                </div>
            </div>
        ";
}


function getAdditionalTemplateHTML($allPropertiesHTML, $allAttributesHTML, $attributes, $idItem = -1)
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
    
    $dependencies = [];
        
    foreach ($attributes as $attribute) {
        $dependencies[$attribute["id_properties"]][] = $attribute["id_attributes"];
    }
    $dependencies = json_encode($dependencies);

    echo "<template data-max-count='$max[count]' data-current-count='$current[count]'>" .
        getAdditionalSelectHTML($allPropertiesHTML, $allAttributesHTML) ."
        <p>$dependencies</p>
    </template>";
}

function redirect($path = "index.php", $params = "")
{
    if (!file_exists(__DIR__ . "/../../public/$path")) {
        header("Location: /");
    } else {
        $fullPath = $params == "" ? "/$path" : "/$path?$params";
        header("Location: $fullPath");
    }
    exit;
}

function redirectYourself($params = "")
{
    redirect(basename(dirname($_SERVER["SCRIPT_FILENAME"])) . "/" . basename($_SERVER["SCRIPT_FILENAME"]), $params);
}

function getValidImage($path = "")
{
    return "/api/loadImg.php?path=$path";
}

function isUserAuth()
{
    return !empty(getUserID());
}

function isAdmin()
{
    return (getUserInfo()["roles_id_users"] ?? 0) == 1;
}

function isDeliver()
{
    $isSuccess = makeSelectQuery("SELECT `roles_id_users` FROM `users` WHERE `id_users` = ?", [getUserID()], true);
    return $isSuccess == "FAIL" || !isset($isSuccess["roles_id_users"]) ? false : $isSuccess["roles_id_users"] == 3 || $isSuccess["roles_id_users"] == 1;
}

function isSupport()
{
    $isSuccess = makeSelectQuery("SELECT `roles_id_users` FROM `users` WHERE `id_users` = ?", [getUserID()], true);
    return $isSuccess == "FAIL" || !isset($isSuccess["roles_id_users"]) ? false : $isSuccess["roles_id_users"] == 4 || $isSuccess["roles_id_users"] == 1;
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

    $result = [];
    if ($getOne) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result === false ? [] : $result;
    } else {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result === false ? [] : $result;
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
    $favoritesItems = [];

    $orderBy = $isPopularItems ? "`views_items` DESC," : "";
    makeSelectQuery("SET SESSION SQL_MODE = ''");
    $items = makeSelectQuery(
        "SELECT
        `id_items`,
        `name_items`,
        `count_items`,
        `discount_items`,
        `image_items_images`,
        `cost_items`,
        ROUND(AVG(`rating_comments`), 1) AS `rating`,
        COUNT(*) AS `comment_count`
        FROM `items`
        LEFT JOIN `items_properties` ON `items_id_items_properties` = `id_items`
        LEFT JOIN `items_images` ON `id_items` = `items_id_items_images`
        LEFT JOIN `attributes` ON `id_attributes` = `attributes_id_items_properties`
        LEFT JOIN `comments` ON `id_items` = `items_id_comments` 
        $whereSQL
        GROUP BY `id_items`
        ORDER BY $orderBy `id_items` DESC
        LIMIT $limit OFFSET $offset
        ", $whereParams, false
    );
    if ($items == "FAIL") return $result;

    if (isUserAuth()) {
        $userItems = makeSelectQuery("SELECT
            `items_id_baskets`, `count_baskets`
            FROM `baskets`
            JOIN `orders` ON `orders`.`id_orders` = `baskets`.`orders_id_baskets`
            WHERE `users_id_orders` = ? AND `status_id_orders` = ?
            ", [getUserID(), 1], false
        );
        if ($userItems == "FAIL") return $result;

        $favoritesItems = makeSelectQuery("SELECT `items_id_favorites` FROM `favorites` WHERE `users_id_favorites` = ?", [getUserID()], false);
        if ($userItems == "FAIL") return $result;
    }

    foreach ($items as $item) {
        $basket = getBuyBasketHTML($userItems, $item, $favoritesItems);
        $cost = "";
        $rating = "";
        if ($item["discount_items"] > 0) {
            $cost .= "
                <p class='current'>" . calculateDiscount($item, false) ."р</p>
                <p class='original'>$item[cost_items]р</p>
                <p class='discount'>$item[discount_items]%</p>
            ";
        } else {
            $cost .= "<p class='current'>$item[cost_items]р</p>";
        }
        if ($item["rating"] > 0) {
            $rating = "
                <p class='item-rating'>
                    <svg width='30' height='30' fill='#FFD700' class='inactive'>
                        <use xlink:href='/assets/img/star.svg#star'></use>
                    </svg>
                    $item[rating]
                </p>
                <p class='item-comment'>
                    <img src='/assets/img/comment.png'>
                    $item[comment_count]
                </p>
            ";
        } else {
            $rating = "<p class='item-notfound'>Нет подробной информации</p>";
        }
        $editButton = "";
        if (isAdmin()) {
            $editButton = "<a href='/admin/editItem.php?id_item=$item[id_items]' class='button edit'>Изменить товар</a>";
        }
        $result .= "<span class='item' data-id='$item[id_items]' data-count='$item[count_items]'>
                <a href ='/user/aboutItem.php?id_item=$item[id_items]' class='item-link'>
                    <img src='" . getValidImage("items/$item[image_items_images]") . "' class='item-image'>
                    <span class='item-cost'>
                        $cost
                    </span>
                    <p class='item-name'>$item[name_items]</p>
                    $rating
                </a>
                $basket
                $editButton
            </span>
        ";
    }

    return $result;
}

function getItemHTML($item)
{
    $result = "";
    if ($item == null) return $result;
    $img = getValidImage("items/$item[image_items_images]");
    $result .= "<a href='/user/aboutItem.php?id_item=$item[id_items]' class='item' data-id='$item[id_items]' data-count='$item[count_baskets]'>
        <img src='$img' class='item-image'>
        <p class='item-name'>$item[name_items]</p>
        <p class='item-count'>Количество: $item[count_baskets]</p>
        <p class='item-cost'>Стоимость: " . calculateDiscount($item, true) * $item["count_baskets"] . "р</p>
    </a>";
    return $result;
}

function calculateDiscount($item, $isBasket = true)
{
    $cost = $isBasket ? $item["cost_baskets"] ?? $item["cost_items"] : $item["cost_items"];
    $discount = ($isBasket ? $item["discount_baskets"] ?? $item["discount_items"] : $item["discount_items"]) ?? 0;
    return $cost * (1 - $discount / 100);
}

function getBuyBasketHTML($userItems = [], $item = [], $favoritesItems = [])
{
    if (!isUserAuth()) return;

    $textBasket = "Добавить в корзину";
    $countBasket = "0";
    $classBasket = "invisible";
    $type = "add";
    $textFavorites = "Добавить в избранное";
    $classFavorites = "";

    if (count($userItems) > 0) {
        foreach ($userItems as $userItem) {
            if ($userItem["items_id_baskets"] == $item["id_items"]) {
                $textBasket = "Убрать из корзины";
                $countBasket = "$userItem[count_baskets]";
                $classBasket = "";
                $type = "remove";
                break;
            }
        }

    }
    if (count($favoritesItems) > 0) {
        foreach ($favoritesItems as $favoriteItem) {
            if ($favoriteItem["items_id_favorites"] == $item["id_items"]) {
                $textFavorites = "Убрать из избранного";
                $classFavorites = "favorite";
                break;
            }
        }
    }

    return "
        <div class='basket'>
            <span class='$classBasket basket-container'>
                <button class='basket-minus button'>-</button>
                <p class='basket-count'>В корзине: <b class='basket-text'>$countBasket</b></p>
                <button class='basket-plus button'>+</button>
            </span>
            <button class='basket-action button' data-type='$type'>$textBasket</button>
            <button class='item-favorites button $classFavorites'>$textFavorites</button>
        </div>
    ";
}

function getBasketHTML($basket)
{
    $historyHTML = "";
    $currentHTML = "";
    $currentCost = 0;
    $historyCost = 0;

    $idOrder = null;
    $nameStatus = null;
    $lastUserItem = end($basket);
    foreach ($basket as $item) {
        if (empty($item["datetime_buy_orders"])) {
            $currentHTML .= getItemHTML($item);//
            $currentCost += calculateDiscount($item, false) * $item["count_baskets"];
        } else {
            if ($idOrder != $item["id_orders"]) {
                if ($idOrder != null) {
                    $historyHTML .= "</div>
                        <p class='text'>Всего: {$historyCost}р</p>
                        <p class='text'>Статус: $nameStatus</p>
                        <a href='/user/deliveryItem.php?id_order=$idOrder' class='button'>Информация о доставке</a>
                        </article>
                    ";
                    $historyCost = 0;
                }
                $historyHTML .= "<article class='order'>
                    <h2 class='subtitle'>Время покупки: " . dateformat($item["datetime_buy_orders"]) . "</h2>
                    <div class='items'>
                ";
                $idOrder = $item["id_orders"];
                $nameStatus = $item["name_status"];
            }
            $historyHTML .= getItemHTML($item);
            $historyCost += calculateDiscount($item, true) * $item["count_baskets"];
            if ($item["id_baskets"] == $lastUserItem["id_baskets"]) {
                $historyHTML .= "</div>
                    <p class='text'>Всего: {$historyCost}р</p>
                    <p class='text'>Статус: $item[name_status]</p>
                    <a href='/user/deliveryItem.php?id_order=$item[id_orders]' class='button'>Информация о доставке</a>
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
    $validatedData = getValidatedData($json, "editUser.php");
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
    $users = makeSelectQuery("SELECT
        `is_banned_users`,
        `roles_id_users`,
        `name_users`,
        `email_users`,
        `id_users`,
        `name_roles`
        FROM `users`
        JOIN `roles` ON `id_roles` = `roles_id_users`
        $where
        ORDER BY `id_users` DESC
    ", $params, false);
    $usersHTML = "";

    if ($users == "FAIL") {
        return "FAIL";
    }

    foreach ($users as $user) {
        $isBanned = $user["is_banned_users"] === 1 ? "Разблокировать" : "Заблокировать";
        $isDeliver = $user["roles_id_users"] === 3 ? "Убрать из доставщика" : "Сделать доставщиком";
        $isSupport =  $user["roles_id_users"] === 4 ? "Убрать из поддержки" : "Сделать поддержкой";
        $usersHTML .= "<div class='user'>
            <p>Имя:<br>$user[name_users]</p>
            <p>Почта:<br>$user[email_users]</p>
            <p class='user-status'>Статус:<br><span>" . ($isBanned == "Разблокировать" ? "Заблокирован" : "Разблокирован") . "</span></p>
            <p class='user-role'>Роль:<br><span>$user[name_roles]</span></p>
            <button class='button deliver' data-id='$user[id_users]' data-id-status='$user[roles_id_users]'>$isDeliver</button>
            <button class='button support' data-id='$user[id_users]' data-id-status='$user[roles_id_users]'>$isSupport</button> 
            <button class='button banned' data-id='$user[id_users]' data-is-banned='$user[is_banned_users]'>$isBanned</button>
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

    $symbols = [
        "id" => "^[0-9]{1,10}$",
        "num" => "0-9",
        "space" => " ",
        "ru" => "А-Яа-яёЁ",
        "eng" => "A-Za-z",
        "special" => "!@#\$%^&*()\-+=_\{\}[]|:;\"'<>?\/\\.,",
        "simple" => "().,:\"'!?-"
    ];

    $rules = [
        // Пользователь
        "id_users" => [
            "files" => ["editUser.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "email_users" => [
            "files" => ["reg.php", "auth.php"],
            "required" => true,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[eng]$symbols[num]._%+-]{1,50}@[$symbols[eng]$symbols[num].-]{1,15}\.[$symbols[eng]]{2,15}$/u", $value);
            },
        ],
        "password_users" => [
            "files" => ["reg.php", "auth.php", "profile.php"],
            "required" => true,
            "canUpdate" => $file == "profile.php",
            "returned_value" => true,
            "pattern" => function ($value) use ($file, $symbols) {
                if (preg_match("/[$symbols[eng]$symbols[num]$symbols[special]]{8,64}$/u", $value)) {
                    return $file == "auth.php" ? $value : password_hash($value, PASSWORD_DEFAULT);
                }
                return false;
            }
        ],
        "re_password_users" => [
            "files" => ["reg.php", "profile.php"],
            "required" => true,
            "canUpdate" => $file == "profile.php",
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/[$symbols[eng]$symbols[num]$symbols[special]]{8,64}$/u", $value);
            }
        ],
        "name_users" => [
            "files" => ["reg.php", "profile.php"],
            "required" => true,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[space]-]{1,100}$/u", $value);
            }
        ],
        "avatar_users" => [
            "files" => ["profile.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => true,
            "pattern" => function ($value) {
                $extension = pathinfo($value["name"], PATHINFO_EXTENSION);
                if (in_array($extension, ["jpg", "png", "webp", "jpeg"]) && $value["size"] < 2_000_000) {
                    return ["tmp_name" => $value["tmp_name"], "current_name" => date("YmdHis") . ".$extension"];
                }
                return false;
            }
        ],
        "tel_users" => [
            "files" => ["profile.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^$|^\+[$symbols[num]]{11}$/u", $value);
            }
        ],
        "privacy_users" => [
            "files" => ["reg.php"],
            "required" => true,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) {
                return $value == "on";
            }
        ],
        // Поиск пользователя
        "email_search_users" => [
            "files" => ["editUser.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                if (preg_match("/^[$symbols[eng]$symbols[num]$symbols[eng]$symbols[num]$symbols[eng]._%+@-]{1,80}$/u", $value)) {
                    return ["sql" => "`email_users` LIKE ?", "params" => ["%$value%"]];
                }
                return false;
            },
        ],
        "is_banned_search_users" => [
            "files" => ["editUser.php"],
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
        // Товар
        "id_items" => [
            "files" => ["index.php", "editItem.php", "aboutItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "name_items" => [
            "files" => ["editItem.php", "addItem.php"],
            "required" => true,
            "canUpdate" => $file == "editItem.php",
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,100}$/u", $value);
            }
        ],
        "description_items" => [
            "files" => ["editItem.php", "addItem.php"],
            "required" => false,
            "canUpdate" => $file == "editItem.php",
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,3000}$/u", $value);
            }
        ],
        "count_items" => [
            "files" => ["index.php", "editItem.php", "addItem.php"],
            "required" => "index.php" != $file,
            "canUpdate" => $file == "editItem.php",
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "cost_items" => [
            "files" => ["editItem.php", "addItem.php"],
            "required" => true,
            "canUpdate" => $file == "editItem.php",
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "discount_items" => [
            "files" => ["editItem.php", "addItem.php"],
            "required" => false,
            "canUpdate" => $file == "editItem.php",
            "returned_value" => false,
            "pattern" => function ($value) {
                return $value <= 100 && $value > 0;
            }
        ],
        "items_type_id_items" => [
            "files" => ["editItem.php", "addItem.php"],
            "required" => true,
            "canUpdate" => $file == "editItem.php",
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "image_items_images" => [
            "files" => ["editItem.php", "addItem.php"],
            "required" => false,
            "canUpdate" => $file == "editItem.php",
            "returned_value" => true,
            "pattern" => function ($value) use ($result) {
                $imagesFileName = [];
                if (empty($value)) return false;
                for($i = 0; $i < count($value["name"]); $i++) {
                    $extension = pathinfo($value["name"][$i], PATHINFO_EXTENSION);
                    if (in_array($extension, ["jpg", "png", "webp", "jpeg"]) && $value["size"][$i] < 2_000_000) {
                        $imagesFileName[] = ["tmp_name" => $value["tmp_name"][$i], "current_name" => date("YmdHis") . "$i.$extension"];
                    } else {
                        return false;
                    }
                }
                return $imagesFileName;
            }
        ],
        "image_items_update" => [
            "files" => ["editItem.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                $imgs = json_decode($value, true);
                foreach ($imgs as $img) {
                    if (!preg_match("/$symbols[id]/", $img["id"]) || !preg_match("/^[$symbols[num]]{13,14}\.|png|jpg|webp|jpeg$/", $img["path"])) {
                        return false;
                    }
                }
                return $imgs;
            }
        ],
        // Поиск товара
        "name_search_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                if (preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,100}$/u", $value)) {
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
            "pattern" => function ($value) use ($symbols) {
                if (preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,3000}$/u", $value)) {
                    return ["sql" => "`description_items` LIKE ?", "param" => "%$value%"];
                }
                return false;
            }
        ],
        "min_cost_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                if (preg_match("/$symbols[id]/u", $value)) {
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
            "pattern" => function ($value) use ($symbols) {
                    if (preg_match("/$symbols[id]/u", $value)) {
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
            "pattern" => function ($value) use ($symbols) {
                if (preg_match("/$symbols[id]/u", $value)) {
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
            "pattern" => function ($value) use ($symbols) {
                if (preg_match("/$symbols[id]/u", $value)) {
                    return ["sql" => "`count_items` < ?", "param" => $value];
                }
                return false;
            }
        ],
        "discount_search_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if ($value == "on") {
                    return ["sql" => "`discount_items` > ?", "param" => 0];
                }
                return false;
            }
        ],
        "items_type_id_search_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                $isCorrect = true;
                $itemsType = json_decode($value, true);
                foreach ($itemsType as $type) {
                    if (!preg_match("/$symbols[id]/u",  $type)) {
                        $isCorrect = false;
                        break;
                    }
                }
                if ($isCorrect) {
                    return $itemsType;
                }
                return false;
            }
        ],
        "id_search_attributes" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                $isCorrect = true;
                $attributesIds = json_decode($value, true);
                foreach ($attributesIds as $id) {
                    if (!preg_match("/$symbols[id]/u", $id)) {
                        $isCorrect = false;
                        break;
                    }
                }
                if ($isCorrect) {
                    return $attributesIds;
                }
                return false;
            } 
        ],
        "offset_search_items" => [
            "files" => ["index.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
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
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "text_comments" => [
            "files" => ["aboutItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,1500}$/u", $value);
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
            "files" => ["editItem.php", "addItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "items_properties" => [
            "files" => ["editItem.php", "addItem.php"],
            "required" => false,
            "canUpdate" => $file == "editItem.php",
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                $isCorrect = true;
                $properties = json_decode($value, true);
                foreach ($properties as $property) {
                    $isType = in_array($property["type"], ["add", "remove"]);
                    $isIdAttributes = preg_match("/^$symbols[id]$/u", $property["id_attributes"]);
                    $isIdProperties = preg_match("/^$symbols[id]$/u", $property["id_properties"]);
                    if (!$isType || !$isIdAttributes || !$isIdProperties) {
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
        // Свойства товаров
        "id_properties" => [
            "files" => ["editTable.php", "editItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "name_properties" => [
            "files" => ["editTable.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,50}$/u", $value);
            }
        ],
        // Атрибуты
        "attributes" => [
            "files" => ["editTable.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                $isCorrect = true;
                $attributes = json_decode($value, true);
                foreach ($attributes as $attribute) {
                    $idProperty = preg_match("/^$symbols[id]$/u", $attribute["properties_id_attributes"] ?? 1);
                    $valueAttribute = preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,50}$/u", $attribute["value_attributes"]);
                    if (!$idProperty || !$valueAttribute) {
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
            "files" => ["editTable.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        // Типы товаров
        "id_items_type" => [
            "files" => ["editTable.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "name_items_type" => [
            "files" => ["editTable.php"],
            "required" => false,
            "canUpdate" => true,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,50}$/u", $value);
            }
        ],
        // Заказы
        "id_orders" => [
            "files" => ["allOrders.php", "myOrders.php", "deliveryItem.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "address_orders" => [
            "files" => ["basket.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                if (!empty($value["street"]) && !empty($value["home"])) {
                    $isStreet =  preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,180}$/u", $value["street"]);
                    $isHome =  preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,50}$/u", $value["home"]);
                    $isNumber =  preg_match("/$symbols[id]/u", empty($value["number"]) ? 1 : $value["number"]);
                    if ($isStreet && $isHome && $isNumber) {
                        return "Ул. $value[street], д. $value[home]" . (!empty($value["number"]) ? ", кв. $value[number]" : "");
                    }
                }
                return false;
            }
        ],
        "note_orders" => [
            "files" => ["basket.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,255}$/u", $value);
            }
        ],
        "datetime_plan_orders" => [
            "files" => ["basket.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) use ($symbols) {
                if (preg_match("/^[$symbols[num]]{2}:[$symbols[num]]{2} - [$symbols[num]]{2}:[$symbols[num]]{2}$/u", $value)) {
                    $split = explode(" - ", $value);
                    return ["start" => date("y-m-d") . " " . $split[0] . ":00", "end" => date("y-m-d") . " " . $split[1] . ":00"];
                }
                return false;
            }
        ],
        // Техподдержка
        "id_talks" => [
            "files" => ["support.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "id_supports" => [
            "files" => ["support.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "talks_id_supports" => [
            "files" => ["support.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/$symbols[id]/u", $value);
            }
        ],
        "title_talks" => [
            "files" => ["support.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,50}$/u", $value);
            }
        ],
        "text_supports" => [
            "files" => ["support.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => false,
            "pattern" => function ($value) use ($symbols) {
                return preg_match("/^[$symbols[ru]$symbols[eng]$symbols[num]$symbols[space]$symbols[simple]]{1,1000}$/u", $value);
            }
        ],
        "image_supports" => [
            "files" => ["support.php"],
            "required" => false,
            "canUpdate" => false,
            "returned_value" => true,
            "pattern" => function ($value) {
                if (preg_match("/^data:([^;]+);base64,(.*)$/", $value, $matches)) {
                    $pureBase64 = $matches[2];
                    $decodedFile = base64_decode($pureBase64);
                    if ($decodedFile !== false) {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $realMimeType = $finfo->buffer($decodedFile);
                        if (in_array($realMimeType, ["image/jpg", "image/png", "image/webp", "image/jpeg"]) && strlen($decodedFile) < 2_000_000) {
                            return ["current_name" => date("YmdHis") . "." . explode("/", $realMimeType)[1], "content" => $decodedFile];
                        }
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
            $result["params"][] = null;
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
        <use xlink:href='/assets/img/star.svg#star'></use>
    </svg>";

    foreach ($comments as $comment) {
        $deleteButton = "";
        if ($comment["users_id_comments"] == getUserID() || isAdmin()) {
            $deleteButton .= "<button class='button' data-id='$comment[id_comments]'>Удалить</button>";
        }

        $img = getValidImage("avatars/$comment[avatar_users]");
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
    return $rating == "FAIL" || $rating["rating"] == null ? "Нет" : $rating["rating"];
}

function setAnswer($status, $data = [])
{
    echo json_encode(["status" => $status, "data" => $data]);
    exit;
}

function changeBasket($idItem, $countItem, $actionItem)
{
    $validatedData = getValidatedData(["id_items" => $idItem, "count_items" => $countItem], "index.php");
    if (!$validatedData["isCorrect"] || !in_array($actionItem, ["add", "remove"])) setAnswer("FAIL");

    $item = makeSelectQuery("SELECT `count_items` FROM `items` WHERE `id_items` = ?", [$idItem], true);
    if ($item == "FAIL" || empty($item)) setAnswer("FAIL");

    $orders = makeSelectQuery("SELECT `id_orders` FROM `orders` WHERE `users_id_orders` = ? AND `status_id_orders` = ?", [getUserID(), 1], true);
    if ($orders == "FAIL") {
        setAnswer("FAIL");
    } else if (empty($orders)) {
        $isSuccess = makeSafeQuery("INSERT INTO `orders` (`status_id_orders`, `users_id_orders`) VALUES (?, ?)", [1, getUserID()]);
        if (!$isSuccess) setAnswer("FAIL");
        $orders["id_orders"] = $GLOBALS["link"]->lastInsertId();
    }

    if ($actionItem == "add" && $item["count_items"] >= $countItem) {
        $check = makeSelectQuery("SELECT `id_baskets` FROM `baskets` WHERE `items_id_baskets` = ? AND `orders_id_baskets` = ?", [$idItem, $orders["id_orders"]], true);
        if ($check == "FAIL") setAnswer("FAIL");

        $isSuccess = false;
        if (!empty($check)) {
            $isSuccess = makeSafeQuery("UPDATE `baskets` SET `count_baskets` = ? WHERE `id_baskets` = ?", [$countItem, $check["id_baskets"]]);
        } else {
            $isSuccess = makeSafeQuery("INSERT INTO `baskets` (`items_id_baskets`, `count_baskets`, `orders_id_baskets`) VALUES (?, ?, ?)", [$idItem, $countItem, $orders["id_orders"]]);
        }

        setAnswer($isSuccess ? "OK" : "FAIL");
    } else if ($actionItem == "remove") {
        //
        $isSuccess = makeSafeQuery("DELETE FROM `baskets` WHERE `items_id_baskets` = ? AND `orders_id_baskets` = ?", [$idItem, $orders["id_orders"]]);
        setAnswer($isSuccess ? "OK" : "FAIL");
    } else {
        setAnswer("FAIL");
    }
}

function deleteAvatar()
{
    $user = getUserInfo();
    if (count($user) < 1 || $user["avatar_users"] == null) setAnswer("FAIL");

    $file = __DIR__ . "/../upload/avatars/$user[avatar_users]";
    if (!file_exists($file)) setAnswer("FAIL");
    @unlink($file);

    $isSuccess = makeSafeQuery("UPDATE `users` SET `avatar_users` = NULL WHERE `id_users` = ?", [$user["id_users"]]);
    if (!$isSuccess) setAnswer("FAIL");

    setAnswer("OK", ["src" => getValidImage("avatars/")]);
}

function buyItems($json)
{
    unset($json["server_type"]);
    $validatedData = getValidatedData($json, "basket.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");
    $json = $validatedData["data"];

    $baskets = makeSelectQuery("SELECT * FROM `baskets` JOIN `orders` ON `orders`.`id_orders` = `baskets`.`orders_id_baskets` JOIN `items` ON `items`.`id_items` = `baskets`.`items_id_baskets` WHERE `orders`.`users_id_orders` = ? AND `orders`.`status_id_orders` = ?", [getUserID(), 1], false);
    if ($baskets == "FAIL") setAnswer("FAIL");
    $sql = "";
    $params = [];
    foreach ($baskets as $basket) {
        $sql .= "UPDATE `baskets` SET `cost_baskets` = ?, `discount_baskets` = ? WHERE `id_baskets` = ?;";
        array_push($params, $basket["cost_items"], $basket["discount_items"], $basket["id_baskets"]);
    }
    if ($sql == "" || $params == []) setAnswer("FAIL");
    $isSuccess = makeSafeQuery($sql, $params);
    if (!$isSuccess) setAnswer("FAIL");

    $datetime = date("y-m-d H:i:s");
    $isSuccess = makeSafeQuery("UPDATE `orders` SET
        `status_id_orders` = ?,
        `datetime_buy_orders` = ?,
        `address_orders` = ?,
        `note_orders` = ?,
        `datetime_start_orders` = ?,
        `datetime_end_orders` = ?
        WHERE `users_id_orders` = ? AND `status_id_orders` = ?",
        [2,
        $datetime,
        $json["address_orders"],
        $json["note_orders"],
        $json["datetime_plan_orders"]["start"],
        $json["datetime_plan_orders"]["end"],
        getUserID(), 1]);

    if (!$isSuccess) setAnswer("FAIL");

    makeSafeQuery("SET SESSION SQL_MODE = ''", []);
    $basketInfo = makeSelectQuery("SELECT
        `id_orders`,
        `id_baskets`,
        `count_baskets`,
        `cost_baskets`,
        `discount_baskets`,
        `users_id_orders`,
        `datetime_buy_orders`,
        `id_items`,
        `name_items`,
        `image_items_images`,
        `cost_items`,
        `name_status`
        FROM `baskets`
        JOIN `items` ON `id_items` = `items_id_baskets`
        JOIN `orders` ON `id_orders` = `orders_id_baskets`
        JOIN `status` ON `id_status` = `status_id_orders`
        LEFT JOIN `items_images` ON `items_id_items_images` = `id_items`
        WHERE `users_id_orders` = ? AND `status_id_orders` = ? AND `datetime_buy_orders` = ?
        GROUP BY `id_items`
    ", [getUserID(), 2, $datetime], false);

    if ($basketInfo == "FAIL" || empty($basketInfo)) setAnswer("FAIL");

    setAnswer("OK", getBasketHTML($basketInfo));
}

function changeFavorites($idItem)
{
    $validatedData = getValidatedData(["id_items" => $idItem], "index.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSelectQuery("SELECT `id_favorites` FROM `favorites` WHERE `users_id_favorites` = ? AND `items_id_favorites` = ?", [getUserID(), $idItem], false);
    if ($isSuccess == "FAIL") setAnswer("FAIL");

    if (count($isSuccess) == 0) {
        $isSuccess = makeSafeQuery("INSERT INTO `favorites` (`users_id_favorites`, `items_id_favorites`) VALUES (?, ?)", [getUserID(), $idItem]);
    } else {
        $isSuccess = makeSafeQuery("DELETE FROM `favorites` WHERE `users_id_favorites` = ? AND `items_id_favorites` = ?", [getUserID(), $idItem]);
    }

    setAnswer($isSuccess ? "OK" : "FA6IL");
}

function deleteItem($idItem)
{
    $validatedData = getValidatedData(["id_items" => $idItem,  "name_items" => 1, "count_items" => 1, "cost_items" => 1, "items_type_id_items" => 1], "editItem.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");
    
    $itemImages = makeSelectQuery("SELECT `image_items_images` FROM `items_images` WHERE `items_id_items_images` = ?", [$idItem], false);
    if ($itemImages == "FAIL") setAnswer("FAIL");
    foreach ($itemImages as $image) {
        @unlink(__DIR__ . "/../upload/items/$image[image_items_images]"); 
    }

    $isSuccess = makeSafeQuery("DELETE FROM `items` WHERE `id_items` = ?", [$idItem]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function searchItems($json)
{
    $data = getValidatedData($json, "index.php");
    if (!$data["isCorrect"]) setAnswer("FAIL");

    $data = $data["data"];
    $isPopularItems = !empty($data["popular_items"]);
    $offset = $data["offset_search_items"];
    $attributes = $data["id_search_attributes"] ?? [];
    $types = empty($data["items_type_id_search_items"]) ? [] : $data["items_type_id_search_items"];
    $strictType = empty($data["strict_search"]) ? "OR" : "AND";
   
    $whereSQL = "";
    $whereParams = [];

    if (!empty($data) && count($data) > 1) {
        unset($data["items_type_id_search_items"], $data["id_search_attributes"], $data["offset_search_items"], $data["strict_search"]);
        
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

function deleteItemProperties($idItemsProperties)
{
    $validatedData = getValidatedData(["id_items_properties" => $idItemsProperties], "editItem.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("DELETE FROM `items_properties` WHERE `id_items_properties` = ?", [$idItemsProperties]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function deleteFromTable($table, $id)
{
    $validatedData = getValidatedData(["id_$table" => $id], "editTable.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    if (!in_array($table, ["properties", "items_type"])) setAnswer("FAIL");
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
    $validatedData = getValidatedData(["id_attributes" => $id], "editTable.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("DELETE FROM `attributes` WHERE `id_attributes` = ?", [$id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function bannedUser($id)
{
    if ($id == 1) setAnswer("FAIL");

    $validatedData = getValidatedData(["id_users" => $id], "editUser.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `users` SET `is_banned_users` = CASE WHEN `is_banned_users` = 1 THEN 0 ELSE 1 END WHERE `id_users` = ?", [$id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function deleteUser($id)
{
    if ($id == 1) setAnswer("FAIL");

    $validatedData = getValidatedData(["id_users" => $id], "editUser.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $userImg = makeSelectQuery("SELECT `avatar_users` FROM `users` WHERE `id_users` = ?", [$id], true);
    if ($userImg == "FAIL") setAnswer("FAIL");
    if (!empty($userImg["avatar_users"])) {
        @unlink(__DIR__ . "/../upload/avatars/$userImg[avatar_users]");
    }

    $isSuccess = makeSafeQuery("DELETE FROM `users` WHERE `id_users` = ?", [$id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function deliverUsers($id)
{
    if ($id == 1) setAnswer("FAIL");

    $validatedData = getValidatedData(["id_users" => $id], "editUser.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");


    $isSuccess = makeSafeQuery("UPDATE `users` SET `roles_id_users` = CASE WHEN `roles_id_users` = 3 THEN 2 ELSE 3 END WHERE `id_users` = ? AND `roles_id_users` != ?", [$id, 1]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function supportUsers($id)
{
    if ($id == 1) setAnswer("FAIL");

    $validatedData = getValidatedData(["id_users" => $id], "editUser.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `users` SET `roles_id_users` = CASE WHEN `roles_id_users` = 4 THEN 2 ELSE 4 END WHERE `id_users` = ? AND `roles_id_users` != ?", [$id, 1]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function acceptOrders($id)
{
    $validatedData = getValidatedData(["id_orders" => $id], "allOrders.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `orders` SET `status_id_orders` = ?, `users_deliver_orders` = ?, `datetime_start_deliver_orders` = ? WHERE `id_orders` = ?", [3, getUserID(), date("y-m-d H:i:s"), $id]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function statusOrders($id)
{
    $validatedData = getValidatedData(["id_orders" => $id], "myOrders.php");

    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `orders` SET `status_id_orders` = `status_id_orders` + 1,
        `datetime_end_deliver_orders` =
        CASE
            WHEN `status_id_orders` = 5 THEN ?
            ELSE NULL
        END
        WHERE `id_orders` = ? AND `status_id_orders` < 5
    ", [date("y-m-d H:i:s"), $id]);
    if (!$isSuccess) setAnswer("FAIL");

    $status = makeSelectQuery("SELECT `name_status`
        FROM `baskets`
        JOIN `orders` ON `id_orders` = `orders_id_baskets`
        JOIN `status` ON `id_status` = `status_id_orders`
        WHERE `id_orders` = ?
    ", [$id], true);
    if ($status == "FAIL") setAnswer("FAIL");

    setAnswer("OK", ["name_status" => $status["name_status"]]);
}

function receiptOrders($id)
{
    $validatedData = getValidatedData(["id_orders" => $id], "deliveryItem.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `orders` SET `status_id_orders` = ?, `datetime_receipt_orders` = ? WHERE `id_orders` = ? AND `users_id_orders` = ?", [6, date("y-m-d H:i:s"), $id, getUserID()]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function startTalk($json)
{
    $validatedData = getValidatedData($json, "support.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $datetime = date("y-m-d H:i:s");
    $isSuccess = makeSafeQuery("INSERT INTO `talks` (`users_id_talks`, `title_talks`, `datetime_start_user_talks`) VALUES (?, ?, ?)", [getUserID(), $validatedData["data"]["title_talks"], $datetime]);
    if (!$isSuccess) setAnswer("FAIL");

    unset($validatedData["data"]["title_talks"]);
    $talksId = $GLOBALS["link"]->lastInsertId();

    $sql = getInsertSQL(
        array_merge(
            array_diff_key($validatedData["data"], ["image_supports" => true]),
            ["datetime_supports" => $datetime, "users_write_supports" => getUserID(), "talks_id_supports" => $talksId, "image_supports" => $validatedData["data"]["image_supports"]["current_name"] ?? null]
        )
    );
    $isSuccess = makeSafeQuery("INSERT INTO `supports` ($sql[sql]) VALUES ($sql[question])", $sql["params"]);

    if (!$isSuccess || !empty($validatedData["data"]["image_supports"]) && !file_put_contents(__DIR__ . "/../upload/supports/" . $validatedData["data"]["image_supports"]["current_name"], $validatedData["data"]["image_supports"]["content"])) setAnswer("FAyIL");

    $messages = makeSelectQuery("SELECT
        `user`.`id_users` AS `user_id`,
        `user`.`name_users` AS `user_name`,
        `user`.`roles_id_users` AS `user_role`,
        `user`.`avatar_users` AS `user_avatar`,
        `support`.`id_users` AS `support_id`,
        `support`.`name_users` AS `support_name`,
        `support`.`roles_id_users` AS `support_role`,
        `support`.`avatar_users` AS `support_avatar`,
        `users_write_supports`,
        `title_talks`,
        `id_supports`,
        `is_end_talks`,
        `talks_id_supports`,
        `text_supports`,
        `image_supports`,
        `datetime_supports`,
        `users_id_talks`
        FROM `supports`
        JOIN `talks` ON `id_talks` = `talks_id_supports`
        JOIN `users` AS `user` ON `user`.`id_users` = `users_id_talks`
        LEFT JOIN `users` AS `support` ON `support`.`id_users` = `users_support_talks`
        WHERE `id_supports` = ? ", [$GLOBALS["link"]->lastInsertId()], false);
    if ($messages == "FAIL") setAnswer("FAIL");

    setAnswer("OK", ["message" => getStartMessageHTML($messages, isSupport()), "chat" => getChatHTML($messages)]);
}

function continueTalk($json)
{
    $validatedData = getValidatedData($json, "support.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $datetime = date("Y-m-d H:i:s");

    $sql = getInsertSQL(
        array_merge(
            array_diff_key($validatedData["data"], ["image_supports" => true]),
            ["datetime_supports" => $datetime, "users_write_supports" => getUserID(), "image_supports" => $validatedData["data"]["image_supports"]["current_name"] ?? null]
        )
    );

    $isSuccess = makeSafeQuery("INSERT INTO `supports` ($sql[sql]) VALUES ($sql[question])", $sql["params"]);
    if (!$isSuccess || !empty($validatedData["data"]["image_supports"]) && !file_put_contents(__DIR__ . "/../upload/supports/" . $validatedData["data"]["image_supports"]["current_name"], $validatedData["data"]["image_supports"]["content"])) setAnswer("FAIL");
    $id = $GLOBALS["link"]->lastInsertId();
        
    $userInfo = getUserInfo();

    if (empty($userInfo["name_users"])) setAnswer("FAIL");
    
    $message = [
        "name" => $userInfo["name_users"],
        "text" => $validatedData["data"]["text_supports"],
        "date" => dateformat($datetime),
        "image" => $validatedData["data"]["image_supports"]["current_name"] ?? "",
        "id" => $id
    ];

    setAnswer($userInfo ? "OK" : "FAIL", ["message" => getMessageHTML($message, "me")]);
}

function pingMessage($json)
{
    $validatedData = getValidatedData($json, "support.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $message = makeSelectQuery("SELECT
        `user`.`id_users` AS `user_id`,
        `user`.`name_users` AS `user_name`,
        `user`.`roles_id_users` AS `user_role`,
        `user`.`avatar_users` AS `user_avatar`,
        `support`.`id_users` AS `support_id`,
        `support`.`name_users` AS `support_name`,
        `support`.`roles_id_users` AS `support_role`,
        `support`.`avatar_users` AS `support_avatar`,
        `users_write_supports`,
        `title_talks`,
        `id_supports`,
        `is_end_talks`,
        `talks_id_supports`,
        `text_supports`,
        `image_supports`,
        `datetime_supports`,
        `users_id_talks`
        FROM `supports`
        JOIN `talks` ON `id_talks` = `talks_id_supports`
        JOIN `users` AS `user` ON `user`.`id_users` = `users_id_talks`
        LEFT JOIN `users` AS `support` ON `support`.`id_users` = `users_support_talks`
        WHERE `id_supports` > ? AND (`user`.`id_users` = ? OR `support`.`id_users` = ?) AND `users_write_supports` != ?", [$validatedData["data"]["id_supports"], getUserID(), getUserID(), getUserID()], true);
    if ($message == "FAIL") setAnswer("FAIL");
    if (empty($message)) setAnswer("NOTFOUND");
    
    $result = [
        "name" => $message["users_write_supports"] == $message["support_id"] ? $message["support_name"] : $message["user_name"],
        "text" => $message["text_supports"],
        "image" => $message["image_supports"],
        "date" => dateformat($message["datetime_supports"]),
        "id" => $message["id_supports"]
    ];

    setAnswer("OK", ["message" => getMessageHTML($result, $message["users_write_supports"] == getUserID() ? "me" : "other")]);
}

function endTalk($idTalks)
{
    $validatedData = getValidatedData(["talks_id_supports" => $idTalks], "support.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `talks` SET `is_end_talks` = ?, `datetime_end_user_talks` = ? WHERE `id_talks` = ?", [1, date("y-m-d H:i:s"), $idTalks]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function acceptSupport($idTalks)
{
    $validatedData = getValidatedData(["id_talks" => $idTalks], "support.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $check = makeSelectQuery("SELECT `users_support_talks` FROM `talks` WHERE `id_talks` = ?", [$idTalks], true);
    if ($check == "FAIL" || $check["users_support_talks"] != null) setAnswer("FAIL");

    $isSuccess = makeSafeQuery("UPDATE `talks` SET `users_support_talks` = ?, `datetime_accept_support_talks` = ? WHERE `id_talks` = ?", [getUserID(), date("y-m-d H:i:s"), $idTalks]);
    setAnswer($isSuccess ? "OK" : "FAIL");
}

function getTalkHTML($idTalks)
{
    $validatedData = getValidatedData(["talks_id_supports" => $idTalks], "support.php");
    if (!$validatedData["isCorrect"]) setAnswer("FAIL");

    $isSupport = isSupport();
    $whereField = $isSupport ? "`users_support_talks`" : "`users_id_talks`";
    $orderByField = $isSupport ? "`datetime_accept_support_talks`" : " `datetime_start_user_talks`";

    $messages = makeSelectQuery("SELECT
        `user`.`id_users` AS `user_id`,
        `user`.`name_users` AS `user_name`,
        `user`.`roles_id_users` AS `user_role`,
        `user`.`avatar_users` AS `user_avatar`,
        `support`.`id_users` AS `support_id`,
        `support`.`name_users` AS `support_name`,
        `support`.`roles_id_users` AS `support_role`,
        `support`.`avatar_users` AS `support_avatar`,
        `users_write_supports`,
        `title_talks`,
        `is_end_talks`,
        `id_supports`,
        `talks_id_supports`,
        `text_supports`,
        `image_supports`,
        `datetime_supports`,
        `users_id_talks`
        FROM `supports`
        JOIN `talks` ON `id_talks` = `talks_id_supports`
        JOIN `users` AS `user` ON `user`.`id_users` = `users_id_talks`
        LEFT JOIN `users` AS `support` ON `support`.`id_users` = `users_support_talks`
        WHERE $whereField = ? AND `id_talks` = ?
        ORDER BY `is_end_talks` ASC, $orderByField DESC
    ", [getUserID(), $idTalks], false);
    if ($messages == "FAIL" || $messages == "") setAnswer("FAIL");

    setAnswer("OK", ["messages" => getStartMessageHTML($messages, $isSupport)]);
}

function getStartMessageHTML($messages, $isSupport = false) {
    $talksID = null;
    $result = "";

    foreach ($messages as $index => $message) {
        if ($talksID != $message["talks_id_supports"]) {
            $result .= "<article class='talk' id-talks='$message[talks_id_supports]'>
                <h1 class='talk-title'>Обращение №$message[talks_id_supports] | $message[title_talks]</h1>
                <div class='messages'>
            ";
            $talksID = $message["talks_id_supports"];
        }
        $isMyMessage = getUserID() == $message["users_write_supports"] ? "me" : "other";
        $name = $message["users_write_supports"] == $message["support_id"] ? $message["support_name"] : $message["user_name"];
        $result .= getMessageHTML(["name" => $name, "text" => $message["text_supports"], "image" => $message["image_supports"], "date" => dateformat($message["datetime_supports"]), "id" => $message["id_supports"]], $isMyMessage);
        if ($index == count($messages) - 1 || $messages[$index + 1]["talks_id_supports"] != $message["talks_id_supports"]) {
            $startTalkFrom = "";
            if ($message["is_end_talks"] != 1) {
                    $buttonEndTalk = "";
                    if (!$isSupport) {
                        $buttonEndTalk .= "<button class='button end-talk' data-id='$talksID'>Закончить диалог</button>";
                    }
                    $startTalkFrom .= "<form action='/user/support.php' method='POST' class='form continue-talk'>
                        <div class='field hidden'>
                            <input type='hidden' class='input hidden' data-name='talks_id_supports' value='$talksID'>
                        </div>
                        <div class='field'>
                            <span class='error-wrapper'>
                                <p class='error'></p>
                            </span>
                            <textarea class='input' data-name='text_supports'></textarea>
                        </div>
                        <div class='field'>
                            <span class='error-wrapper'>
                                <p class='error'></p>
                            </span>
                            <img src='' class='hidden'>
                            <input class='input file' type='file' data-name='image_supports'>
                        </div>
                        <div class='field'>
                            $buttonEndTalk
                            <input class='button' type='submit' name='submit_button' value='Отправить'>
                        </div>
                    </form>";
            }
            $result .= "</div>$startTalkFrom</article>";
        }
    }

    return $result;
}

function getMessageHTML($message = ["name" => "", "text" => "", "date" => "", "image" => "", "id" => ""], $who = "")
{
    $img = "";
    if ($message["image"] != "") {
        $img = "<img class='message-image' src='" . getValidImage("supports/$message[image]") . "'>";
    }
    return "<div class='message $who' data-id-support='$message[id]'>
        <p class='message-name'>$message[name]</p>
        <p class='message-text'>$message[text]</p>
        <p class='message-date'>$message[date]</p>
        $img
    </div>";
}

function getChatHTML($messages) {
    $chatsHTML = "";
    $includeChats = [];
    foreach ($messages as $message) {
        if (in_array($message["talks_id_supports"], $includeChats)) continue;
        $includeChats[] = $message["talks_id_supports"];
        $status = $message["is_end_talks"] == 0 ? "В процессе" : "Закрыто";
        $chatsHTML .= "<div class='chat' data-id-talks='$message[talks_id_supports]'>
            <img class='avatar' src='" . getValidImage("avatars/$message[user_avatar]") ."'>
            <p class='chat-username'>$message[user_name]</p>
            <p class='chat-title'>$message[title_talks]</p>
            <p class='chat-status'>$status</p>
        </div>";
    }
    return $chatsHTML;
}

function getImagesItemHTML($img = [], $isAdminFile = false)
{
    $id = empty($img["id_items_images"]) ? "" : "data-id-items-images='$img[id_items_images]'";
    $path = empty($img["image_items_images"]) ? "" : "data-path='$img[image_items_images]'";
    $imageName = empty($img["image_items_images"]) ? "" : $img["image_items_images"];
    $buttonRemoveHTML = "";
    if ($isAdminFile) {
        $buttonRemoveHTML .= "<button class='button'>Убрать это фото</button>";
    }
    return "<span class='image' $id $path>
        <img src='" . getValidImage("items/$imageName") ."'>
        $buttonRemoveHTML
    </span>";
}

function getSliderImagesItemHTML($imagesItem = [], $addBaseImage = false)
{   
    $isAdminFile = basename(dirname($_SERVER["SCRIPT_FILENAME"])) == "admin";
    $imagesItemHTML = "";
    if (!empty($imagesItem)) {
        foreach ($imagesItem as $img) {
            $imagesItemHTML .= getImagesItemHTML($img, $isAdminFile);
        }
    } else if ($addBaseImage) {
        $imagesItemHTML = getImagesItemHTML();
    }

    return "<div class='images-view'>
        <button class='images-switch-left button hidden'><img src='/assets/img/selectArrow.png'></button>
        <div class='images-container'>
            $imagesItemHTML
        </div>
        <button class='images-switch-right button hidden'><img src='/assets/img/selectArrow.png'></button>
    </div>";
}