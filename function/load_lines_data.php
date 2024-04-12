<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Функция для загрузки данных о линиях из базы данных по ProductMetalCadId
function loadLinesDataFromDatabase($conn, $productId) {
    // Запрос к базе данных для загрузки данных о линиях
    $sql = "SELECT StartX, StartY, EndX, EndY FROM Lines WHERE ProductMetalCadId = $productId";
    $result = $conn->query($sql);

    $linesData = [];

    // Проверка наличия данных
    if ($result && $result->num_rows > 0) {
        // Преобразование результатов запроса в массив данных о линиях
        while ($row = $result->fetch_assoc()) {
            $linesData[] = $row;
        }
    }

    return $linesData;
}

// Получение ProductMetalCadId из запроса
$productId = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if ($productId !== null) {
    // Загрузка данных о линиях из базы данных
    $linesData = loadLinesDataFromDatabase($conn, $productId);

    // Отправка данных в формате JSON
    header('Content-Type: application/json');
    echo json_encode($linesData);
} else {
    echo "Product ID is missing.";
}

// Закрытие соединения с базой данных
$conn->close();
?>
