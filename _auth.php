<?php
/* Проверка авторизации */
$cookie_token = null;
$username = null;
$isAuth = false;
if (isset($_COOKIE['token'])) {
    $cookie_token = $_COOKIE['token'];

    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
    $query = "SELECT username, date_reg, token FROM users WHERE token='$cookie_token' ORDER BY date_reg DESC LIMIT 1";
    if (mysqli_connect_errno()) {
        printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", mysqli_connect_error());
        exit;
    }
    if ($result = $mysqli->query($query)) {
        $row = $result->fetch_assoc();
        if ($row['username'] != '') {
            $username = $row['username'];
            $isAuth = true;
        }
        $result->close();
    }
    $mysqli->close();
}
/* Окончание проверки авторизации */
?>