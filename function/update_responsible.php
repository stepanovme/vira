<?php 
require '../database/db_connection.php';

// Получаем данные из тела запроса
$data = json_decode(file_get_contents('php://input'), true);
$projectId = $data['projectId'];
$responsibleIds = $data['responsibleIds'];

// Удаляем существующих ответственных для проекта
$sqlDelete = "DELETE FROM ProjectMetalCadResponsible WHERE ProjectMetalCadId = $projectId";
if ($conn->query($sqlDelete) === TRUE) {
    // Вставляем новых ответственных для проекта
    $responsibleIdArray = explode(',', $responsibleIds);
    foreach ($responsibleIdArray as $userId) {
        $sqlInsert = "INSERT INTO ProjectMetalCadResponsible (ProjectMetalCadId, userId) VALUES ($projectId, $userId)";
        $conn->query($sqlInsert);
    }
    echo "Responsibles updated successfully";
} else {
    echo "Error updating responsibles: " . $conn->error;
}

$conn->close();
?>
