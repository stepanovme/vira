<?php
include '../database/db_connection.php';

// Получаем параметр ticketId из запроса
$ticketId = $_GET['ticketId'];

// Вычисляем сумму для каждой строки и суммируем их
$sumQuery = "SELECT SUM(ProductMetalCadLength * 0.001 * ProductMetalCadQuantity) AS sumMetr FROM ProductMetalCad WHERE TicketMetalCadId = $ticketId";
$sumResult = $conn->query($sumQuery);

if ($sumResult->num_rows > 0) {
    $sumRow = $sumResult->fetch_assoc();
    $sumProductMetr = $sumRow['sumMetr'];

    // Обновляем значение в таблице TicketMetalCad
    $updateTicketQuery = "UPDATE TicketMetalCad SET TicketMetalCadQuantityMetr = " . number_format($sumProductMetr, 2, '.', '') . " WHERE TicketMetalCadId = $ticketId";
    if ($conn->query($updateTicketQuery) === TRUE) {
        // Возвращаем новое значение суммы метража
        echo number_format($sumProductMetr, 2, '.', '');
    } else {
        echo "Error updating ticket record: " . $conn->error;
    }
} else {
    echo "Error calculating sum of product metr";
}

$conn->close();
?>
