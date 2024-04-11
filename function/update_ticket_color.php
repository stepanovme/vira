<?php
// Подключение к базе данных
require '../database/db_connection.php';

// Проверка, были ли переданы данные методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из тела запроса
    $data = json_decode(file_get_contents("php://input"));

    // Получение значений ticketId и colorId
    $ticketId = $data->ticketId;
    $colorId = $data->colorId;

    // Подготовка запроса на обновление цвета в таблице TicketMetalCad
    $sql = "UPDATE TicketMetalCad SET TicketMetalCadColor = ? WHERE TicketMetalCadId = ?";

    // Подготовка запроса
    $stmt = $conn->prepare($sql);

    // Привязка параметров
    $stmt->bind_param("ii", $colorId, $ticketId);

    // Выполнение запроса
    if ($stmt->execute()) {
        // Возвращение успешного ответа в формате JSON
        echo json_encode(array("success" => true));
    } else {
        // Возвращение ошибки в формате JSON
        echo json_encode(array("success" => false, "error" => "Ошибка при обновлении цвета: " . $conn->error));
    }

    // Закрытие подготовленного запроса
    $stmt->close();
}

// Закрытие соединения с базой данных
$conn->close();
?>
