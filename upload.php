<?php
include_once "../conf.php";
include('./_auth.php');
include('./_autoload.php');

/////// работа с загруженным файлом /////////////
if (isset($_POST['act'])) {

    $title="New track " . date('Y-m-d');
    if (isset($_POST['title'])) {
        $title = $_POST['title'];
    }

    echo "<br>Оригинальное имя файла на компьютере клиента. " . $_FILES['userfile']['name'];
    //

    echo "<br>Mime-тип файла " . $_FILES['userfile']['type'];
    echo "<br>Размер в байтах принятого файла." . $_FILES['userfile']['size'];
    echo "<br>Временное имя, с которым принятый файл был сохранен на сервере." . $_FILES['userfile']['tmp_name'];
    echo "<br>Код ошибки. " . $_FILES['userfile']['error'];
    echo "<br>name. " . $_FILES['userfile']['name'];


    /* Проверка на скам */

    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        echo "Файл " . $_FILES['userfile']['name'] . " успешно загружен.\n";

        /* Создание записи в БД */
        $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
        $title = $mysqli->real_escape_string($title);
        $query = "INSERT INTO tracks (userid, title) VALUES ('$userid', '$title')";        
        if (mysqli_connect_errno()) {
            $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
            exit;
        }

        if ($mysqli->query($query)) {
            $isCreated = true;
            $newid = $mysqli->insert_id;
            $hashid = hash('md5',$newid);

            /* TODO: заменить 2 на 1 */
            $mysqli2 = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
            $query = "UPDATE tracks SET hashmd5='$hashid', filename='$hashid.gpx' WHERE id='$newid';";
            if (mysqli_connect_errno()) {
                $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
                exit;
            }

            if ($mysqli2->query($query)) {
                echo 'track id' . $hashid;
            } else {
                $msg = "Ошибка записи в БД";
            }
            $mysqli2->close();

        } 
        $mysqli->close();



        /* перенос в каталог gpx */
        $uploaddir = './gpx/';
        $uploadfilename = $hashid;
        $uploadfilepath = $uploaddir . $uploadfilename;




        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfilepath)) {



            echo "Файл корректен и был успешно загружен. <a href='show.php?id=$uploadfilename'>Посмотреть</a>\n";
        } else {
            echo "Возможная атака с помощью файловой загрузки!\n";
        }
    } else {
        echo "Возможная атака с участием загрузки файла: ";
    }
} // if isset act




////////// phpGPX //////////////

include('./lib/phpGPX/phpGPX.php');

use phpGPX\phpGPX;

$gpx = new phpGPX();
//$file = $gpx->load("./gpx/$data[2].gpx");



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


</head>

<body class="upload">

    <?php include('./_menu.php'); ?>




<div class="container">
        <header class="upload-header">
            <div class="container__upload">
                <h1 class="title__primary">
                    Загрузить новый трек
                </h1>
            </div>
        </header>
    </div>
    <main class="page-main">
        <div class="container">
            <!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
            <form enctype="multipart/form-data" action="upload.php" method="POST">
                <input type="hidden" name="act" value="upload">
                <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
                <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
                <div class="upload-form--container__top">
                    <select name="sport-choice" id="sport-choice-id">
                        <option value="1" id="bicycle">Велосипед</option>
                        <option value="2" id="run">Бег</option>
                        <option value="3" id="skiing">Лыжи</option>
                    </select>
                    <input type="text" class="track-name--input" style="text-indent: 5px;" placeholder="Название трека" /> 
                    <div class="private-checkbox--container">
                        <input type="checkbox" value="1" id="private-checkbox" class="check__input">  <label for="private-checkbox" class="check"> 
                            Приватный</label>  
                    </div>
                </div>
                <div class="upload-form--container__bottom">
                    <!-- Название элемента input определяет имя в массиве $_FILES -->
                    <input type="file" id="upload-track-file" name="userfile"> 
                    <label for="upload-track-file" class="upload-file--button">Файлы</label>
                    <label class="upload-file-submit--button">Название загруженного файла</label>
                </div>
                <div class="upload-form--container__bottom">
                    <button type="submit" class="button--primary">
                        Загрузить
                    </button>
                </div>
            </form>
        </div>
    </main>


</body>