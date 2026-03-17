<?php
include_once __DIR__ . "/config/config.php";
include_once __DIR__ . "/function.php";

if (!isAdmin()) {
    redirect();
}

getModalHTML();
include_once __DIR__ . "/header.php";
?>

<main class="content">
    <form action="adminEditUser.php" class="form" method="POST">
        <legend class="legend">Поиск пользователей</legend>
        <div class="field">
            <label class="label"></label>
            <input class="input" type="search" data-name="email_search_users" data-is-insert-server="1">
            <p class="error"></p>
        </div>
        <div class="field">
            <label class="label"></label>
            <select class="input" data-name="is_banned_search_users" data-is-insert-server="1">
                <option value="3" disabled selected>Выбрать</option>
                <option value="2">Всех</option>
                <option value="1">Заблокированных</option>
                <option value="0">Разблокированных</option>
            </select>
            <p class="error"></p>
        </div>
        <div class="field">
            <input type="submit" name="submit_button" class="input button" value="Найти">
        </div>
    </form>
    <section class="users">
        <?= getUsers("WHERE `id_users` != ?", [1]) ?>
    </section>
</main>

<?php include_once __DIR__ . "/footer.php"; ?>