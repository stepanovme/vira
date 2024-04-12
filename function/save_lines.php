<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение данных о линии из запроса
$productId = $_POST['productId'];
$startX = $_POST['startX'];
$startY = $_POST['startY'];
$endX = $_POST['endX'];
$endY = $_POST['endY'];

// SQL запрос для вставки данных в таблицу PlanMetalCad
$sql = "INSERT INTO PlanMetalCad (ProductMetalCadId, StartX, StartY, EndX, EndY)
VALUES ('$productId', '$startX', '$startY', '$endX', '$endY')";

if ($conn->query($sql) === TRUE) {
    echo "Line saved successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>