<?php
include_once "../conf.php";
/* Проверка авторизации */
include('./_auth.php');
include('./_autoload.php');

$idhash = null;
$data = null;
if (isset($_GET['id'])) {
    $idhash = $_GET['id'];

    // Поиск данных в БД
    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
    $idhash = $mysqli->real_escape_string($idhash);
    $query = "SELECT u.username, t.title, t.hashmd5, t.date, t.isprivate FROM tracks t INNER JOIN users u ON t.userid = u.id WHERE t.hashmd5 = '$idhash' ";

    if (mysqli_connect_errno()) {
        $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
        exit;
    }
    if ($result = $mysqli->query($query)) {
        $data = $result->fetch_row();
        if ($data[0] == '') {
            $data = null;
        }


        $result->close();
    }
    $mysqli->close();
}

/* Показ трека, передаваемого в GET параметром id 
http://gpxies.ru/show.php?id=cfcd208495d565ef66e7dff9f98764da
*/


/*
Получаем id
Загружаем файл в массив
Массив передаём в полилайн leaflet

*/


////////// phpGPX //////////////

include('./lib/phpGPX/phpGPX.php');

use phpGPX\phpGPX;

$gpx = new phpGPX();
$file = $gpx->load("./gpx/$data[2].gpx");

$file_stats = [
    "track_count" => count($file->tracks) ,
    "waypoint_count" => count($file->waypoints) 
];




echo "Этот файл содержит " . count($file->tracks) . " треков, " ;

/* Подсчёт треков и сегментов */

foreach ($file->tracks as $track) {
    // Statistics for whole track
    $track_stats = $track->stats->toArray();

    echo "трек содержит " . $track_stats["distance"] . 'm';

    foreach ($track->segments as $segment) {
        // Statistics for segment of track
        $segment_stats = $segment->stats->toArray();
        echo "Сегмент содержит " . $segment_stats["distance"] . 'm';
    }
}

/* Объединение всех сегментов и треков в один трек с одним сегментом */




?>

<!DOCTYPE html>
<html lang="en" ng-app='gpxiesApp'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo ($title); ?></title>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Krub:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.4.0/gpx.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js"></script>
    <script src="app/app.js"></script>
    <script src="app/trackView.js"></script>

</head>

<body class="show" ng-controller='trackView'>

    <?php include('./_menu.php'); ?>

    <?php if (!!$data) : ?>

        <div class="container container__header-show">
            <header class="show-header">
                <div class="container">
                    <div class="show-header__top">
                        <h1 class="show-header__title">
                            <span><?=$data[1]?></span>, <span>407 км</span>
                        </h1>
                        <h2 class="show-header__title--secondary">
                            Автор: <span><?php echo "<a href='./map.php?user=$data[0]'>$data[0]</a>" ?></span>, дата: <span> 2005.01.25</span>
                        </h2>
                    </div>
                    <ul class="show-header__menu">
                        <li>
                            <button>
                                Скачать
                            </button>
                        </li>
                        <li>
                            <button>
                                Создать вариант
                            </button>
                        </li>
                        <li>
                            <button>
                                Добавить в закладки
                            </button>
                        </li>
                        <li>
                            <button>
                                Редактировать
                            </button>
                        </li>
                        <li>
                            <button>
                                Удалить
                            </button>
                        </li>
                    </ul>
            </header>
            <aside class="show-statistics">
                <h3>
                    Статистика
                </h3>
                <ul class="show-statistics--list">
                    <li>
                        <span>407</span> км
                    </li>
                    <li>
                        <span>4000</span> точек
                    </li>
                    <li>
                        <span><?=$file_stats["waypoint_count"]?></span> wpt
                    </li>
                </ul>
            </aside>
        </div>
        <main class="page-main">
            <div class="container--max">
                <div id="mapid"></div>
                <script src="js/main.js"></script>
                <script>
                    showTrack('<?=$data[2]?>.gpx');
                </script>
            </div>
            <div class="container">
                <h3>
                    Полная статистика
                </h3>
                <ul class="show-statistics--list__full-statistics">
                    <li>
                        <span>407</span> км
                    </li>
                    <li>
                        <span>4000</span> точек
                    </li>
                    <li>
                        <span>25</span> wpt
                    </li>
                    <li>
                        <span>407</span> км
                    </li>
                    <li>
                        <span>4000</span> точек
                    </li>
                    <li>
                        <span>25</span> wpt
                    </li>
                    <li>
                        <span>407</span> км
                    </li>
                    <li>
                        <span>4000</span> точек
                    </li>
                    <li>
                        <span>25</span> wpt
                    </li>
                </ul>
            </div>
        </main>




    <?php elseif (!!$idhash) : ?>
        Нет такого трека.
    <?php else : ?>
        Не задан id трека.
    <?php endif ?>


</html>