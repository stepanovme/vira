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

if (isset($_GET['ticketId'])) {
    $ticketId = $_GET['ticketId'];
} else {
    echo "Ошибка: Не удалось получить идентификатор проекта из URL.";
}

$sql = "SELECT ProjectId FROM TicketMetalCad WHERE TicketMetalCadId = $ticketId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $projectId = $row['ProjectId'];
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
    <link rel="shortcut icon" href="/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/metal-cad-ticket.css">
    <title>Заявка</title>
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
                        <button class="back" onclick="window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'"></button>
                        <?php 
                        $sql = "SELECT TicketMetalCadName, TicketMetalCadNum from TicketMetalCad where TicketMetalCadId = $ticketId";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo '<h1>'.$row['TicketMetalCadName'].' '.$row['TicketMetalCadNum'].'</h1>';
                        }
                        ?>
                    </div>
                    <?php 
                        $sql = "SELECT TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            if($row['TicketMetalCadStatusId'] == 1){
                                echo '<div class="status-project new">Новая</div>';
                            } elseif($row['TicketMetalCadStatusId'] == 2){
                                echo '<div class="status-project agreement">Cогласование</div>';
                            }
                        }
                    ?>
                </div>
                <div class="nav-ticket">
                    <button class="active">Заявка</button>
                    <button>Параметры расчёта</button>
                    <button>Расчёт</button>
                </div>
                <div class="ticket-info">
                    <div class="column">
                        <div class="line">
                            <label for="">Объект:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadObject from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo '<input type="text" readonly value="'.$row['TicketMetalCadObject'].'">';
                                }
                            ?>
                        </div>
                        <div class="line">
                            <label for="">Цвет:</label>
                            <div class="custom-select">
                                <input type="text" id="colorTicketInput" readonly placeholder="Выберите цвет">
                                <div class="select-options color-select-options">
                                    <ul>
                                        <?php 

                                            $sql = "SELECT pc.ColorId, cc.ColorName AS ProjectColorName, ccc.ColorName AS ColorTicketName
                                                    FROM ProjectMetalCadColor pc
                                                    JOIN ColorCad cc ON pc.ColorId = cc.ColorId
                                                    LEFT JOIN TicketMetalCad t ON pc.ProjectMetalCadId = t.ProjectId
                                                    LEFT JOIN ColorCad ccc ON t.TicketMetalCadColor = ccc.ColorId
                                                    WHERE t.TicketMetalCadId = $ticketId";

                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    // Устанавливаем значение названия цвета напрямую в атрибут value
                                                    echo '<li data-color-id="'.$row['ColorId'].'">'.$row['ProjectColorName'].'</li>';
                                                    echo '<script>document.getElementById("colorTicketInput").value = "'.$row['ColorTicketName'].'";</script>';
                                                }
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="line">
                            <label for="">Толщина:</label>
                            <div class="custom-select">
                                <input type="text" id="thicknessTicketInput" readonly placeholder="Выберите толщину">
                                <div class="select-options thickness-select-options">
                                    <ul>
                                        <?php 
                                            $sql = "SELECT pc.ThicknessId, cc.ThicknessValue AS ProjectThicknessValue, ccc.ThicknessValue AS ThicknessValueName
                                                    FROM ProjectMetalCadThickness pc
                                                    JOIN ThicknessMetalCad cc ON pc.ThicknessId = cc.ThicknessId
                                                    LEFT JOIN TicketMetalCad t ON pc.ProjectMetalCadId = t.ProjectId
                                                    LEFT JOIN ThicknessMetalCad ccc ON t.TicketMetalCadThickness = ccc.ThicknessId
                                                    WHERE t.TicketMetalCadId = $ticketId";

                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    // Устанавливаем значение названия цвета напрямую в атрибут value
                                                    echo '<li data-thickness-id="'.$row['ThicknessId'].'">'.$row['ProjectThicknessValue'].'</li>';
                                                    echo '<script>document.getElementById("thicknessTicketInput").value = "'.$row['ThicknessValueName'].'";</script>';
                                                }
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="line">
                            <label for="">Участок:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadPlace from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo '<input type="text" name="place" id="place" value="'.$row['TicketMetalCadPlace'].'" onchange="updatePlaceTicket(this.value)" onkeypress="handleKeyPress(event)">';
                                }
                            ?>
                        </div>
                        <div class="line">
                            <label for="">Бригада:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadBrigade from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo '<input type="text" name="brigade" id="brigade" value="'.$row['TicketMetalCadBrigade'].'" onchange="updateBrigade(this.value)" onkeypress="handleKeyPress(event)">';
                                }
                            ?>
                        </div>
                        <div class="line">
                        <label for="">Адрес доставки:</label>
                        <?php 
                            $sql = "SELECT TicketMetalCadAdress from TicketMetalCad where TicketMetalCadId = $ticketId";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo '<input type="text" name="address" id="address" value="'.$row['TicketMetalCadAdress'].'" onchange="updateAddress(this.value)" onkeypress="handleKeyPress(event)">';
                            }
                        ?>
                    </div>
                    </div>
                    <div class="column">
                        <div class="line">
                            <label for="">Дата план:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadDatePlan from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    // Проверяем, является ли значение NULL
                                    if ($row['TicketMetalCadDatePlan'] !== null) {
                                        // Если не NULL, то конвертируем значение времени в формат даты
                                        $date = date('Y-m-d', strtotime($row['TicketMetalCadDatePlan']));
                                        echo '<input type="date" id="datePlan" value="'.$date.'" onchange="updateDatePlan(this.value)">';
                                    } else {
                                        // Если NULL, выводим просто пустое поле
                                        echo '<input type="date" id="datePlan" value="" onchange="updateDatePlan(this.value)">';
                                    }
                                }
                            ?>
                        </div>
                        <div class="line">
                            <label for="">Кол-во изделий:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadQuantityProduct from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo '<input type="text" id="ticketQuantityInput" value="'.$row['TicketMetalCadQuantityProduct'].'" readonly>';
                                }
                            ?>
                        </div>
                        <div class="line">
                            <label for="">Метров погонных:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadQuantityMetr from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo '<input type="text" id="ticketMetrInput" value="'.$row['TicketMetalCadQuantityMetr'].'" readonly>';
                                }
                            ?>
                        </div> 
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="./js/mobile.js"></script>
    <script src="/js/metal-cad-ticket.js"></script>
</body>
</html>