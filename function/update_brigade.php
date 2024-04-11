<?php
include '../database/db_connection.php';

// Получаем параметры из запроса
$ticketId = $_GET['ticketId'];
$newBrigade = $_GET['newBrigade'];

// Готовим и выполняем SQL запрос для обновления значения
$sql = "UPDATE TicketMetalCad SET TicketMetalCadBrigade = '$newBrigade' WHERE TicketMetalCadId = $ticketId";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
