<?php
// get_project_thicknesses.php

// Подключение к базе данных
require '../database/db_connection.php';

// Получение ID проекта из параметров запроса
$projectId = $_GET['projectId'];

// SQL-запрос для выборки толщин проекта
$sql = "SELECT cc.ThicknessId, cc.ThicknessValue
        FROM ProjectMetalCadThickness AS prmct
        JOIN ThicknessMetalCad AS cc ON cc.ThicknessId = prmct.ThicknessId
        WHERE prmct.ProjectMetalCadId = $projectId";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $projectThicknesses = array();
    while ($row = $result->fetch_assoc()) {
        $projectThicknesses[] = array(
            'id' => $row["ThicknessId"],
            'value' => $row["ThicknessValue"]
        );
    }
    echo json_encode($projectThicknesses);
} else {
    echo json_encode(array());
}

$conn->close();
?>
