<?php
include_once "../conf.php";
include('./_auth.php');
include('./_autoload.php');

/////// работа с загруженным файлом /////////////
$error = [];
$isSuccess = false;
$uploadfilename = null;

if (isset($_POST['act']) && isset($userid)) {
    if ($_POST['act'] == 'upload') {
        // Информация о треке по-умолчанию
        $title = "New track " . date('Y-m-d');

        // Если они указаны, то заменяются на введённые с формы
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
        }

        // Метаинформация
        $meta = [
            // Оригинальное имя файла на компьютере клиента
            "origin_filename"   => $_FILES['userfile']['name'],
            // Mime-тип файла
            "mime"              => $_FILES['userfile']['type'],
            // Размер в байтах принятого файла
            "size"              => $_FILES['userfile']['size'],
            // Временное имя, с которым принятый файл был сохранен на сервере
            "temp_name"         => $_FILES['userfile']['tmp_name'],
            // Код ошибки
            "error_code"        => $_FILES['userfile']['error'],

        ];

        echo '<pre>';
        print_r($meta);
        echo '</pre>';

        // Обработка ошибок сразу
        if (strtolower(substr($meta['origin_filename'], -4)) !== ".gpx") {
            $error = [
                "code" => 1,
                "msg"  => "Ошибка типа файла"
            ];
        }

        if (strtolower($meta['mime']) !== "application/octet-stream") {
            $error = [
                "code" => 2,
                "msg"  => "Ошибка типа файла"
            ];
        }

        if ($meta['error_code'] !== 0) {

            switch ($meta['error_code']) {
                case 1:
                    $error = [
                        "code" => 31,
                        "msg"  => "Ошибка при загрузке: UPLOAD_ERR_INI_SIZE"
                    ];
                    break;
                case 2:
                    $error = [
                        "code" => 32,
                        "msg"  => "Ошибка при загрузке: UPLOAD_ERR_FORM_SIZE"
                    ];
                    break;
                case 3:
                    $error = [
                        "code" => 33,
                        "msg"  => "Ошибка при загрузке: UPLOAD_ERR_PARTIAL"
                    ];
                    break;
                case 4:
                    $error = [
                        "code" => 34,
                        "msg"  => "Ошибка при загрузке: UPLOAD_ERR_NO_FILE"
                    ];
                    break;
                case 6:
                    $error = [
                        "code" => 36,
                        "msg"  => "Ошибка при загрузке: UPLOAD_ERR_NO_TMP_DIR"
                    ];
                    break;
                case 7:
                    $error = [
                        "code" => 37,
                        "msg"  => "Ошибка при загрузке: UPLOAD_ERR_CANT_WRITE"
                    ];
                    break;
                case 8:
                    $error = [
                        "code" => 38,
                        "msg"  => "Ошибка при загрузке: UPLOAD_ERR_EXTENSION"
                    ];
                    break;
                default:
                    $error = [
                        "code" => 3,
                        "msg"  => "Ошибка при загрузке"
                    ];
            }
        }

        if (!is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $error = [
                "code" => 4,
                "msg"  => "Ошибка при загрузке"
            ];
        }

        if ($error == []) {
            /* Создание записи в БД */
            $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
            $title = $mysqli->real_escape_string($title);
            $query = "INSERT INTO tracks (userid, title) VALUES ('$userid', '$title')";

            if (mysqli_connect_errno()) {
                $error = [
                    "code" => 9,
                    "msg"  => "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error()
                ];
            }

            if ($mysqli->query($query)) {
                $newid = $mysqli->insert_id;
                $hashid = hash('md5', $newid + time());
                $uploadfilename = hash('md5', $hashid + time());

                /* TODO: заменить 2 на 1 */
                $mysqli2 = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
                $query = "UPDATE tracks SET hashmd5='$hashid', filename='$uploadfilename.gpx' WHERE id='$newid';";
                if (mysqli_connect_errno()) {
                    $error = [
                        "code" => 9,
                        "msg"  => "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error()
                    ];
                }

                if ($mysqli2->query($query)) {
                    // Успех
                } else {
                    $error = [
                        "code" => 8,
                        "msg"  => "Ошибка записи в БД."
                    ];
                }
                $mysqli2->close();
            } else {
                $error = [
                    "code" => 8,
                    "msg"  => "Ошибка записи в БД."
                ];
            }
            $mysqli->close();
        } else {
            file_put_contents('log.txt', date("Y-m-d H:i:s")  . ' ERROR: ' . $username . '(' . $userid . ') failed to upload file with error ' . $error['code'] . ' ' . $error['msg'] . ' ( size: ' . $meta['size'] . 'kb , mime: ' . $meta['mime'] . ' , filename: ' . $origin_filename . ' )' . '<br />', FILE_APPEND | LOCK_EX);

        }

        if ($error == []) {
            /* перенос в каталог gpx */
            $uploaddir = './gpx/'; // <- перенести в конфиг
            $uploadfilepath = $uploaddir . $uploadfilename . '.gpx';

            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfilepath)) {
                $isSuccess = true;
                file_put_contents('log.txt', date("Y-m-d H:i:s") . ' SUCCESS: ' . $username . '(' . $userid . ') uploaded file to ' . $uploadfilepath . ' ( size: ' . $meta['size'] . 'kb , mime: ' . $meta['mime'] . ' , id: ' . $newid . ' , link: https://gpxies.ru/show.php?id=' . $hashid . ' )' . '<br />', FILE_APPEND | LOCK_EX);
            } else {
                $error = [
                    "code" => 1,
                    "msg"  => "Возможная атака с помощью файловой загрузки."
                ];
            }
        }
    };
};







////////// phpGPX //////////////

// ПОКА НЕ ИСПОЛЬЗУЕТСЯ

//include('./lib/phpGPX/phpGPX.php');

//use phpGPX\phpGPX;

//$gpx = new phpGPX();
//$file = $gpx->load("./gpx/$data[2].gpx");



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

<body class="upload">

    <?php include('./_menu.php'); ?>

    <?php if ($isAuth) : ?>

        <?php if ($isSuccess == true) : ?>

            <div class="message message-success">
                <p class="message-header">Трек успешно загружен.</p>
                <p>Вы можете просмотреть его по ссылке: <a href="show.php?id=<?= $hashid ?>">http://gpxies.ru/show.php?id=<?= $hashid ?></a></p>
            </div>

        <?php else : ?>

            <?php if ($error !== []) : ?>
                <div class="message message-warning message-margin-bottom ">
                    <p class="message-header">Что-то пошло не так - файл не загружен.</p>
                    <p>Повторите попытку. Подробнее об ошибке:</p>
                    <p>[<?= $error['code'] ?>] <?= $error['msg'] ?></p>
                </div>
            <?php endif ?>
            <!-- if error-->

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
                                <input type="checkbox" value="1" id="private-checkbox" class="check__input"> <label for="private-checkbox" class="check">
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

        <?php endif ?>
        <!-- if isSuccess -->

    <?php else : ?>

        <?php include('./_notdenied.php'); ?>

    <?php endif ?>
    <!-- if isAuth -->




</body>