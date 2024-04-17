<?php

include '../database/db_connection.php';

// Получение идентификатора заявки из GET-параметра
$ticketId = $_GET['ticketId'];

// SQL запрос для удаления строки из таблицы TicketMetalCad по идентификатору заявки
$sql = "UPDATE TicketMetalCad SET TicketMetalCadStatusId = 6 WHERE TicketMetalCadId = $ticketId";

// Выполнение запроса
if ($conn->query($sql) === TRUE) {
    echo "Заявка отправлена в работу";
} else {
    echo "Ошибка при отправление заявки: " . $conn->error;
}

// Закрытие подключения
$conn->close();
?>
