<?php
include '../database/db_connection.php';

// Получаем параметры из запроса
$ticketId = $_GET['ticketId'];
$newPlace = $_GET['newPlace'];

// Готовим и выполняем SQL запрос для обновления значения
$sql = "UPDATE ProjectMetalCad SET ProjectObject = '$newPlace' WHERE ProjectId = $ticketId";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
