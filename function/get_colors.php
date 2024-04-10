<?php 
require '../database/db_connection.php';

$sql = "SELECT ColorId, ColorName FROM ColorCad";
$result = $conn->query($sql);

// Проверка наличия результатов
if ($result->num_rows > 0) {
    $colors = array();
    while ($row = $result->fetch_assoc()) {
        $colors[] = array("id" => $row["ColorId"], "name" => $row["ColorName"]);
    }
    echo json_encode($colors);
} else {
    echo json_encode(array());
}

$conn->close();
?>