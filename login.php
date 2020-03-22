<?php
include_once "../conf.php";

/* Проверка авторизации */
include('./_auth.php');

/* Обработка полученных из формы данных */
if (isset($_POST['login'])) $username = $_POST['login'];
if (isset($_POST['pass'])) $password = $_POST['pass'];

if (isset($username) && isset($password)) {

    // Поиск данных в БД
    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
    $username = $mysqli->real_escape_string($username);
    $password = $mysqli->real_escape_string($password);

    $query = "SELECT id, username, email, passmd5 FROM users WHERE username='$username' OR email='$username' ORDER BY date_reg DESC LIMIT 1";

    if (mysqli_connect_errno()) {
        $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
        exit;
    }

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        if (password_verify($password, $row[3])) {
            
            $id = $row[0];
            $token = hash('md5', time() + strlen($username));



/* TODO: заменить 2 на 1 */


            $mysqli2 = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
            $query = "UPDATE users SET token='$token' WHERE id='$id';";
            if (mysqli_connect_errno()) {
                $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
                exit;
            }

            if ($mysqli2->query($query)) {
                setcookie("token", $token, time() + 604800);
                $isAuth = true;
            } else {
                $msg = "Ошибка записи в БД";
            }
            $mysqli2->close();
        } else {
            $msg = "Пароль не верен";
        }

        $result->close();
    }
    $mysqli->close();
}/* Конец обработки */


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

<?php // include('./_menu.php'); ?>


<?php if (!$isAuth): ?>


        <!-- форма регистрации templates/login.html -->

        <form class="container__signIn" action="login.php" method="post">
            <h3 class="header__signIn">
                Вход
            </h3>
            <label for="loginField">логин или email</label>
            <input type="text" id="loginField" name="login" required>
            <label for="Password">пароль</label>
            <input type="password" id="Password" name="pass" required>
            <a href="restore.php" class="link">Забыли пароль?</a>
            <div class="container__button--primary">
                <button type="submit" class="button--primary">Войти</button>
                <a href="signup.php" class="link">Регистрация</a>
            </div>
        </form>


        <!-- // -->

<?php else: ?>
<h2> "Вы успешно вошли в систему"; </h2>

<?php endif ?>


</body>

</html>