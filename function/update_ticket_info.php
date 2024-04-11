<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение данных из POST запроса
$data = json_decode(file_get_contents("php://input"));

// Проверка наличия необходимых данных в запросе
if (isset($data->ticketId) && isset($data->field) && isset($data->value)) {
    $ticketId = $data->ticketId;
    $field = $data->field;
    $value = $data->value;

    // Подготовка запроса на обновление данных в базе данных
    $sql = "UPDATE TicketMetalCad SET $field = '$value' WHERE TicketMetalCadId = $ticketId";

    // Выполнение запроса на обновление данных
    if ($conn->query($sql) === TRUE) {
        // Отправка успешного ответа
        echo json_encode(array("message" => "Ticket information updated successfully"));
    } else {
        // Отправка сообщения об ошибке
        echo json_encode(array("error" => "Error updating ticket information: " . $conn->error));
    }

    // Закрытие соединения с базой данных
    $conn->close();
} else {
    // Отправка сообщения об ошибке, если необходимые данные отсутствуют
    echo json_encode(array("error" => "Missing data in request"));
}
?>
