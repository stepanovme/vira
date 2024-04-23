<?php
// add_project_thickness.php

// Подключение к базе данных
require '../database/db_connection.php';

// Получение данных из тела запроса
$data = json_decode(file_get_contents("php://input"));

// Извлечение ID проекта и толщины
$projectId = $data->projectId;
$thicknessId = $data->thicknessId;

// SQL-запрос для добавления толщины к проекту
$sql = "INSERT INTO ProjectMetalCadThickness (ProjectMetalCadId, ThicknessId) 
        VALUES ($projectId, $thicknessId)";

if ($conn->query($sql) === TRUE) {
    echo "Thickness added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
