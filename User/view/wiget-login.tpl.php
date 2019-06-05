<nav style="text-align: right; margin-right: 10px">
    <ul style="list-style: none">
        <li><a href="<?= \User\RouteUser::getInstance()->getFavoritLink(); ?>">Избранное</a></li>
        <li><a href="<?= \User\RouteUser::getInstance()->getLogoutLink(); ?>">Выйти</a></li>
    </ul>
</nav>
<?php
