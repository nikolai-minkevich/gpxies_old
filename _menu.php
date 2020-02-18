<header class="page-header">
    <div class="container">
        <div class="header__top">
            <h1 class="page-header__title"> gp<big>x</big>ies</h1>
            <?php if ($isAuth) :  ?>
                <div class="header-btn">
                    <a class="login" href="settings.php"><?php echo "$username";?></a>
                    <a class="sign-out" href="logout.php">выйти</a>
                </div>
            <?php else : ?>
                <div class="header-btn">
                    <a class="login" href="login.php">войти</a>
                    <a class="sign-up" href="signup.php">зарегистрироваться</a>
                </div>
            <?php endif ?>
        </div>
        <nav>
            <ul class="header-menu">
                <li>
                    <a href="map.php">Карта</a>
                </li>
                <li>
                    <a href="list.php">Список</a>
                </li>
                <li>
                    <a href="fav.php">Закладки</a>
                </li>
                <li>
                    <a href="create.php">Создать</a>
                </li>
                <li>
                    <a href="upload.php">Загрузить</a>
                </li>
                <li>
                    <a href="search.php">Поиск</a>
                </li>
            </ul>
        </nav>
    </div>
</header>