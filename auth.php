<?php
session_start(); // Начало сессии

require 'database/db_connection.php';

// Обработка авторизации
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Код для авторизации
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE login='$login'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Пользователь найден
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];

        // Сравнение хэшированного пароля с введенным паролем
        if (password_verify($password, $hashed_password)) {
            // Успешная авторизация
            $_SESSION['user_id'] = $user['userId'];
            header('Location: index.php'); // Перенаправление на index.php
            exit;
        } else {
            // Неверный пароль
            // echo "Неверный логин или пароль";
        }
    } else {
        // Пользователь не найден
        // echo "Неверный логин или пароль";
    }
}


// Обработка регистрации
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Код для регистрации
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Проверка наличия пользователя с таким логином
    $check_sql = "SELECT * FROM user WHERE login='$login'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Пользователь с таким логином уже существует
        // echo "Пользователь с таким логином уже существует";
    } else {
        // Вставка новой записи в базу данных с хешированным паролем
        $insert_sql = "INSERT INTO user (name, surname, login, password) VALUES ('$name', '$surname', '$login', '$hashed_password')";

        if ($conn->query($insert_sql) === TRUE) {
            // Успешная регистрация
            // echo "Успешная регистрация";
        } else {
            // Ошибка при регистрации
            // echo "Ошибка при регистрации: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <title>Авторизация</title>
</head>
<body>
    <div class="content">
        <div class="background-image">
            <img src="/assets/images/auth.jpg" alt="">
        </div>
        <div class="form-auth">
            <form  method="post">
                <h1>Авторизация</h1>
                <p>Логин</p>
                <input type="text" name="login" placeholder="Логин">
                <p>Пароль</p>
                <input type="password" name="password" placeholder="********">
                <a href="#" id="showRegister">Ещё нет аккаунта?</a>
                <button type="submit">Войти</button>
            </form>
        </div>

        <div class="form-register">
            <form method="post">
                <h1>Регистрация</h1>
                <p>Имя</p>
                <input type="text" name="name" placeholder="Сергей">
                <p>Фамилия</p>
                <input type="text" name="surname" placeholder="Логинов">
                <p>Логин</p>
                <input type="text" name="login" placeholder="Логин">
                <p>Пароль</p>
                <input type="password" name="password" placeholder="********">
                <a href="#" id="showAuth">Уже есть аккаунт?</a>
                <button type="submit" name="register">Зарегистрироваться</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>