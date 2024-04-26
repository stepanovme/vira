<?php
// Подключаемся к VK API
require_once('../vendor/autoload.php');

// Здесь ваш токен VK
$token = 'vk1.a.tglnVsHIuEtNOstKOOrkvwsjyY3xl3ThMZQ4ACcfmO31BHZCgWCybcBtm__MUwkzfo-quJ672VmBqbHZ3ytnWP8U_aqBwLUcGJSz22rbxy_9NXbAAdHY6wT0_YVe4XJbx36bPG-m5Oi9VhYSuA7eto0t2r2UtcJJWbGBaX-gd4-DzZC1A92qp4BIVFL-8PtpFFXrTyEYKllbkXUNeh-GPw';

// Получаем ID беседы, в которую нужно отправить сообщение (можно указать статически)
$peer_id = '2000000001'; // ID беседы

// Получаем текст сообщения из GET-параметра
$message = isset($_GET['message']) ? $_GET['message'] : 'Default message'; // Если GET-параметр message существует, используем его, иначе используем стандартное сообщение

// Формируем запрос к VK API
$request_params = array(
    'peer_id' => $peer_id,
    'message' => $message,
    'random_id' => rand(0, 100000) // Генерируем случайный ID
);

// Отправляем запрос к VK API методом messages.send
$request_url = 'https://api.vk.com/method/messages.send?' . http_build_query($request_params) . '&access_token=' . $token . '&v=5.131';
$response = file_get_contents($request_url);

// Ответ от VK API
echo $response;
?>
