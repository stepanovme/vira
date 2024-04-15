<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение данных о ProductMetalCadId из запроса
$productId = $_GET['productId'];

// SQL запрос для расчета суммы Number по заданному ProductMetalCadId
$sql = "SELECT SUM(Number) AS TotalNumber FROM PlanMetalCad WHERE ProductMetalCadId = '$productId'";

// Выполнение SQL запроса
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Если есть результаты, извлекаем данные из результата запроса
    $row = $result->fetch_assoc();
    $totalNumber = $row['TotalNumber'];

    // Возвращаем сумму Number на клиент
    echo $totalNumber;
} else {
    // Если результатов нет, возвращаем сообщение об ошибке
    echo "Error: No data found";
}

// Закрываем соединение с базой данных
$conn->close();
?>
