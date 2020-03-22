<?php
include_once "../conf.php";

/* Проверка авторизации */
include('./_auth.php');

/* Обработка полученных из формы данных */
if (isset($_POST['login'])) $username = $_POST['login'];
if (isset($_POST['pass'])) $password = $_POST['pass'];
if (isset($_POST['email'])) $email = $_POST['email'];

$isExist = false;
$isCreated = false;

if (isset($username) && isset($password) && isset($email)) {
    // Проверка на уникальность юзернейма и емейла

    $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);

    $username = $mysqli->real_escape_string($username);
    $password = $mysqli->real_escape_string($password);
    $email = $mysqli->real_escape_string($email);

    $query = "SELECT username FROM users WHERE username='$username' OR email='$email' ORDER BY date_reg DESC LIMIT 1";
        
    if (mysqli_connect_errno()) {
        $msg = "Подключение к серверу MySQL невозможно. Код ошибки: %s\n" . mysqli_connect_error();
        exit;
    }
    if ($result = $mysqli->query($query)) {
        if ( $result->num_rows > 0) {
            $isExist = true;
        }
        $result->close();
    }
    $mysqli->close();


    // Создание записи в БД
    if (!$isExist) {
        // Создание hash для пароля
        $mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldbname);
        $passwordmd5 = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, passmd5, email) VALUES ('$username', '$passwordmd5', '$email')";        
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

<?php // include('./_menu.php'); ?>

    <?php
        if ($isCreated) {
            echo "Пользователь успешно создан<br>";
        } elseif ($isExist) {
            echo "Пользователь с таким именем или адресом уже зарегистрирован<br>";
        }
    ?>


        <!-- форма регистрации templates/signUp.html -->

        <form class="container__signUp" action="signup.php" method="post">
            <h3 class="header__signUp">
                Регистрация
            </h3>
            <label for="loginField">логин</label>
            <input type="text" id="login" name="login" required>
            <label for="Password">пароль</label>
            <input type="password" id="Password" name="pass" required>
            <label for="email">email</label>
            <input type="email" id="email" name="email" required>
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