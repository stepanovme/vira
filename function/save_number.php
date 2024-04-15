<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение данных о числе из запроса
$productId = $_POST['productId'];
$x = $_POST['x'];
$y = $_POST['y'];
$number = $_POST['number'];


$sql = "UPDATE PlanMetalCad SET x = '$x' WHERE ProductMetalCadId = '$productId' AND ABS((StartX + EndX) / 2) = '$x' AND ABS((StartY + EndY) / 2) = '$y'";

if ($conn->query($sql) === TRUE) {
    // echo "Number saved successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$sql = "UPDATE PlanMetalCad SET y = '$y' WHERE ProductMetalCadId = '$productId' AND ABS((StartX + EndX) / 2) = '$x' AND ABS((StartY + EndY) / 2) = '$y'";

if ($conn->query($sql) === TRUE) {
    // echo "Number saved successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// SQL запрос для вставки данных в таблицу PlanMetalCad
$sql = "UPDATE PlanMetalCad SET Number = '$number' WHERE ProductMetalCadId = '$productId' AND ABS((StartX + EndX) / 2) = '$x' AND ABS((StartY + EndY) / 2) = '$y'";

if ($conn->query($sql) === TRUE) {
    echo "Number saved successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
