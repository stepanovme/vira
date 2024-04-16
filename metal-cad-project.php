<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$user_id = $_SESSION['user_id'];

require 'database/db_connection.php';

$sql = "SELECT * FROM user WHERE userId = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $surname = $row['surname'];
    $roleId = $row['roleId'];
}

$sql = "SELECT * FROM role WHERE roleId = $roleId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $roleName = $row['roleName'];
}

if ($roleId != 2 && $roleId != 5 && $roleId != 3 && $roleId != 4) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['projectId'])) {
    $projectId = $_GET['projectId'];
} else {
    echo "Ошибка: Не удалось получить идентификатор проекта из URL.";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/metal-cad-project.css">
    <title>Проекты по гибке</title>
</head>
<body>
    <div class="wrapper">
        <div class="navbar">
            <div class="logo" onclick="window.location.href = 'index.php'">VIRA</div>
            <img src="/assets/images/mobile_logo.png" alt="" class="logo_mobile" onclick="window.location.href = 'index.php'">
            <nav>
                <?php include 'components/nav.php';?>
            </nav>
        </div>
        <div class="layout">
            <header>
                <div class="profile">
                    <div class="avatar">
                        <img src="/assets/images/avatar.png" alt="">
                    </div>
                    <div class="info">
                        <p class="name"><?php echo $name ." ". $surname;?></p>
                        <p class="role"><?php echo $roleName;?></p>
                    </div>
                </div>
                <img class="mobile-avatar" src="/assets/images/small_logo.svg" alt="" onclick="window.location.href = 'index.php'">
                <input type="checkbox" id="toggle">
                <label for="toggle" class="toggle-btn">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </label>
            </header>
            <div class="content">
                <div class="menu" id="menu">
                    <?php include 'components/nav_mobile.php';?>
                </div>
                <div class="content-header">
                    <div class="title">
                        <button class="back" onclick="window.location.href = 'metal-cad.php'"></button>
                        <?php 
                        $sql = "SELECT ProjectName from ProjectMetalCad where ProjectId = $projectId";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo '<h1>'.$row['ProjectName'].'</h1>';
                        }
                        ?>
                    </div>
                    <?php 
                        $sql = "SELECT StatusId from ProjectMetalCad where ProjectId = $projectId";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            if($row['StatusId'] == 1){
                                echo '<div class="status-project plan">Планирование</div>';
                            } elseif($row['StatusId'] == 2){
                                echo '<div class="status-project work">В работе</div>';
                            } elseif($row['StatusId'] == 3){
                                echo '<div class="status-project sent">Отправлено</div>';
                            } elseif($row['StatusId'] == 4){
                                echo '<div class="status-project shipped">Отгружен</div>';
                            } elseif($row['StatusId'] == 5){
                                echo '<div class="status-project completed">Завершено</div>';
                            }
                        }
                    ?>
                </div>
                <div class="subtitle">
                    <div class="mobile-project-nav">
                        <button class="active">З</button>
                        <button onclick="window.location.href = 'metal-cad-settings.php?projectId=<?php echo $projectId;?>'">Н</button>
                        <button onclick="window.location.href = 'metal-cad-analyt.php?projectId=<?php echo $projectId;?>'">А</button>
                    </div>
                    <div class="project-nav">
                        <button class="active">Заявка</button>
                        <button onclick="window.location.href = 'metal-cad-settings.php?projectId=<?php echo $projectId;?>'">Настройки</button>
                        <button onclick="window.location.href = 'metal-cad-analyt.php?projectId=<?php echo $projectId;?>'">Аналитика</button>
                    </div>
                    <div class="ticket-select">
                        <button class="slide"></button>
                        <button class="table-btn"></button>
                        <button class="add-ticket" data-project-id="<?php echo $projectId;?>">Добавить</button>
                        <button class="mobile-add-ticket" data-project-id="<?php echo $projectId;?>">+</button>
                    </div>
                </div>

                <div class="information-bars">
                    <div class="bar">
                        <div class="name">
                            <p>Проекта</p>
                            <p class="value">80/100 м.пог.</p>
                        </div>
                        <div class="progress-bar"></div>
                    </div>
                </div>

                        
                    <?php 
                        $atLeastOneResult = false;

                        $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadStatusId = 1";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $atLeastOneResult = true;
                        }

                        $sqlN = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, tmc.TicketMetalCadThickness, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadThickness IS NULL AND tmc.TicketMetalCadColor IS NULL AND tmc.TicketMetalCadStatusId = 1";
                        
                        $result = $conn->query($sqlN);
                        if ($result->num_rows > 0) {
                            $atLeastOneResult = true;
                        }

                        $sqlT = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                 FROM TicketMetalCad AS tmc
                                 JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                 JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                 WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadThickness IS NULL AND tmc.TicketMetalCadStatusId = 1";

                        $result = $conn->query($sqlT);
                        if ($result->num_rows > 0) {
                            $atLeastOneResult = true;
                        }

                        $sqlH = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                 FROM TicketMetalCad AS tmc
                                 JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                 JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                 WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadColor IS NULL AND tmc.TicketMetalCadStatusId = 1";

                        $result = $conn->query($sqlH);
                        if ($result->num_rows > 0) {
                            $atLeastOneResult = true;
                        }

                        if($atLeastOneResult == true){
                            echo '<p class="title-status">Новые заявки</p>';
                        }

                        $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadStatusId = 1";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0 || $result->num_rows == 0) {

                            echo '<div class="slide-list">';
                            while($row = $result->fetch_assoc()){

                                echo '
                                    <div class="slide new" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                        <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                        <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                        <div class="color">'.$row['ColorName'].' '.$row['ThicknessValue'].'мм</div>
                                        <div class="status">Новая</div>
                                    </div>
                                ';
                            }

                            $sqlN = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, tmc.TicketMetalCadThickness, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadThickness IS NULL AND tmc.TicketMetalCadColor IS NULL AND tmc.TicketMetalCadStatusId = 1";

                            $result = $conn->query($sqlN);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    echo '
                                        <div class="slide new" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                            <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Новая</div>
                                        </div>
                                    ';
                                }
                            }

                            $sqlT = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadThickness IS NULL AND tmc.TicketMetalCadStatusId = 1";

                            $result = $conn->query($sqlT);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    echo '
                                        <div class="slide new" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                            <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="color">'.$row['ColorName'].'</div>
                                            <div class="status">Новая</div>
                                        </div>
                                    ';
                                }
                            }

                            $sqlH = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadColor IS NULL AND tmc.TicketMetalCadStatusId = 1";

                            $result = $conn->query($sqlH);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    echo '
                                        <div class="slide new" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                            <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="color">'.$row['ThicknessValue'].'мм</div>
                                            <div class="status">Новая</div>
                                        </div>
                                    ';
                                }
                            }

                            echo '</div>';
                        }

                        $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadStatusId = 4";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            echo '<p class="title-status">Заявки на дработку</p>';
                            echo '<div class="slide-list">';
                            while($row = $result->fetch_assoc()){
                                echo '
                                    <div class="slide revision" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                        <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                        <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                        <div class="color">'.$row['ColorName'].' '.$row['ThicknessValue'].'мм</div>
                                        <div class="status">На доработке</div>
                                    </div>
                                ';
                            }
                            echo '</div>';
                        }


                        $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadStatusId = 2";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            echo '<p class="title-status">Заявки на согласование</p>';
                            echo '<div class="slide-list">';
                            while($row = $result->fetch_assoc()){
                                echo '
                                    <div class="slide agreement" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                        <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                        <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                        <div class="color">'.$row['ColorName'].' '.$row['ThicknessValue'].'мм</div>
                                        <div class="status">На согласование</div>
                                    </div>
                                ';
                            }
                            echo '</div>';
                        }

                        $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadStatusId = 3";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            echo '<p class="title-status">Отправлено в цех</p>';
                            echo '<div class="slide-list">';
                            while($row = $result->fetch_assoc()){
                                echo '
                                    <div class="slide send-workshop" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                        <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                        <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                        <div class="color">'.$row['ColorName'].' '.$row['ThicknessValue'].'мм</div>
                                        <div class="status">Отправлено в цех</div>
                                    </div>
                                ';
                            }
                            echo '</div>';
                        }

                        $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId AND tmc.TicketMetalCadStatusId = 5";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            echo '<p class="title-status">Отказанные заявки</p>';
                            echo '<div class="slide-list">';
                            while($row = $result->fetch_assoc()){
                                echo '
                                    <div class="slide deny" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                        <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                        <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                        <div class="color">'.$row['ColorName'].' '.$row['ThicknessValue'].'мм</div>
                                        <div class="status">Отказано</div>
                                    </div>
                                ';
                            }
                            echo '</div>';
                        }
                    ?>

                <div class="table"> 
                    <table>
                        <thead>
                            <tr>
                                <th id="ticket-num">№</th>
                                <th id="ticket-name">Название</th>
                                <th id="ticket-date">Дата</th>
                                <th id="ticket-color">Цвет</th>
                                <th id="ticket-thikness">Толщина</th>
                                <th id="ticket-metr">пог.м.</th>
                                <th id="ticket-responsible">Ответственный</th>
                                <th id="ticket-status">Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadDateCreate, tmc.TicketMetalCadQuantityMetr, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                    FROM TicketMetalCad AS tmc
                                    JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                    JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                    JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                    WHERE tmc.ProjectId = $projectId";

                            $result = $conn->query($sql);
                            $num = 0;
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    $num = $num + 1;
                                    $dateString = $row['TicketMetalCadDateCreate'];
                                    $dateTimestamp = strtotime($dateString);
                                    $formattedDate = date('d.m.20y', $dateTimestamp);
                                    if($row['TicketMetalCadStatusId'] == 1){
                                        echo '
                                            <tr data-ticket-id="'.$row['TicketMetalCadId'].'">
                                                <td id="ticket-num-value">'.$num.'</td>
                                                <td id="ticket-name-value">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</td>
                                                <td id="ticket-date-value">'.$formattedDate.'</td>
                                                <td id="ticket-color-value">'.$row['ColorName'].'</td>
                                                <td id="ticket-thikness-value">'.$row['ThicknessValue'].'</td>
                                                <td id="ticket-metr-value">'.$row['TicketMetalCadQuantityMetr'].'</td>
                                                <td id="ticket-responsible-value">'.$row['name'].' '.$row['surname'].'</td>
                                                <td id="ticket-status-value"><div class="status new">Новая</div></td>
                                            </tr>
                                        ';
                                    } elseif($row['TicketMetalCadStatusId'] == 2){
                                        echo '
                                            <tr data-ticket-id="'.$row['TicketMetalCadId'].'">
                                                <td id="ticket-num-value">'.$num.'</td>
                                                <td id="ticket-name-value">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</td>
                                                <td id="ticket-date-value">'.$formattedDate.'</td>
                                                <td id="ticket-color-value">'.$row['ColorName'].'</td>
                                                <td id="ticket-thikness-value">'.$row['ThicknessValue'].'</td>
                                                <td id="ticket-metr-value">'.$row['TicketMetalCadQuantityMetr'].'</td>
                                                <td id="ticket-responsible-value">'.$row['name'].' '.$row['surname'].'</td>
                                                <td id="ticket-status-value"><div class="status agreement">Согласование</div></td>
                                            </tr>
                                        ';
                                    }
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="./js/mobile.js"></script>
    <script src="/js/metal-cad-project.js"></script>
    <script>
        document.querySelector('.add-ticket').addEventListener('click', function() {
        var projectId = this.getAttribute('data-project-id');
        var user_id = "<?php echo $user_id; ?>"; // Получение ProjectObject из PHP
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Обработка успешного ответа
                console.log("Новая строка TicketMetalCad добавлена успешно");
                location.reload();
                // Перезагрузка страницы или обновление интерфейса по вашему желанию
            } else if (this.readyState == 4 && this.status != 200) {
                // Обработка ошибки
                console.error("Произошла ошибка при добавлении строки TicketMetalCad");
            }
        };
        xhttp.open("GET", "function/add_ticket.php?projectId=" + projectId + "&user_id=" + user_id, true);
        xhttp.send();
    });

    </script>
</body>
</html>