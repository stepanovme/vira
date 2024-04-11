<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение данных из POST запроса
$data = json_decode(file_get_contents("php://input"));

// Проверка наличия необходимых данных в запросе
if (isset($data->ticketId) && isset($data->thicknessId)) {
    $ticketId = $data->ticketId;
    $thicknessId = $data->thicknessId;

    // Обновление записи в базе данных
    $sql = "UPDATE TicketMetalCad SET TicketMetalCadThickness = $thicknessId WHERE TicketMetalCadId = $ticketId";

    if ($conn->query($sql) === TRUE) {
        // Отправка успешного ответа
        echo json_encode(array("message" => "Thickness updated successfully"));
    } else {
        // Отправка сообщения об ошибке
        echo json_encode(array("error" => "Error updating thickness: " . $conn->error));
    }

    // Закрытие соединения с базой данных
    $conn->close();
} else {
    // Отправка сообщения об ошибке, если необходимые данные отсутствуют
    echo json_encode(array("error" => "Missing data in request"));
}
?>
