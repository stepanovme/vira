<?php 
require '../database/db_connection.php';

// Получаем ID проекта из GET-запроса
$projectId = $_GET['projectId'];

$sql = "SELECT prmcc.ProjectMetalCadId, prmcc.userId, cc.name, cc.surname 
        FROM ProjectMetalCadParticipant AS prmcc
        JOIN user AS cc 
        WHERE cc.userId = prmcc.userId 
        AND prmcc.ProjectMetalCadId = $projectId";
$result = $conn->query($sql);

// Проверка наличия результатов
if ($result->num_rows > 0) {
    $selectedParticipants = array();
    while ($row = $result->fetch_assoc()) {
        $selectedParticipants[] = array(
            'name' => $row["name"],
            'surname' => $row["surname"],
            'userId' => $row["userId"]
        );
    }
    echo json_encode($selectedParticipants);
} else {
    echo json_encode(array());
}

$conn->close();
?>
