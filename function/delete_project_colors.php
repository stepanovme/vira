<?php
require '../database/db_connection.php';

// Получение ID проекта из параметров запроса
$projectId = $_GET['projectId'];

// Проверка наличия ID проекта
if (isset($projectId)) {
    // SQL запрос для удаления записей о цветах для данного проекта
    $sql = "DELETE FROM ProjectMetalCadColor WHERE ProjectMetalCadId = $projectId";

    if ($conn->query($sql) === TRUE) {
        echo "Records deleted successfully";
    } else {
        echo "Error deleting records: " . $conn->error;
    }
    
} else {
    echo "Error: Missing project ID";
}

$conn->close();
?>
