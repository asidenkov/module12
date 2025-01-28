<?php
session_start();
include 'auth.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (checkPassword($login, $password)) {
        $_SESSION['user'] = $login;
        header("Location: index.php"); // После успешного входа перенаправляем на главную страницу
        exit();
    } else {
        $error = "Неверный логин или пароль!";
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Спа Салон</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Спа Салон "Релакс"</h1>
        <form action="" method="POST" class="auth-form">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Войти</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </header>
    <main>
        <p class="auth-massage" >Чтобы увидеть наши услуги и цены, войдите в личный кабинет.</p>
    </main>
</body>
</html>
