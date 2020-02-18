<?
include_once "../conf.php";

/* Проверка авторизации */
include('./_auth.php');

if (!!$username) {

    // Поиск данных в БД
    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
    $query = "SELECT id FROM users WHERE username='$username' ORDER BY date_reg DESC LIMIT 1";

    if (mysqli_connect_errno()) {
        $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
        exit;
    }

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();

        $id = $row[0];
        $token = '';

        $mysqli2 = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
        $query = "UPDATE users SET token='$token' WHERE id='$id';";
        if (mysqli_connect_errno()) {
            $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
            exit;
        }

        if ($mysqli2->query($query)) {
            unset($_COOKIE['token']);
            setcookie('token', null, -1, '/');
            $isAuth = false;
            $username = null;
        } else {
            $msg = "Ошибка записи в БД";
        }
        $mysqli2->close();


        $result->close();
    }
    $mysqli->close();
}
