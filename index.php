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

<body>
    
    <?php include('./_menu.php'); ?>

    <p>Gpsies is dead. Long live Gpxies!</p>

    <?php include('./_sitemap.php'); ?>


</html>