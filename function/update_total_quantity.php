<?php
include '../database/db_connection.php';

// Получаем параметр ticketId из запроса
$ticketId = $_GET['ticketId'];

// Вычисляем сумму ProductMetalCadQuantity для данного TicketMetalCadId
$sumQuery = "SELECT SUM(ProductMetalCadQuantity) AS totalQuantity FROM ProductMetalCad WHERE TicketMetalCadId = $ticketId";
$sumResult = $conn->query($sumQuery);

if ($sumResult->num_rows > 0) {
    $sumRow = $sumResult->fetch_assoc();
    $totalQuantity = $sumRow['totalQuantity'];

    // Обновляем значение в таблице TicketMetalCad
    $updateTicketQuery = "UPDATE TicketMetalCad SET TicketMetalCadQuantityProduct = $totalQuantity WHERE TicketMetalCadId = $ticketId";
    if ($conn->query($updateTicketQuery) === TRUE) {
        echo $totalQuantity; // Возвращаем новое значение общего количества продуктов
    } else {
        echo "Error updating ticket record: " . $conn->error;
    }
} else {
    echo "Error calculating total quantity";
}

$conn->close();
?>
