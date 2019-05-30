<div class="container" style="width: 300px">
    <div class="text-center">
        Восстановление пароля:
    </div>
    <form action="<?= \User\RouteUser::getInstance()->getRestorePass(); ?>" method="post">
        <input type="hidden" name="doUserAction" value="restorePass">
        <label style="margin-top: 5px">Введите новый пароль:</label>
        <input type="password" class="form-control" name="pswd">
        <label style="margin-top: 5px">Подтвердите новый пароль:</label>
        <input type="password" class="form-control" name="pswd2">
        <button type="submit" class="btn btn-primary" style="margin-top: 15px">Сохранить новый пароль</button>
    </form>
</div>


