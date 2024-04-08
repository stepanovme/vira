<?php 
require '../database/db_connection.php';

// Обработка регистрации
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "INSERT INTO user (name, surname, login, password) VALUES ('$name', '$surname', '$login', '$password')";

    if ($conn->query($sql) === TRUE) {

    } else {
        // Ошибка при регистрации
        echo "Ошибка при регистрации: " . $conn->error;
    }
}

$conn->close();
?>