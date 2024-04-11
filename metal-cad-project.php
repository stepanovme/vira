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
                        <button onclick="window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'">З</button>
                        <button onclick="window.location.href = 'metal-cad-settings.php?projectId=<?php echo $projectId;?>'">Н</button>
                        <button class="active">А</button>
                    </div>
                    <div class="project-nav">
                        <button class="active">Заявка</button>
                        <button onclick="window.location.href = 'metal-cad-settings.php?projectId=<?php echo $projectId;?>'">Настройки</button>
                        <button onclick="window.location.href = 'metal-cad-analyt.php?projectId=<?php echo $projectId;?>'">Аналитика</button>
                    </div>
                    <div class="ticket-select">
                        <button class="slide"></button>
                        <button class="table-btn"></button>
                        <button class="add-ticket">Добавить</button>
                        <button class="mobile-add-ticket">+</button>
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
                    <div class="bar">
                        <div class="name">
                            <p>Проекта</p>
                            <p class="value">80/100 м.пог.</p>
                        </div>
                        <div class="progress-bar"></div>
                    </div>
                    <div class="bar">
                        <div class="name">
                            <p>Проекта</p>
                            <p class="value">80/100 м.пог.</p>
                        </div>
                        <div class="progress-bar"></div>
                    </div>
                    <div class="bar">
                        <div class="name">
                            <p>Проекта</p>
                            <p class="value">80/100 м.пог.</p>
                        </div>
                        <div class="progress-bar"></div>
                    </div>
                    <div class="bar">
                        <div class="name">
                            <p>Проекта</p>
                            <p class="value">80/100 м.пог.</p>
                        </div>
                        <div class="progress-bar"></div>
                    </div>
                </div>

                <div class="slide-list">
                <?php 
                        $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()){
                                if($row['TicketMetalCadStatusId'] == 1){
                                    echo '
                                        <div class="slide new">
                                            <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="color">'.$row['ColorName'].' '.$row['ThicknessValue'].'мм</div>
                                            <div class="status">Новая</div>
                                        </div>
                                    ';
                                } elseif($row['TicketMetalCadStatusId'] == 2){
                                    echo '
                                        <div class="slide agreement">
                                            <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="color">'.$row['ColorName'].' '.$row['ThicknessValue'].'мм</div>
                                            <div class="status">Согласование</div>
                                        </div>
                                    ';
                                }
                            }
                        }
                    ?>
                </div>

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
                            $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadDateCreate, tmc.TicketMetalCadQuantityMetr, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
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
                                            <tr>
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
                                            <tr>
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
</body>
</html>