<?php

include '../database/db_connection.php';

// Получение идентификатора заявки из GET-параметра
$ticketId = $_GET['ticketId'];

// SQL запрос для удаления строки из таблицы TicketMetalCad по идентификатору заявки
$sql = "UPDATE TicketMetalCad SET TicketMetalCadStatusId = 5 WHERE TicketMetalCadId = $ticketId";

// Выполнение запроса
if ($conn->query($sql) === TRUE) {
    echo "Заявка отказана";
} else {
    echo "Ошибка при отказе заявка: " . $conn->error;
}

// Закрытие подключения
$conn->close();
?>
