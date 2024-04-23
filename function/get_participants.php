<?php 
require '../database/db_connection.php';

$sql = "SELECT name, surname, userId
        FROM user
        WHERE roleId = 3 OR roleId = 4 OR roleId = 5";
$result = $conn->query($sql);

// Проверка наличия результатов
if ($result->num_rows > 0) {
    $participants = array();
    while ($row = $result->fetch_assoc()) {
        $participants[] = array(
            'name' => $row["name"],
            'surname' => $row["surname"],
            'userId' => $row["userId"]
        );
    }
    echo json_encode($participants);
} else {
    echo json_encode(array());
}

$conn->close();
?>
