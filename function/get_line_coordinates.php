<?php
$productId = $_GET['productId'];

// Здесь необходимо выполнить подключение к базе данных
include '../database/db_connection.php';

// Запрос координат линий из базы данных для конкретного ProductId
$sql = "SELECT startX, startY, endX, endY FROM ProductMetalCadPlan WHERE ProductId = '$productId'";
$result = $conn->query($sql);

$lineCoordinates = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $lineCoordinates[] = array(
            'startX' => $row['startX'],
            'startY' => $row['startY'],
            'endX' => $row['endX'],
            'endY' => $row['endY']
        );
    }
}

// Преобразование результатов запроса в формат JSON и отправка на клиент
echo json_encode($lineCoordinates);

$conn->close();
?>
