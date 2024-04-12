<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение параметров из запроса
$productId = $_GET['productId'];
$newName = $_GET['newName'];

// SQL запрос для обновления имени продукта
$sql = "UPDATE ProductMetalCad SET ProductMetalCadName = '$newName' WHERE ProductMetalCadId = $productId";

if ($conn->query($sql) === TRUE) {
    echo "Product name updated successfully";
} else {
    echo "Error updating product name: " . $conn->error;
}

// Закрытие соединения с базой данных
$conn->close();
?>
