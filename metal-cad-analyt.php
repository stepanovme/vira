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

if(isset($_GET['projectId'])) {
    $projectId = $_GET['projectId'];
} else {
    echo "projectId не указан";
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
    <link rel="stylesheet" href="css/metal-cad-analyt.css">
    <title>Аналитика</title>
</head>
<body>

    <style>
        .progress-container {
            width: 100%;
            background-color: #A4E7BB;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            overflow: hidden;
        }

        .progress-bar {
            width: 0%;
            height: 30px;
            background-color: #59B077;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            text-align: center;
            line-height: 30px;
            color: white;
            transition: width 0.5s ease-in-out;
        }

        .progress-text {
            font-size: 16px;
            margin-top: 5px;
        }
    </style>    

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
                    <div class="project-nav">
                        <button onclick="window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'">Заявка</button>
                        <button onclick="window.location.href = 'metal-cad-settings.php?projectId=<?php echo $projectId;?>'">Настройки</button>
                        <button class="active">Аналитика</button>
                    </div>
                    <div class="mobile-project-nav">
                        <button onclick="window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'">З</button>
                        <button onclick="window.location.href = 'metal-cad-settings.php?projectId=<?php echo $projectId;?>'">Н</button>
                        <button class="active">А</button>
                    </div>
                </div>

                <div class="analyt">
                    <div class="information-project">

                        

                        <p class="title">Информация по проекту</p>
                        <?php
                            $sql = "SELECT u.name, u.surname FROM ProjectMetalCadResponsible pjmcr
                                    JOIN user u ON pjmcr.userId = u.userId
                                    WHERE pjmcr.ProjectMetalCadId = $projectId";


                            $result = $conn->query($sql);
                            if ($result->num_rows > 0 || $result->num_rows == 0) {

                                while($row = $result->fetch_assoc()){ 
                                    echo '<p>Руководитель проекта:<span> '.$row['name'].' '.$row['surname'].'</span></p>';
                                } 
                            
                            }
                        ?>
                        <?php
                            $sql = "SELECT ProjectDateCreated FROM ProjectMetalCad WHERE ProjectId = $projectId";

                            $result = $conn->query($sql);
                            
                            if ($result->num_rows > 0 || $result->num_rows == 0) {
                                $row = $result->fetch_assoc();
                                $projectDateCreated = date('d.m.Y', strtotime($row['ProjectDateCreated']));
                                echo '<p>Дата начала: <span>'.$projectDateCreated.'</span></p>';
                            }                            
                        ?>
                        <?php
                            $sql = "SELECT COUNT(*) AS count
                                    FROM TicketMetalCad
                                    WHERE ProjectId = $projectId;";


                            $result = $conn->query($sql);
                            if ($result->num_rows > 0 || $result->num_rows == 0) {
                                $row = $result->fetch_assoc();
                                echo '<p>Заявок произведено: <span>'.$row['count'].'</span></p>';
                            }
                        ?>
                        <?php
                            $sql = "SELECT ProjectPlan FROM ProjectMetalCad WHERE ProjectId = $projectId";


                            $result = $conn->query($sql);
                            $projectPlan = 0;
                            if ($result->num_rows > 0 || $result->num_rows == 0) {
                                $row = $result->fetch_assoc();
                                echo '<p>План по проекту:<span>'.$row['ProjectPlan'].' м<sup>2</sup></span></p>';
                                $projectPlan = $row['ProjectPlan'];
                            }
                        ?>
                        <?php
                            $sql = "SELECT ProductMetalCad.*, ColorCad.ColorName, ThicknessMetalCad.ThicknessValue
                                    FROM ProductMetalCad
                                    JOIN TicketMetalCad ON ProductMetalCad.TicketMetalCadId = TicketMetalCad.TicketMetalCadId
                                    JOIN ColorCad ON TicketMetalCad.TicketMetalCadColor = ColorCad.ColorId
                                    JOIN ThicknessMetalCad ON ThicknessMetalCad.ThicknessId = TicketMetalCad.TicketMetalCadThickness
                                    WHERE TicketMetalCad.ProjectId = $projectId";


                            $result = $conn->query($sql);
                            $factPlan = 0;
                            if ($result->num_rows > 0 || $result->num_rows == 0) {
                                while($row = $result->fetch_assoc()){
                                    $factPlan += ($row['ProductMetalCadLength'] * 0.001 * $row['ProductMetalCadSum'] * 0.001) * $row['ProductMetalCadManufactured'];
                                    // echo $row['ProductMetalCadSum'];
                                }

                                echo '<p>Фактическое по проекту:<span>'.$factPlan.' м<sup>2</sup></span></p>';
                            }
                        ?>

                        <!-- <p>План по RAL 7024: <span>100 м2</span></p>
                        <p>Фактическое по RAL 7024:<span>100 м2</span></p> -->
                    </div>

                    <div class="information-bars">
                        <p class="title-information-bars">Выроботка</p>
                        <div class="bar">
                            <div class="progress-text" id="progress-text">0 из 0</div>
                            <div class="progress-container">
                                <div class="progress-bar" id="progress-bar" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table">
                    <p class="title">Изготовленные изделия</p>
                    <table>
                        <thead>
                            <tr>
                                <th id="analyt-num">№</th>
                                <th id="analyt-name">Название</th>
                                <th id="analyt-color">Цвет</th>
                                <th id="analyt-thikness">Толщина</th>
                                <th id="analyt-quantity">Количество</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                $sql = "SELECT ProductMetalCad.*, ColorCad.ColorName, ThicknessMetalCad.ThicknessValue
                                        FROM ProductMetalCad
                                        JOIN TicketMetalCad ON ProductMetalCad.TicketMetalCadId = TicketMetalCad.TicketMetalCadId
                                        JOIN ColorCad ON TicketMetalCad.TicketMetalCadColor = ColorCad.ColorId
                                        JOIN ThicknessMetalCad ON ThicknessMetalCad.ThicknessId = TicketMetalCad.TicketMetalCadThickness
                                        WHERE TicketMetalCad.ProjectId = $projectId";


                                $result = $conn->query($sql);
                                $num = 0;
                                if ($result->num_rows > 0 || $result->num_rows == 0) {
                                    while($row = $result->fetch_assoc()){
                                        $num = $num + 1;
                                        echo '<tr>';
                                        echo '<td id="analyt-num-value">'.$num.'</td>';
                                        echo '<td id="analyt-name-value">'.$row['ProductMetalCadName'].'</td>';
                                        echo '<td id="analyt-color-value">'.$row['ColorName'].'</td>';
                                        echo '<td id="analyt-thikness-value">'.$row['ThicknessValue'].'</td>';
                                        echo '<td id="analyt-quantity-value">'.$row['ProductMetalCadManufactured'].'</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-process">
                    <p class="title">Изделия в процессе</p>
                    <table>
                        <thead>
                            <tr>
                                <th id="analyt-num">№</th>
                                <th id="analyt-name">Название</th>
                                <th id="analyt-color">Цвет</th>
                                <th id="analyt-thikness">Толщина</th>
                                <th id="analyt-quantity">Количество</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT ProductMetalCad.*, ColorCad.ColorName, ThicknessMetalCad.ThicknessValue
                                        FROM ProductMetalCad
                                        JOIN TicketMetalCad ON ProductMetalCad.TicketMetalCadId = TicketMetalCad.TicketMetalCadId
                                        JOIN ColorCad ON TicketMetalCad.TicketMetalCadColor = ColorCad.ColorId
                                        JOIN ThicknessMetalCad ON ThicknessMetalCad.ThicknessId = TicketMetalCad.TicketMetalCadThickness
                                        WHERE TicketMetalCad.ProjectId = $projectId AND ProductMetalCad.ProductMetalCadRemains != ProductMetalCad.ProductMetalCadQuantity";


                                $result = $conn->query($sql);
                                $num = 0;
                                if ($result->num_rows > 0 || $result->num_rows == 0) {
                                    while($row = $result->fetch_assoc()){
                                        $num = $num + 1;
                                        echo '<tr>';
                                        echo '<td id="analyt-num-value">'.$num.'</td>';
                                        echo '<td id="analyt-name-value">'.$row['ProductMetalCadName'].'</td>';
                                        echo '<td id="analyt-color-value">'.$row['ColorName'].'</td>';
                                        echo '<td id="analyt-thikness-value">'.$row['ThicknessValue'].'</td>';
                                        echo '<td id="analyt-quantity-value">'.$row['ProductMetalCadRemains'].'</td>';
                                        echo '</tr>';
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
    <script src="/js/metal-cad-analyt.js"></script>
    <script>
        // Функция для обновления прогресс-бара и текста
        function updateProgress(current, total) {
        var progressBar = document.getElementById("progress-bar");
        var progressText = document.getElementById("progress-text");
        var percentage = Math.floor((current / total) * 100);
        progressBar.style.width = percentage + "%";
        // progressBar.textContent = current + " из " + total + " м.пог";
        progressText.textContent = current + " из " + total + " м.пог.";
        }

        // Пример обновления прогресса
        updateProgress(<?php echo $factPlan;?>, <?php echo $projectPlan;?>); // Устанавливаем текущее значение и общее количество
</script>
</body>
</html>