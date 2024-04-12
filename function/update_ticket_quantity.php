<?php
// Подключение к базе данных
include '../database/db_connection.php';

$ticketId = $_GET['ticketId'];

// Рассчитать сумму количества продуктов
$sql = "SELECT SUM(ProductMetalCadQuantity) AS totalQuantity FROM ProductMetalCad WHERE TicketMetalCadId = $ticketId";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $totalQuantity = $row['totalQuantity'];

    if ($totalQuantity !== null) {
        // Обновить сумму в таблице TicketMetalCad
        $updateSql = "UPDATE TicketMetalCad SET TicketMetalCadQuantityProduct = $totalQuantity WHERE TicketMetalCadId = $ticketId";
        if ($conn->query($updateSql) === TRUE) {
            // Вернуть фактическое значение суммы
            echo $totalQuantity;
        } else {
            echo "Error updating quantity: " . $conn->error;
        }
    } else {
        echo "No products found";
    }
} else {
    echo "Query error: " . $conn->error;
}

// Закрыть соединение с базой данных
$conn->close();
?>
