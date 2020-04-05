<?php
include_once "../conf.php";

/* Проверка авторизации */
include('./_auth.php');

/* Обработка полученных из формы данных */
$n_username = $n_password = $n_email = null;
if (isset($_POST['login'])) $n_username = $_POST['login'];
if (isset($_POST['pass'])) $n_password = $_POST['pass'];
if (isset($_POST['email'])) $n_email = $_POST['email'];

$isExist = false;
$isCreated = false;

if (isset($n_username) && isset($n_password) && isset($n_email)) {
    // Проверка на уникальность юзернейма и емейла

    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);

    $n_username = $mysqli->real_escape_string($n_username);
    $n_password = $mysqli->real_escape_string($n_password);
    $n_email = $mysqli->real_escape_string($n_email);

    $query = "SELECT username FROM users WHERE username='$n_username' OR email='$n_email' ORDER BY date_reg DESC LIMIT 1";

    if (mysqli_connect_errno()) {
        $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
        exit;
    }
    if ($result = $mysqli->query($query)) {
        if ($result->num_rows > 0) {
            $isExist = true;
        }
        $result->close();
    }
    $mysqli->close();


    // Создание записи в БД
    if (!$isExist) {
        // Создание hash для пароля
        $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
        $passwordmd5 = password_hash($n_password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, passmd5, email) VALUES ('$n_username', '$passwordmd5', '$n_email')";
        if (mysqli_connect_errno()) {
            $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
            exit;
        }

        if ($mysqli->query($query)) {
            $isCreated = true;
        }
        $mysqli->close();
    }
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

    <?php // include('./_menu.php'); 
    ?>

    <?php if ($isCreated) : ?>

        <script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 3 * 1000);
        </script>

        

        <div class="message message-success message-margin-bottom message-wide">
            <p class="message-header">Вы успешно зарегистрировались.</p>
            <p>Сейчас вы будете перенаправлены на <a href="index.php">главную страницу</a></p>
        </div>

    <?php elseif ($isExist) : ?>

        <div class="message message-warning message-margin-bottom message-wide">
            <p class="message-header">Пользователь с таким логином или почтовым адресом уже зарегистрирован.</p>
            <p>Пожалуйста, введите другой логин или адрес электронной почты.</p>
        </div>

    <?php endif ?>




    <!-- форма регистрации templates/signUp.html -->



    <form class="container__signUp" action="signup.php" method="post">
        <h3 class="header__signUp">
            Регистрация
        </h3>
        <label for="loginField">логин</label>
        <input type="text" id="login" name="login" required <?php if ($n_username)  echo "value='$n_username'"; ?>>
        
        <label for="Password">пароль</label>
        <input type="password" id="password" name="pass" required <?php if ($n_password) echo "value='$n_password'"; ?> 
        onmouseover="this.type='text'" onmouseout="this.type='password'"  />


        <label for="email">email</label>
        <input type="email" id="email" name="email" required <?php if ($n_email) echo "value='$n_email'"; ?> >
        
        <label for="countries">страна</label>
        <select id="countries" name="country">
            <option value="1" selected>Россия</option>
            <option value="2">Украина</option>
            <option value="3">Белоруссия</option>
        </select>
        <div class="container__button--primary">
            <button type="submit" class="button--primary">Зарегистрироваться</button>
            <a href="login.php" class="link">Вход</a>
        </div>

    </form>


    <!-- // -->

</body>

</html>