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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.4.0/gpx.min.js"></script>



</head>

<body class="create" ng-controller='trackView'>

    <?php include('./_menu.php'); ?>

    <?php if ($isAuth) : ?>

        <div class="container">
            <header class="create-header">
                <h1 class="title__primary">
                    Создать новый трек
                </h1>
            </header>
        </div>
        <main class="page-main">
            <div class="container">
                <form action="#">
                    <div class="create-form--container">
                        <select name="sport-choice" id="sport-choice-id">
                            <option value="1" id="bicycle">Велосипед</option>
                            <option value="2" id="run">Бег</option>
                            <option value="3" id="skiing">Лыжи</option>
                        </select>
                        <input type="text" class="track-name--input" style="text-indent: 5px;" placeholder="Название трека" required />
                        <div class="private-checkbox--container">
                            <input type="checkbox" value="1" id="private-checkbox" class="check__input"> <label for="private-checkbox" class="check">
                                Приватный</label>
                        </div>
                        <button class="button--primary">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
            <div class="container--max">
                <div id="mapid"></div>
                <script src="../js/main.js"></script>
            </div>
        </main>

    <?php else : ?>

        <?php include('./_notdenied.php'); ?>

    <?php endif ?>

</body>

</html>