<?php 
require '../database/db_connection.php';

// Обработка авторизации
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE login='$login' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Успешная авторизация
        echo "Успешная авторизация";
    } else {
        // Неверный логин или пароль
        echo "Неверный логин или пароль";
    }
}

$conn->close();
?>