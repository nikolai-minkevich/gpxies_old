<?php
/* Проверка авторизации */
$cookie_token = null;
$username = null;
$userid = null;
$isAuth = false;
if (isset($_COOKIE['token'])) {
    $cookie_token = $_COOKIE['token'];

    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
    $cookie_token = $mysqli->real_escape_string($cookie_token);
    $query = "SELECT id, username, date_reg, token FROM users WHERE token='$cookie_token' ORDER BY date_reg DESC LIMIT 1";
    if (mysqli_connect_errno()) {
        printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", mysqli_connect_error());
        exit;
    }
    if ($result = $mysqli->query($query)) {
        $row = $result->fetch_assoc();
        if ($row['username'] != '') {
            $username = $row['username'];
            $userid = $row['id'];
            $isAuth = true;
        }
        $result->close();
    }
    $mysqli->close();
}
/* Окончание проверки авторизации */
?>