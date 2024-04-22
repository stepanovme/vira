<?php
require '../database/db_connection.php';

// Получение данных из тела запроса
$data = json_decode(file_get_contents("php://input"));

// Проверка наличия необходимых данных
if (isset($data->projectId) && isset($data->colorId)) {
    $projectId = $data->projectId;
    $colorId = $data->colorId;

    // SQL запрос для добавления новой записи
    $sql = "INSERT INTO ProjectMetalCadColor (ProjectMetalCadId, ColorId) VALUES ($projectId, $colorId)";

    if ($conn->query($sql) === TRUE) {
        echo "New record added successfully";
    } else {
        echo "Error adding record: " . $conn->error;
    }
} else {
    echo "Error: Missing parameters";
}

$conn->close();
?>
