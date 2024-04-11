<?php
include '../database/db_connection.php';

// Получаем параметры из запроса
$productId = $_GET['productId'];
$newPlace = $_GET['newPlace'];

// Готовим и выполняем SQL запрос для обновления значения
$sql = "UPDATE ProductMetalCad SET ProductMetalCadPlace = '$newPlace' WHERE ProductMetalCadId = $productId";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}


$conn->close();
?>
