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
            <form action="" method="post">
                <h1>Авторизация</h1>
                <p>Логин</p>
                <input type="text" placeholder="Логин">
                <p>Пароль</p>
                <input type="password" placeholder="********">
                <a href="#" id="showRegister">Ещё нет аккаунта?</a>
                <button type="submit">Войти</button>
            </form>
        </div>

        <div class="form-register">
            <form action="" method="post">
                <h1>Регистрация</h1>
                <p>Имя</p>
                <input type="text" placeholder="Сергей">
                <p>Фамилия</p>
                <input type="text" placeholder="Логинов">
                <p>Логин</p>
                <input type="text" placeholder="Логин">
                <p>Пароль</p>
                <input type="password" placeholder="********">
                <p>Повторить пароль</p>
                <input type="password" placeholder="********">
                <a href="#" id="showAuth">Уже есть аккаунт?</a>
                <button type="submit">Войти</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>