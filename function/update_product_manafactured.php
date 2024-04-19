<?php
// Подключение к базе данных
include '../database/db_connection.php';

// Получение данных из POST запроса
$product_ids = $_POST['product_id'];
$quantities = $_POST['quantity'];

// Предполагая, что вы уже защитили входные данные от SQL инъекций

// Обрабатываем каждый элемент массива
foreach ($product_ids as $key => $product_id) {
    $quantity = $quantities[$key];
    // Получение текущего значения ProductMetalCadManufactured
    $sql = "SELECT ProductMetalCadManufactured, ProductMetalCadQuantity FROM ProductMetalCad WHERE ProductMetalCadId = $product_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $manufactured = $row['ProductMetalCadManufactured'];
        $quantity_available = $row['ProductMetalCadQuantity'];
        // Вычисление нового значения для ProductMetalCadManufactured
        $new_manufactured = $manufactured + $quantity;
        if ($new_manufactured > $quantity_available) {
            // Если новое значение больше доступного количества, устанавливаем его равным доступному количеству
            $new_manufactured = $quantity_available;
        }
        // Обновление значений в базе данных
        $sql_update = "UPDATE ProductMetalCad SET ProductMetalCadManufactured = $new_manufactured WHERE ProductMetalCadId = $product_id";
        $conn->query($sql_update);

        // Расчет значения ProductMetalCadRemains
        $sql_remain = "UPDATE ProductMetalCad SET ProductMetalCadRemains = ProductMetalCadQuantity - ProductMetalCadManufactured WHERE ProductMetalCadId = $product_id";
        $conn->query($sql_remain);
    }
}

// Закрытие соединения с базой данных
$conn->close();
?>
