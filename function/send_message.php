<?php
// Параметры для запроса к API VK
$access_token = '22143c9422143c9422143c94b0210c22c82221422143c94443ce5280729e5522aaed4b3'; // Ваш токен доступа
$group_id = 'c241'; // ID группы
$message = 'Ваше сообщение'; // Текст сообщения

// Формируем запрос к API VK
$request_params = array(
    'peer_id' => -$group_id, // Используем ID группы с отрицательным знаком
    'message' => $message,
    'access_token' => $access_token,
    'v' => '5.131' // Версия API VK
);

// Отправляем запрос к API VK методом messages.send
$response = file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params));
$response_json = json_decode($response, true);

// Проверяем результат запроса
if ($response_json && isset($response_json['response'])) {
    echo 'Сообщение успешно отправлено!';
} else {
    echo 'Ошибка отправки сообщения: ' . $response_json['error']['error_msg'];
}
?>