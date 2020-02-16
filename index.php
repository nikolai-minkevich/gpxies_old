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
$query = "SELECT username, date_reg, token FROM users WHERE token='$cookie_token' ORDER BY date_reg DESC LIMIT 1";
if (mysqli_connect_errno()) {
    printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", mysqli_connect_error());
    exit;
}
if ($result = $mysqli->query($query)) {
    $row = $result->fetch_assoc();
    if ($row['username'] != '') {
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

<?php if ($isAuth) :  ?>
        <?php 
        echo "Вы успешно авторизовались";
        //echo "<script type='text/javascript'> document.location = 'main.php'; </script>"; ?>
    <?php else : ?>
        <?php //echo "<script type='text/javascript'> document.location = 'login.php'; </script>"; ?>
        Вы не авторизованы. Для продолжения <a href="login.php">залогиньтесь</a> или <a href="signup.php">зарегистрируйтесь</a>.
    <?php endif ?>

</html>