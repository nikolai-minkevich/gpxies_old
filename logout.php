<?php
include_once "../conf.php";

/* Проверка авторизации */
include('./_auth.php');

if (isset($isAuth) && isset($username)) {
    
    unset($_COOKIE['token']);
    setcookie('token', null, time()-10000, '/'); 

    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
    $query = "UPDATE users SET token='' WHERE username='$username';";

    if (mysqli_connect_errno()) {
        printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", mysqli_connect_error());
        exit;
    }
    if ($mysqli->query($query)) {
        echo "Вы успешно вышли из системы [$mysqli->affected_rows]";
        $username=null;
        $isAuth=false;
    }
    $mysqli->close();


} else {
    include('./_notdenied.php'); 
}


?>