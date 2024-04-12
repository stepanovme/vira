<?php
include '../database/db_connection.php';

$productId = $_POST['productId'];

$sql = "SELECT * FROM PlanMetalCad WHERE ProductMetalCadId = '$productId'";
$result = $conn->query($sql);

$lines = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $lines[] = array(
            'startX' => $row['StartX'],
            'startY' => $row['StartY'],
            'endX' => $row['EndX'],
            'endY' => $row['EndY']
        );
    }
}

echo json_encode($lines);
$conn->close();
?>