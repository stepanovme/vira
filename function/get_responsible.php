<?php 
require '../database/db_connection.php';

$sql = "SELECT name,surname,roleId 
        FROM user
        WHERE roleId = 2 or roleId = 3 or roleId = 4 or roleId = 5";
$result = $conn->query($sql);

// Проверка наличия результатов
if ($result->num_rows > 0) {
    $responsible = array();
    while ($row = $result->fetch_assoc()) {
        $responsible[] = $row["name"]." ".$row["surname"];
    }
    echo json_encode($responsible);
} else {
    echo json_encode(array());
}

$conn->close();
?>