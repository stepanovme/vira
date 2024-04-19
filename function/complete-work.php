<?php

include '../database/db_connection.php';

// Получение идентификатора заявки из GET-параметра
$ticketId = $_GET['ticketId'];

// SQL запрос для удаления строки из таблицы TicketMetalCad по идентификатору заявки
$sql = "UPDATE TicketMetalCad SET TicketMetalCadStatusId = 8 WHERE TicketMetalCadId = $ticketId";

// Выполнение запроса
if ($conn->query($sql) === TRUE) {
    echo "Заявка завершена";
} else {
    echo "Ошибка при завершение заявки: " . $conn->error;
}

// Закрытие подключения
$conn->close();
?>
