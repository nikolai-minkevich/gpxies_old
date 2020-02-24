<?php
include_once "../conf.php";

$step='first';
if (isset($_POST['step'])) {
    $step=$_POST['step'];
}



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


    <!-- форма регистрации templates/restore.html -->

    <form class="container__restore" action="restore.php" method="post">
        <h3 class="header__restore">
            Восстановление пароля
        </h3>

        <?php if ($step == 'first') : ?>

            <label for="loginForRestore">введите email</label>
            <input type="text" id="loginForRestore" name="email" required>
            <input type="hidden" name="step" value="first">
            <div class="container__button--primary">
                <button type="submit" class="button--primary" disabled>получить код для восстановления пароля</button>
            </div>
            
        <?php elseif ($step == 'second') : ?>

            <div>
                <label for="Code">введите полученный код</label>
                <input type="password" id="Code" name="code" required>
                <input type="hidden" name="step" value="second">
                <div class="container__button--primary">
                    <button type="submit" class="button--primary">Ок</a>
                </div>
            </div>
            
        <?php elseif ($step == 'third') : ?>
            <div>
                <label for="newPassword">введите новый пароль дважды</label>
                <input type="password" id="newPassword1" name="newpass1" required>
                <input type="password" id="newPassword2" name="newpass2" required>
                <input type="hidden" name="step" value="third">
                <div class="container__button--primary">
                    <a href="#" class="button--primary">Ок</a>
                </div>
            </div>
        <?php endif ?>
    </form>



    <!-- // -->



</body>

</html>