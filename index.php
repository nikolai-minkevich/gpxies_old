<?php
include_once "../conf.php";
//setcookie("token", 'secrett0ken', time()+3600);

$cookie_token = null;
if (isset($_COOKIE['token'])) {
    $cookie_token = $_COOKIE['token'];
}

/* Проверка авторизации */
$isAuth = false;
$mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
$query = "SELECT name, date_reg, token FROM users WHERE token='$cookie_token' ORDER BY date_reg DESC LIMIT 1";
if (mysqli_connect_errno()) {
    printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", mysqli_connect_error());
    exit;
}
if ($result = $mysqli->query($query)) {
    $row = $result->fetch_assoc();
    if ($row['name'] != '') {
        $isAuth = true;
    } 
    $result->close();
}
$mysqli->close();
/* Окончание проверки авторизации */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo ($title);?></title>
    <link rel="shortcut icon" href="/gpxies/images/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php if ($isAuth) :  ?>
        <!-- secret content -->
    <?php else : ?>
        <?php echo "<script type='text/javascript'> document.location = 'login.php'; </script>"; ?>
    <?php endif ?>
</body>

</html>