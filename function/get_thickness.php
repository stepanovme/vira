<?php 
require '../database/db_connection.php';

$sql = "SELECT ThicknessValue FROM ThicknessMetalCad";
$result = $conn->query($sql);

// Проверка наличия результатов
if ($result->num_rows > 0) {
    $thickness = array();
    while ($row = $result->fetch_assoc()) {
        $thickness[] = $row["ThicknessValue"];
    }
    echo json_encode($thickness);
} else {
    echo json_encode(array());
}

$conn->close();
?>