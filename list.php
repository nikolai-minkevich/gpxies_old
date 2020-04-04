<?php
include_once "../conf.php";

/* Проверка авторизации */
include('./_auth.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo ($title); ?></title>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Krub:400,600&display=swap" rel="stylesheet">
</head>

<body class="list" >

    <?php include('./_menu.php'); ?>

    <?php if ($isAuth) : ?>

        <!-- Список треков пользователя -->

        <!doctype html>

        <body class="list" ng-controller=''>
            <header class="list-header">
                <div class="container container-list">
                    <h1 class="title__primary">
                        Список треков
                    </h1>
                    <form action="#">
                        <label for="input-filter">Фильтр
                            <input id="input-filter" type="text"></label>
                    </form>
                </div>
            </header>
            <main class="page-main">
                <form action="#">
                    <div class="container container-filter">
                        <div class="container-filter--checkbox">
                        </div>
                        <div class="container-filter--type">
                            <h3 class="filter--title">Вид спорта</h3>
                            <select name="sport-choice" id="sport-choice-id">
                                <option value="1" id="bicycle">Велосипед</option>
                                <option value="2" id="run">Бег</option>
                                <option value="3" id="skiing">Лыжи</option>
                            </select>
                        </div>
                        <div class="container-filter--data">
                            <h3 class="filter--title">Дата</h3>
                            <div class="container-filter--data__distance">
                                <label for="input-filter--data__start">c</label>
                                <input id="input-filter--data__start" type="text" placeholder="дд.мм.гггг">
                                <label for="input-filter--data__finish">до</label>
                                <input id="input-filter--data__finish" type="text" placeholder="дд.мм.гггг">
                            </div>
                        </div>
                        <div class="container-filter--name">
                            <label for="input-filter--name" class="filter--title">Название</label>
                            <input id="input-filter--name" type="text">
                        </div>
                        <div class="container-filter--distance">
                            <label for="input-filter--distance" class="filter--title">Расстояние</label>
                            <input id="input-filter--distance" type="text">
                        </div>
                        <div class="container-filter--actions">
                            Действия
                        </div>

                    </div>
                </form>
                <div class="container-filter--content">
                    <div class="container container-filter">
                        <div class="container-filter--checkbox">
                            <input type="checkbox">
                        </div>
                        <div class="container-filter--type">
                            Бег
                        </div>
                        <div class="container-filter--data">
                            23.12.1998
                        </div>
                        <div class="container-filter--name">
                            бегаю бегаю бегаю бегаю бегаю бегаю бегаю бегаю бегаю бегаю
                        </div>
                        <div class="container-filter--distance">
                            2 км
                        </div>
                        <div class="container-filter--actions">
                            <div class="container-filter--actions__typical">
                                <a href="#">Ред.</a>
                                <a href="#">Удал.</a>
                            </div>
                            <a href="#" class="button--primary">Скачать GPX</a>
                        </div>
                    </div>



                </div>

            </main>





        <?php else : ?>

            <?php include('./_notdenied.php'); ?>

        <?php endif ?>
        </body>


</html>