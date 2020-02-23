<?php
include_once "../conf.php";
/* Проверка авторизации */
include('./_auth.php');

spl_autoload_register(function ($class) {
	$class = str_replace('\\', '/', $class);
    //include __DIR__.'/../' . $class . '.php';
    include __DIR__.'/lib/' . $class . '.php';
});

$idhash = null;
$data = null;
if (isset($_GET['id'])) {
    $idhash = $_GET['id'];

    // Поиск данных в БД
    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
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
echo "use OK";
$gpx = new phpGPX();
echo "new OK";
$file = $gpx->load("./gpx/$data[2].gpx");
echo "load OK";


///////////////





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

<body class="start-page" ng-controller='trackView'>

    <?php include('./_menu.php'); ?>

    <?php if (!!$data) : ?>

        <?php // echo "username: $data[0], title: $data[1], hash: $data[2], date: $data[3], isPrivate: $data[4]"; 
        ?>

        <main class="page-main">
            <div class="container--max">
                <h2><?php echo "$data[1]" ?> by <?php echo "<a href='./map.php?user=$data[0]'>$data[0]</a>" ?></h2>


                <div id="mapid"></div>
                <script src="js/main.js"></script>
                <script>
                    showTrack('<?php echo "$data[2]"; ?>.gpx');
                </script>
                <button class="page-main__button--primary" onclick='clearCurrentTrack()'>Clear all</button>
                <p id='track-info'></p>
                <p id='coords'></p>
                <p id="trackList">{{trackList}}
                    <ul class="page-main__track-list">
                        <li class="track-list__item" ng-repeat="t in trackList">
                            {{t.id}}, {{t.title}}, {{t.filename}} <button ng-click="showTrack1(t.filename)">show</button>
                        </li>
                    </ul>
            </div>
            </p>
        </main>>




    <?php elseif (!!$idhash) : ?>
        Нет такого трека.
    <?php else : ?>
        Не задан id трека.
    <?php endif ?>


</html>