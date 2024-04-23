<?php
// delete_project_thicknesses.php

// Подключение к базе данных
require '../database/db_connection.php';

// Получение ID проекта из параметров запроса
$projectId = $_GET['projectId'];

// SQL-запрос для удаления толщин проекта
$sql = "DELETE FROM ProjectMetalCadThickness WHERE ProjectMetalCadId = $projectId";

if ($conn->query($sql) === TRUE) {
    echo "Thicknesses deleted successfully";
} else {
    echo "Error deleting thicknesses: " . $conn->error;
}

$conn->close();
?>
