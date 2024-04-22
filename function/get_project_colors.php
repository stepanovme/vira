<?php 
require '../database/db_connection.php';

$projectId = $_GET['projectId'];

$sql = "SELECT prmcc.ProjectMetalCadId, prmcc.ColorId, cc.ColorName FROM ProjectMetalCadColor AS prmcc
        JOIN ColorCad AS cc ON cc.ColorId = prmcc.ColorId WHERE prmcc.ProjectMetalCadId = $projectId";
$result = $conn->query($sql);

// Проверка наличия результатов
if ($result->num_rows > 0) {
    $colors = array();
    while ($row = $result->fetch_assoc()) {
        $colors[] = array("id" => $row["ColorId"], "name" => $row["ColorName"]);
    }
    echo json_encode($colors);
} else {
    echo json_encode(array());
}

$conn->close();
?>
