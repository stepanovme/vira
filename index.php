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
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Дашборд</title>
</head>
<body>
    
    <div class="wrapper">
        <div class="navbar">
            <div class="logo" onclick="window.location.href = 'index.php'">VIRA</div>
            <img src="/assets/images/mobile_logo.png" alt="" class="logo_mobile">
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
                <img class="mobile-avatar" src="/assets/images/small_logo.svg" alt="">
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

                <?php
                    $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                    FROM TicketMetalCad AS tmc
                    JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                    JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                    JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                    WHERE tmc.TicketMetalCadStatusId = 2";

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo '<p class="metal-binding-title-agreement-manage">Заявки требующие согласования</p>';
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
                ?>
                    
                

                <?php
                    $sql = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                    FROM TicketMetalCad AS tmc
                    JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                    JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                    JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                    WHERE tmc.TicketMetalCadStatusId = 3";

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo '<p class="metal-binding-title-workshop">Новые заявки цеха</p>';
                        echo '<div class="slide-list">';
                        while($row = $result->fetch_assoc()){

                            echo '
                                <div class="slide send-workshop" data-ticket-id="'.$row['TicketMetalCadId'].'">
                                    <div class="title">'.$row['TicketMetalCadName'].$row['TicketMetalCadNum'].'</div>
                                    <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                    <div class="color">'.$row['ColorName'].' '.$row['ThicknessValue'].'мм</div>
                                    <div class="status">Новая</div>
                                </div>
                            ';
                        }
                        echo '</div>';
                    }
                ?>

                    <!-- <p class="metal-binding-title-agreement">Заявки на согласования</p> -->
            </div>
        </div>
    </div>


    <script src="./js/mobile.js"></script>
    <script src="./js/dashboard.js"></script>
</body>
</html>