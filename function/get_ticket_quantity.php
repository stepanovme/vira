<?php
include '../database/db_connection.php';

// Получаем параметры из запроса
$ticketId = $_GET['ticketId'];

// Выполняем SQL запрос для получения количества изделий
$sql = "SELECT TicketMetalCadQuantityProduct FROM TicketMetalCad WHERE TicketMetalCadId = $ticketId";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Возвращаем количество изделий
    echo $row['TicketMetalCadQuantityProduct'];
} else {
    echo "0";
}

$conn->close();
?>