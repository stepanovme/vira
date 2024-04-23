<?php 
require '../database/db_connection.php';

// Получаем ID проекта из GET-запроса
$projectId = $_GET['projectId'];

$sql = "SELECT prmcc.ProjectMetalCadId, prmcc.userId, cc.name, cc.surname 
        FROM ProjectMetalCadResponsible AS prmcc
        JOIN user AS cc 
        WHERE cc.userId = prmcc.userId 
        AND prmcc.ProjectMetalCadId = $projectId";
$result = $conn->query($sql);

// Проверка наличия результатов
if ($result->num_rows > 0) {
    $selectedResponsibles = array();
    while ($row = $result->fetch_assoc()) {
        $selectedResponsibles[] = array(
            'name' => $row["name"],
            'surname' => $row["surname"],
            'userId' => $row["userId"]
        );
    }
    echo json_encode($selectedResponsibles);
} else {
    echo json_encode(array());
}

$conn->close();
?>
