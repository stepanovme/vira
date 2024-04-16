<?php

include '../database/db_connection.php';

// Получение идентификатора заявки из GET-параметра
$ticketId = $_GET['ticketId'];

// SQL запрос для удаления строки из таблицы TicketMetalCad по идентификатору заявки
$sql = "DELETE FROM TicketMetalCad WHERE TicketMetalCadId = $ticketId";

// Выполнение запроса
if ($conn->query($sql) === TRUE) {
    echo "Заявка успешно удалена";
} else {
    echo "Ошибка при удалении заявки: " . $conn->error;
}

// Закрытие подключения
$conn->close();
?>
