<?php
include_once "../conf.php";
include('./_auth.php');
include('./_autoload.php');

/////// работа с загруженным файлом /////////////
if (isset($_POST['act'])) {
    echo "<br>Оригинальное имя файла на компьютере клиента. " . $_FILES['userfile']['name'];
    //

    echo "<br>Mime-тип файла " . $_FILES['userfile']['type'];

    echo "<br>Размер в байтах принятого файла." . $_FILES['userfile']['size'];

    echo "<br>Временное имя, с которым принятый файл был сохранен на сервере." . $_FILES['userfile']['tmp_name'];

    echo "<br>Код ошибки. " . $_FILES['userfile']['error'];

    echo "<br>name. " . $_FILES['userfile']['name'];


    // добавит 

    /* Проверка на скам */

    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        echo "Файл " . $_FILES['userfile']['name'] . " успешно загружен.\n";




        /* Создание записи в БД */
        $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
        $query = "INSERT INTO tracks (userid, title, email) VALUES ('$username', '$passwordmd5', '$email')";        
        if (mysqli_connect_errno()) {
            $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
            exit;
        }

        if ($mysqli->query($query)) {
            $isCreated = true;
        } 
        $mysqli->close();



        /* перенос в каталог gpx */
        $uploaddir = './gpx/';
        $uploadfilename = hash("md5", $_FILES['userfile']['name']);
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

<body>

    <?php include('./_menu.php'); ?>

    <h1>Загрузка трека</h1>

    <!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
    <form enctype="multipart/form-data" action="upload.php" method="POST">
        <input type="hidden" name="act" value="upload">
        <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
        <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
        <!-- Название элемента input определяет имя в массиве $_FILES -->
        Отправить этот файл: <input name="userfile" type="file" />
        <input type="submit" value="Отправить файл" />
    </form>

</body>