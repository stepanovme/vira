<?php
include '../database/db_connection.php';

$productId = $_POST['productId'];

// Запрос для загрузки линий из БД
$sqlLines = "SELECT * FROM PlanMetalCad WHERE ProductMetalCadId = '$productId'";
$resultLines = $conn->query($sqlLines);

$lines = array();
if ($resultLines->num_rows > 0) {
    while($row = $resultLines->fetch_assoc()) {
        $lines[] = array(
            'startX' => $row['StartX'],
            'startY' => $row['StartY'],
            'endX' => $row['EndX'],
            'endY' => $row['EndY']
        );
    }
}

// Запрос для загрузки чисел из БД
$sqlNumbers = "SELECT * FROM PlanMetalCad WHERE ProductMetalCadId = '$productId' AND Number IS NOT NULL";
$resultNumbers = $conn->query($sqlNumbers);

$numbers = array();
if ($resultNumbers->num_rows > 0) {
    while($row = $resultNumbers->fetch_assoc()) {
        $numbers[] = array(
            'x' => ($row['StartX'] + $row['EndX']) / 2,
            'y' => ($row['StartY'] + $row['EndY']) / 2,
            'number' => $row['Number']
        );
    }
}

$response = array(
    'lines' => $lines,
    'numbers' => $numbers
);

echo json_encode($response);
$conn->close();
?>