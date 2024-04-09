<?php
// Подключение к базе данных
require '../database/db_connection.php';

// Проверка наличия данных в запросе
if(isset($_POST['userId']) && isset($_POST['roleId'])) {
    // Получение данных из запроса
    $userId = $_POST['userId'];
    $roleId = $_POST['roleId'];

    // Подготовка SQL-запроса для обновления роли пользователя
    $sql = "UPDATE user SET roleId = ? WHERE userId = ?";
    
    // Подготовка и выполнение подготовленного запроса
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $roleId, $userId);
    if ($stmt->execute()) {
        // Возвращаем успешный HTTP-статус
        http_response_code(200);
        echo "Роль пользователя успешно обновлена";
    } else {
        // Возвращаем HTTP-статус с ошибкой
        http_response_code(500);
        echo "Произошла ошибка при обновлении роли пользователя";
    }

    // Закрытие подготовленного запроса и соединения с базой данных
    $stmt->close();
    $conn->close();
} else {
    // Если данные не были переданы, возвращаем HTTP-статус с ошибкой
    http_response_code(400);
    echo "Недостаточно данных для выполнения запроса";
}
?>
