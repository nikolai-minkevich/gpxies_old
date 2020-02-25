<?php
include_once "../conf.php";

/* Проверка авторизации */
include('./_auth.php');



/* Обработка полученных из формы данных */
/*
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
}
*/
/* Конец обработки */


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


<?php if ($isAuth): ?>


        <!-- форма регистрации templates/settings.html -->

        <form class="container" action="settings.php" method="post">
            <div class="container__setting">
                <h3 class="header__setting">
                    Настройки аккаунта 
                    <?php if (isset($username)) {
                        echo "<span id='username'>$username</span>";
                    } ?>
                </h3>
                    <label for="loginField">email</label>
                    <input type="text" id="loginField" name="email" required>
                <label for="countries">страна</label>
                <select id="countries" name="country">
                    <option id="Россия" value="1">Russia</option>
                    <option id="Украина" value="2">Ukraine</option>
                    <option id="Финляндия" value="3">Finland</option>
                </select>
                
                <h4>
                    Смена пароля
                </h4>
                <label for="oldPassword">введите старый пароль </label>
                <input type="password" id="oldPassword" name="pass0">
                <label for="newPassword1">введите новый пароль дважды</label>
                <input type="password" id="newPassword1" name="pass1">
                <input type="password" id="newPassword2" name="pass2">
                
                <div class="container__button--primary">
                    <button type="submit" class="button--primary" disabled>сохранить</button>                   
                </div>
            </div>
        </form>


        <!-- // -->

<?php else: ?>

<?php include('./_notdenied.php'); ?>

<?php endif ?>


</body>

</html>