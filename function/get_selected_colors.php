<?php
require '../database/db_connection.php';

$projectId = $_GET['projectId']; // Предположим, что id проекта передается через GET параметр

$sql = "SELECT prmcc.ProjectMetalCadId, prmcc.ColorId, cc.ColorName 
        FROM ProjectMetalCadColor AS prmcc
        JOIN ColorCad AS cc ON cc.ColorId = prmcc.ColorId
        WHERE prmcc.ProjectMetalCadId = $projectId";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $selectedColors = array();
    while ($row = $result->fetch_assoc()) {
        $selectedColors[] = array("id" => $row["ColorId"], "name" => $row["ColorName"]);
    }
    echo json_encode($selectedColors);
} else {
    echo json_encode(array());
}

$conn->close();
?>
