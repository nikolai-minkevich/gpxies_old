<?php $pagename = basename($_SERVER['PHP_SELF']); ?>

<header class="page-header">
    <div class="container">
        <div class="header__top">
            <h1 class="page-header__title"> gp<big>x</big>ies</h1>
            <?php if ($isAuth) :  ?>
                <div class="header-btn">
                    <a class="login" href="settings.php"><?=$username?> <?=$userid?></a>
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
                <li>
                    <a href="map.php" <?php if($pagename=='map.php') echo "class='header-menu-selected'"?>>Карта</a>
                </li>
                <li >
                    <a href="list.php" <?php if($pagename=='list.php') echo "class='header-menu-selected'"?>>Список</a>
                </li>
                <li >
                    <a href="fav.php" <?php if($pagename=='fav.php') echo "class='header-menu-selected'"?>>Закладки</a>
                </li>
                <li >
                    <a href="create.php" <?php if($pagename=='create.php') echo "class='header-menu-selected'"?>>Создать</a>
                </li>
                <li >
                    <a href="upload.php" <?php if($pagename=='upload.php') echo "class='header-menu-selected'"?>>Загрузить</a>
                </li>
                <li >
                    <a href="search.php" <?php if($pagename=='search.php') echo "class='header-menu-selected'"?>>Поиск</a>
                </li>
            </ul>
        </nav>
    </div>
</header>