<?php
include '../database/db_connection.php';

// Получаем параметры из запроса
$productId = $_GET['productId'];
$newQuantity = $_GET['newQuantity'];

// Готовим и выполняем SQL запрос для обновления значения
$sql = "UPDATE ProductMetalCad SET ProductMetalCadQuantity = '$newQuantity' WHERE ProductMetalCadId = $productId";

if ($conn->query($sql) === TRUE) {
    // Обновляем сумму ProductMetalCadQuantity для данного TicketMetalCadId
    $ticketIdQuery = "SELECT TicketMetalCadId FROM ProductMetalCad WHERE ProductMetalCadId = $productId LIMIT 1";
    $ticketIdResult = $conn->query($ticketIdQuery);
    
    if ($ticketIdResult->num_rows > 0) {
        $row = $ticketIdResult->fetch_assoc();
        $ticketId = $row['TicketMetalCadId'];
        
        // Вычисляем сумму ProductMetalCadQuantity для данного TicketMetalCadId
        $sumQuery = "SELECT SUM(ProductMetalCadQuantity) AS totalQuantity FROM ProductMetalCad WHERE TicketMetalCadId = $ticketId";
        $sumResult = $conn->query($sumQuery);
        
        if ($sumResult->num_rows > 0) {
            $sumRow = $sumResult->fetch_assoc();
            $totalQuantity = $sumRow['totalQuantity'];
            
            // Обновляем значение в таблице TicketMetalCad
            $updateTicketQuery = "UPDATE TicketMetalCad SET TicketMetalCadQuantityProduct = $totalQuantity WHERE TicketMetalCadId = $ticketId";
            if ($conn->query($updateTicketQuery) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating ticket record: " . $conn->error;
            }
        }
    }
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
