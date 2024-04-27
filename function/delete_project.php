<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение данных из параметров запроса
$projectId = $_GET['projectId'];
$user_id = $_GET['user_id'];

$selectSql = "DELETE FROM ProjectMetalCad WHERE ProjectId = '$projectId'";
$result = $conn->query($selectSql);

if($conn->query($selectSql) === TRUE){
    echo "Проект успешно удалён";
} else {
    echo "Ошибка при удалении проекта: " . $conn->error;
}

// Закрыть соединение с базой данных
$conn->close();
?>
