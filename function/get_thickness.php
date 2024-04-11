<?php 
require '../database/db_connection.php';

$sql = "SELECT ThicknessValue, ThicknessId FROM ThicknessMetalCad";
$result = $conn->query($sql);

// Проверка наличия результатов
if ($result->num_rows > 0) {
    $thickness = array();
    while ($row = $result->fetch_assoc()) {
        $thickness[] = array(
            'value' => $row["ThicknessValue"],
            'id' => $row["ThicknessId"]
        );
    }
    echo json_encode($thickness);
} else {
    echo json_encode(array());
}

$conn->close();
?>