<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получаем данные чертежа и идентификатор продукта из POST-запроса
$canvas_data = $_POST['canvas_data'];
$product_id = $_POST['product_id'];

// Подготовка SQL-запроса для сохранения данных чертежа
$sql = "INSERT INTO Drawings (product_id, drawing_data) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

// Привязываем параметры и выполняем запрос
$stmt->bind_param("is", $product_id, $canvas_data);
$stmt->execute();

// Проверяем успешность выполнения запроса
if ($stmt->affected_rows > 0) {
    echo "Drawing saved successfully";
} else {
    echo "Error saving drawing";
}

// Закрываем соединение с базой данных
$stmt->close();
$conn->close();
?>