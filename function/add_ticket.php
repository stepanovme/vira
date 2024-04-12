<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение данных из параметров запроса
$projectId = $_GET['projectId'];
$user_id = $_GET['user_id'];

// Получение максимального значения TicketMetalCadNum из таблицы TicketMetalCad
$maxNumSql = "SELECT MAX(TicketMetalCadNum) AS maxNum FROM TicketMetalCad";
$maxNumResult = $conn->query($maxNumSql);

if ($maxNumResult->num_rows > 0) {
    $maxNumRow = $maxNumResult->fetch_assoc();
    $maxNum = $maxNumRow['maxNum'];
    $newNum = $maxNum + 1;

    // Получение projectObject из таблицы ProjectMetalCad
    $selectSql = "SELECT ProjectObject FROM ProjectMetalCad WHERE ProjectId = '$projectId'";
    $result = $conn->query($selectSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $projectObject = $row['ProjectObject'];

        // Добавление новой строки в таблицу TicketMetalCad
        $insertSql = "INSERT INTO TicketMetalCad (ProjectId, TicketMetalCadObject, TicketMetalCadApplicant, TicketMetalCadNum) VALUES ('$projectId', '$projectObject', '$user_id', '$newNum')";
        if ($conn->query($insertSql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
        }
    } else {
        echo "Project not found";
    }
} else {
    echo "Error: Unable to get max value of TicketMetalCadNum";
}

// Закрыть соединение с базой данных
$conn->close();
?>
