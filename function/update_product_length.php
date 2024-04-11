<?php
include '../database/db_connection.php';

// Получаем параметры из запроса
$productId = $_GET['productId'];
$newLength = $_GET['newLength'];

// Готовим и выполняем SQL запрос для обновления значения
$sql = "UPDATE ProductMetalCad SET ProductMetalCadLength = '$newLength' WHERE ProductMetalCadId = $productId";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}


$conn->close();
?>
