<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение ticketId из параметров запроса
$ticketId = $_GET['ticketId'];

// Добавление новой записи в таблицу ProductMetalCad
$insertSql = "INSERT INTO ProductMetalCad (TicketMetalCadId, ProductMetalCadQuantity) VALUES ($ticketId, 0)";
if ($conn->query($insertSql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $insertSql . "<br>" . $conn->error;
}

// Закрыть соединение с базой данных
$conn->close();
?>
