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

<?php
if (isset($isAuth) && isset($username)) {

    unset($_COOKIE['token']);
    setcookie('token', null, time() - 10000, '/');

    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
    $query = "UPDATE users SET token='' WHERE username='$username';";

    if (mysqli_connect_errno()) {
        printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", mysqli_connect_error());
        exit;
    }
    if ($mysqli->query($query)) {
        $username = null;
        $isAuth = false;

?>

        <script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2 * 1000);
        </script>

        <div class="message message-success">
            <p class="message-header">Вы успешно вышли из системы.</p>
            <p>Сейчас вы будете перенаправлены на <a href="index.php">главную страницу</a></p>
        </div>

<?php


    }
    $mysqli->close();
} else {
    include('./_notdenied.php');
}


?>

</body>

</html>