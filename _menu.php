<?php $pagename = basename($_SERVER['PHP_SELF']); ?>

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
            <?php /* Исправить на цикл (?) */ ?>
            <ul class="header-menu">
                <li <?php if($pagename=='map.php') echo "class='header-menu-selected'"?>>
                    <a href="map.php">Карта</a>
                </li>
                <li <?php if($pagename=='list.php') echo "class='header-menu-selected'"?>>
                    <a href="list.php">Список</a>
                </li>
                <li <?php if($pagename=='fav.php') echo "class='header-menu-selected'"?>>
                    <a href="fav.php">Закладки</a>
                </li>
                <li <?php if($pagename=='create.php') echo "class='header-menu-selected'"?>>
                    <a href="create.php">Создать</a>
                </li>
                <li <?php if($pagename=='upload.php') echo "class='header-menu-selected'"?>>
                    <a href="upload.php">Загрузить</a>
                </li>
                <li <?php if($pagename=='search.php') echo "class='header-menu-selected'"?>>
                    <a href="search.php">Поиск</a>
                </li>
            </ul>
        </nav>
    </div>
</header>