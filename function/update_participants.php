<?php 
require '../database/db_connection.php';

// Получаем данные из тела запроса
$data = json_decode(file_get_contents('php://input'), true);
$projectId = $data['projectId'];
$participantIds = $data['participantIds'];

// Удаляем существующих участников для проекта
$sqlDelete = "DELETE FROM ProjectMetalCadParticipant WHERE ProjectMetalCadId = $projectId";
if ($conn->query($sqlDelete) === TRUE) {
    // Вставляем новых участников для проекта
    $participantIdArray = explode(',', $participantIds);
    foreach ($participantIdArray as $userId) {
        $sqlInsert = "INSERT INTO ProjectMetalCadParticipant (ProjectMetalCadId, userId) VALUES ($projectId, $userId)";
        $conn->query($sqlInsert);
    }
    echo "Participants updated successfully";
} else {
    echo "Error updating participants: " . $conn->error;
}

$conn->close();
?>
