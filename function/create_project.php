<?php
require '../database/db_connection.php';

// Получаем данные из тела запроса
$data = json_decode(file_get_contents("php://input"));

// Проверяем, получены ли данные
if (isset($data->projectName) && isset($data->projectObject)) {
    // Подготавливаем запрос на добавление проекта
    $stmt = $conn->prepare("INSERT INTO ProjectMetalCad (ProjectName, ProjectObject) VALUES (?, ?)");
    $stmt->bind_param("ss", $data->projectName, $data->projectObject);

    // Выполняем запрос
    if ($stmt->execute()) {
        // Получаем идентификатор только что созданного проекта
        $projectId = $stmt->insert_id;

        // Закрываем подготовленное выражение
        $stmt->close();
        
        // Проверяем, получены ли данные о цветах, толщинах и ответственных
        if (isset($data->colorIds)) {
            // Разбиваем переданные id цветов на массив
            $colorIds = explode(',', $data->colorIds);

            // Подготавливаем запрос на добавление цветов проекта
            $stmt = $conn->prepare("INSERT INTO ProjectMetalCadColor (ProjectMetalCadId, ColorId) VALUES (?, ?)");
            foreach ($colorIds as $colorId) {
                $stmt->bind_param("ii", $projectId, $colorId);
                $stmt->execute();
            }
            $stmt->close();
        }

        if (isset($data->thicknessIds)) {
            // Разбиваем переданные id толщин на массив
            $thicknessIds = explode(',', $data->thicknessIds);

            // Подготавливаем запрос на добавление толщин проекта
            $stmt = $conn->prepare("INSERT INTO ProjectMetalCadThickness (ProjectMetalCadId, ThicknessId) VALUES (?, ?)");
            foreach ($thicknessIds as $thicknessId) {
                $stmt->bind_param("ii", $projectId, $thicknessId);
                $stmt->execute();
            }
            $stmt->close();
        }

        if (isset($data->responsibleIds)) {
            // Разбиваем переданные id ответственных на массив
            $responsibleIds = explode(',', $data->responsibleIds);

            // Подготавливаем запрос на добавление ответственных проекта
            $stmt = $conn->prepare("INSERT INTO ProjectMetalCadResponsible (ProjectMetalCadId, userId) VALUES (?, ?)");
            foreach ($responsibleIds as $userId) {
                $stmt->bind_param("ii", $projectId, $userId);
                $stmt->execute();
            }
            $stmt->close();
        }

        // Возвращаем идентификатор проекта в JSON формате
        echo json_encode(array("projectId" => $projectId));
    } else {
        // В случае ошибки возвращаем сообщение об ошибке
        http_response_code(500);
        echo json_encode(array("message" => "Не удалось добавить проект"));
    }

    // Закрываем соединение с базой данных
    $conn->close();
} else {
    // Если данные не получены, возвращаем сообщение об ошибке
    http_response_code(400);
    echo json_encode(array("message" => "Отсутствуют необходимые данные"));
}
?>
