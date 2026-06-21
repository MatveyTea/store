<?php
include_once __DIR__ . "/../../app/server/function.php";

if (!isAdmin()) {
    redirect();
}

$users = getUsers("WHERE `id_users` != ?", [1]);
if (str_contains($users, "{\"status\":\"FAIL\"}")) {
   redirect("admin/admin.php");
}

getModalHTML();
include_once __DIR__ . "/../../app/server/header.php";
?>

<main class="content">
    <form action="/admin/editUser.php" class="form" method="POST">
        <legend class="legend">Поиск пользователей</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="search" data-name="email_search_users" data-is-insert-server="1">
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <label class="label"></label>
            <select class="input" data-name="is_banned_search_users" data-is-insert-server="1">
                <option value="-1" selected disabled>Выбрать</option>
                <option value="2">Всех</option>
                <option value="1">Заблокированных</option>
                <option value="0">Разблокированных</option>
            </select>
            <span class="error-wrapper">
                <p class="error"></p>
            </span>
        </div>
        <div class="field">
            <input type="submit" name="submit_button" class="button" value="Найти">
        </div>
    </form>
    <section class="users">
        <?= $users ?>
    </section>
</main>

<?php include_once __DIR__ . "/../../app/server/footer.php"; ?>