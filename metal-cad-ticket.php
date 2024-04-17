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
                        <button onclick="printPage()" class="printer"></button>
                    </div>
                    <div class="title-button">
                        <?php 
                            $sql = "SELECT TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                if($row['TicketMetalCadStatusId'] == 1){
                                    echo '<div class="status-project new">Новая</div>';
                                } elseif($row['TicketMetalCadStatusId'] == 2){
                                    echo '<div class="status-project agreement">Cогласование</div>';
                                } elseif($row['TicketMetalCadStatusId'] == 3){
                                    echo '<div class="status-project send-workshop">Отправлено в цех</div>';
                                } elseif($row['TicketMetalCadStatusId'] == 4){
                                    echo '<div class="status-project revision">На доработке</div>';
                                } elseif($row['TicketMetalCadStatusId'] == 5){
                                    echo '<div class="status-project deny">Отказано</div>';
                                }
                            }
                        ?>
                    </div>
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

                                            $sql = "SELECT pc.ColorId, t.TicketMetalCadStatusId, cc.ColorName AS ProjectColorName, ccc.ColorName AS ColorTicketName
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
                                $sql = "SELECT TicketMetalCadPlace, TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                                        echo '<input type="text" name="place" id="place" value="'.$row['TicketMetalCadPlace'].'" onchange="updatePlaceTicket(this.value)" onkeypress="handleKeyPress(event)">';
                                    } elseif($roleId == 2 || $roleId == 5){
                                        echo '<input type="text" name="place" id="place" value="'.$row['TicketMetalCadPlace'].'" onchange="updatePlaceTicket(this.value)" onkeypress="handleKeyPress(event)">';
                                    } elseif(($roleId !== 2 || $roleId !== 5) && $row['TicketMetalCadStatusId'] !== 1){
                                        echo '<input type="text" name="place" id="place" value="'.$row['TicketMetalCadPlace'].'" onchange="updatePlaceTicket(this.value)" onkeypress="handleKeyPress(event)" readonly>';
                                    }
                                }
                            ?>
                        </div>
                        <div class="line">
                            <label for="">Бригада:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadBrigade, TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                                        echo '<input type="text" name="brigade" id="brigade" value="'.$row['TicketMetalCadBrigade'].'" onchange="updateBrigade(this.value)" onkeypress="handleKeyPress(event)">';
                                    } elseif($roleId == 2 || $roleId == 5){
                                        echo '<input type="text" name="brigade" id="brigade" value="'.$row['TicketMetalCadBrigade'].'" onchange="updateBrigade(this.value)" onkeypress="handleKeyPress(event)">';
                                    } elseif(($roleId !== 2 || $roleId !== 5) && $row['TicketMetalCadStatusId'] !== 1){
                                        echo '<input type="text" name="brigade" id="brigade" value="'.$row['TicketMetalCadBrigade'].'" onchange="updateBrigade(this.value)" onkeypress="handleKeyPress(event)" readonly>';
                                    }
                                }
                            ?>
                        </div>
                        <div class="line">
                        <label for="">Адрес доставки:</label>
                        <?php 
                            $sql = "SELECT TicketMetalCadAdress, TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();

                                if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                                    echo '<input type="text" name="address" id="address" value="'.$row['TicketMetalCadAdress'].'" onchange="updateAddress(this.value)" onkeypress="handleKeyPress(event)">';
                                } elseif($roleId == 2 || $roleId == 5){
                                    echo '<input type="text" name="address" id="address" value="'.$row['TicketMetalCadAdress'].'" onchange="updateAddress(this.value)" onkeypress="handleKeyPress(event)">';
                                } elseif(($roleId !== 2 || $roleId !== 5) && $row['TicketMetalCadStatusId'] !== 1){
                                    echo '<input type="text" name="address" id="address" value="'.$row['TicketMetalCadAdress'].'" onchange="updateAddress(this.value)" onkeypress="handleKeyPress(event)" readonly>';
                                }
                            }
                        ?>
                    </div>
                    </div>
                    <div class="column">
                        <div class="line">
                            <label for="">Дата план:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadDatePlan, TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();

                                    if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                                        // Проверяем, является ли значение NULL
                                        if ($row['TicketMetalCadDatePlan'] !== null) {
                                            // Если не NULL, то конвертируем значение времени в формат даты
                                            $date = date('Y-m-d', strtotime($row['TicketMetalCadDatePlan']));
                                            echo '<input type="date" id="datePlan" value="'.$date.'" onchange="updateDatePlan(this.value)">';
                                        } else {
                                            // Если NULL, выводим просто пустое поле
                                            echo '<input type="date" id="datePlan" value="" onchange="updateDatePlan(this.value)">';
                                        }
                                    } elseif($roleId == 2 || $roleId == 5){ 
                                        // Проверяем, является ли значение NULL
                                        if ($row['TicketMetalCadDatePlan'] !== null) {
                                            // Если не NULL, то конвертируем значение времени в формат даты
                                            $date = date('Y-m-d', strtotime($row['TicketMetalCadDatePlan']));
                                            echo '<input type="date" id="datePlan" value="'.$date.'" onchange="updateDatePlan(this.value)">';
                                        } else {
                                            // Если NULL, выводим просто пустое поле
                                            echo '<input type="date" id="datePlan" value="" onchange="updateDatePlan(this.value)">';
                                        }
                                    } elseif(($roleId !== 2 || $roleId !== 5) && $row['TicketMetalCadStatusId'] !== 1){ 
                                        // Проверяем, является ли значение NULL
                                        if ($row['TicketMetalCadDatePlan'] !== null) {
                                            // Если не NULL, то конвертируем значение времени в формат даты
                                            $date = date('Y-m-d', strtotime($row['TicketMetalCadDatePlan']));
                                            echo '<input type="date" id="datePlan" value="'.$date.'" onchange="updateDatePlan(this.value)" readonly>';
                                        } else {
                                            // Если NULL, выводим просто пустое поле
                                            echo '<input type="date" id="datePlan" value="" onchange="updateDatePlan(this.value)" readonly>';
                                        }
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

                <table>
                    <thead>
                        <tr>
                            <th id="product-num">Поз.</th>
                            <th id="product-name">Изделие</th>
                            <th id="product-sum">Сумма разв.</th>
                            <th id="product-length">L, м</th>
                            <th id="product-quantity">кол-во, шт</th>
                            <th id="product-place">Место</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $sql = "SELECT ProductMetalCad.*, TicketMetalCad.TicketMetalCadStatusId
                                FROM ProductMetalCad
                                INNER JOIN TicketMetalCad ON ProductMetalCad.TicketMetalCadId = TicketMetalCad.TicketMetalCadId
                                WHERE ProductMetalCad.TicketMetalCadId = $ticketId";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $num = 0;
                            while($row = $result->fetch_assoc()) {
                                if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                                    $num = $num + 1;
                                    echo '
                                        <tr>
                                            <td id="product-num-value">'.$num.'</td>
                                            <td id="product-name-value">
                                                <input type="text" data-id="'.$row['ProductMetalCadId'].'" value="'.$row['ProductMetalCadName'].'" onchange="updateProductName(this)" onkeypress="updateProductNameOnEnter(event, this)">
                                                <canvas width="1000" height="300" tabindex="0" data-id="'.$row['ProductMetalCadId'].'"></canvas>
                                            </td>
                                            <td id="product-sum-value" class="product-sum-value" data-id="'.$row['ProductMetalCadId'].'">'.$row['ProductMetalCadSum'].'</td>
                                            <td id="product-length-value" contenteditable="true" onblur="updateLength(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updateLengthOnEnter(event)">' . $row['ProductMetalCadLength'] . '</td>
                                            <td id="product-quantity-value" contenteditable="true" onblur="updateQuantity(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updateQuantityOnEnter(event)">' . $row['ProductMetalCadQuantity'] . '</td>
                                            <td id="product-place-value" contenteditable="true" onblur="updatePlace(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updatePlaceOnEnter(event)">' . $row['ProductMetalCadPlace'] . '</td>
                                        </tr>
                                    ';
                                } elseif($roleId == 2 || $roleId == 5){ 
                                    $num = $num + 1;
                                    echo '
                                        <tr>
                                            <td id="product-num-value">'.$num.'</td>
                                            <td id="product-name-value">
                                                <input type="text" data-id="'.$row['ProductMetalCadId'].'" value="'.$row['ProductMetalCadName'].'" onchange="updateProductName(this)" onkeypress="updateProductNameOnEnter(event, this)">
                                                <canvas width="1000" height="300" tabindex="0" data-id="'.$row['ProductMetalCadId'].'"></canvas>
                                            </td>
                                            <td id="product-sum-value" class="product-sum-value" data-id="'.$row['ProductMetalCadId'].'">'.$row['ProductMetalCadSum'].'</td>
                                            <td id="product-length-value" contenteditable="true" onblur="updateLength(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updateLengthOnEnter(event)">' . $row['ProductMetalCadLength'] . '</td>
                                            <td id="product-quantity-value" contenteditable="true" onblur="updateQuantity(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updateQuantityOnEnter(event)">' . $row['ProductMetalCadQuantity'] . '</td>
                                            <td id="product-place-value" contenteditable="true" onblur="updatePlace(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updatePlaceOnEnter(event)">' . $row['ProductMetalCadPlace'] . '</td>
                                        </tr>
                                    ';
                                } elseif(($roleId !== 2 || $roleId !== 5) && $row['TicketMetalCadStatusId'] !== 1){ 
                                    $num = $num + 1;
                                    echo '
                                        <tr>
                                            <td id="product-num-value">'.$num.'</td>
                                            <td id="product-name-value">
                                                <input type="text" data-id="'.$row['ProductMetalCadId'].'" value="'.$row['ProductMetalCadName'].'" onchange="updateProductName(this)" onkeypress="updateProductNameOnEnter(event, this)">
                                                <canvas width="1000" height="300" tabindex="0" data-id="'.$row['ProductMetalCadId'].'"></canvas>
                                            </td>
                                            <td id="product-sum-value" class="product-sum-value" data-id="'.$row['ProductMetalCadId'].'">'.$row['ProductMetalCadSum'].'</td>
                                            <td id="product-length-value" onblur="updateLength(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updateLengthOnEnter(event)">' . $row['ProductMetalCadLength'] . '</td>
                                            <td id="product-quantity-value" onblur="updateQuantity(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updateQuantityOnEnter(event)">' . $row['ProductMetalCadQuantity'] . '</td>
                                            <td id="product-place-value" onblur="updatePlace(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updatePlaceOnEnter(event)">' . $row['ProductMetalCadPlace'] . '</td>
                                        </tr>
                                    ';
                                }
                                
                            }
                        }
                    ?>

                    <?php 
                        $sqlBtn = "SELECT TicketMetalCadStatusId FROM TicketMetalCad WHERE TicketMetalCadId = $ticketId";
                        $result = $conn->query($sqlBtn);
                        if ($result->num_rows > 0) {
                            $num = 0;
                            while($row = $result->fetch_assoc()) {
                                if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                                    echo '
                                            <tr>
                                                <td id="product-add-button" colspan="6" ticket-id="'.$ticketId.'">+</td>
                                            </tr>
                                         ';
                                } elseif($roleId == 2 || $roleId == 5){ 
                                    echo '
                                            <tr>
                                                <td id="product-add-button" colspan="6" ticket-id="'.$ticketId.'">+</td>
                                            </tr>
                                         ';
                                } elseif(($roleId !== 2 || $roleId !== 5) && $row['TicketMetalCadStatusId'] !== 1){
                                    echo '
                                            <tr>
                                                <td id="product-add-button" style="display:none"; colspan="6" ticket-id="'.$ticketId.'">+</td>
                                            </tr>
                                         ';
                                }
                            }
                        }

                    ?>
                        
                    </tbody>
                </table>
                <div class="buttons-ticket">
                    <?php 
                    $sqlBtns = "SELECT TicketMetalCadStatusId FROM TicketMetalCad WHERE TicketMetalCadId = $ticketId";
                    $result = $conn->query($sqlBtns);

                    if ($result->num_rows > 0) { 
                        while($row = $result->fetch_assoc()){
                            if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){ 
                                if($roleId == 2 || $roleId == 5){
                                    echo '<button class="send-to-workshop" data-ticket-id="'.$ticketId.'">Отправить в цех</button>
                                          <button class="send-to-approval" data-ticket-id="'.$ticketId.'">Отправить на согласование</button>
                                          <button class="delete" data-ticket-id="'.$ticketId.'">Удалить заявку</button>        
                                         ';
                                } else{
                                    echo '
                                          <button class="send-to-approval" data-ticket-id="'.$ticketId.'">Отправить на согласование</button>
                                          <button class="delete" data-ticket-id="'.$ticketId.'">Удалить заявку</button>        
                                         ';
                                }
                            } elseif($row['TicketMetalCadStatusId'] == 2){
                                if($roleId == 2 || $roleId == 5){
                                    echo '<button class="approve" data-ticket-id="'.$ticketId.'">Утвердить</button>
                                          <button class="send-to-revision" data-ticket-id="'.$ticketId.'">Отправить на доработку</button>
                                          <button class="deny" data-ticket-id="'.$ticketId.'">Отказать</button>        
                                         ';
                                }
                            }
                        }
                    }
                    
                    ?>
                    
                </div>
            </div>
            <div id="printContent">

                <style>
                    @import url(https://myfonts.ru/myfonts?fonts=gost-common);
                    @page {
                        size: auto; /* Вы можете установить размер страницы, например, A4 или letter */
                        margin: 0; /* Убираем отступы по умолчанию */
                    }
                    *{
                        font-family: 'Inter', sans-serif;
                    }

                    .title-content{
                        /* font-family: 'GOST Common', Arial; */
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-size: 18px;
                    }

                    .title-content.agree{
                        padding-top: 40px;
                    }
                    
                    .title-content>p.title{
                        /* font-family: 'GOST Common', Arial; */
                        margin: auto;
                    }
                    table#print-table{
                        /* font-family: 'GOST Common', Arial; */
                        margin-top: 10px;
                        border: 2px solid black;
                        width: 100%;
                        border-collapse: collapse;
                    }
                    
                    table#print-table>tbody>tr>td{
                        /* font-family: 'GOST Common', Arial; */
                        border: 2px solid black;
                        padding: 5px;
                    }
                    input.print-input{
                        /* font-family: 'GOST Common', Arial; */
                        width: 100%;
                        border: 0;
                        font-size: 20px;
                    }
                    
                    canvas.print-canvas{
                        width: 100%;
                    }
                    
                    .made-up{
                        /* font-family: 'GOST Common', Arial; */
                        margin-top: 10px;
                        text-align: right;
                        font-size: 18px;
                    }
                    p.length{
                        /* font-family: 'GOST Common', Arial; */
                        position: absolute;
                        right: 0;
                        margin-right: 90px;
                        margin-top: 106px;
                        font-size: 24px;
                    }
                </style>

                <?php
                   $sqlPrint = "SELECT * FROM TicketMetalCad WHERE TicketMetalCadId = $ticketId";
                   $result = $conn->query($sqlPrint);

                   if ($result->num_rows > 0) { 
                        
                        while($row = $result->fetch_assoc()){
                            $ticketMetalCadDateCreate = $row['TicketMetalCadDateCreate'];
                            $timestamp = strtotime($ticketMetalCadDateCreate);
                            $formattedDate = date("d.m.Y", $timestamp);

                            $ticketMetalCadDatePlan = $row['TicketMetalCadDatePlan'];
                            $timestampPlan = strtotime($ticketMetalCadDatePlan);
                            $formattedDatePlan = date("d.m.Y", $timestampPlan);

                            // echo '<p class="title">Заявка на гибку металла №'.$row['TicketMetalCadNum'].'</p>';
                            echo '
                            <div class="title-content">
                                <p class="title">Заявка на гибку металла №'.$row['TicketMetalCadNum'].'</p>
                                <p class="date-create">дата: '.$formattedDate.'</p>
                            </div>
                            <table id="print-table">
                                <tr>
                                    <td>ОБЪЕКТ:</td>
                                    <td colspan=2>'.$row['TicketMetalCadObject'].'</td>
                                    <td rowspan=6> КОЛ-ВО ИЗДЕЛИЙ: '.$row['TicketMetalCadQuantityProduct'].' шт.</td>
                                </tr>
                                <tr>
                                    <td>ЦВЕТ/толщ.:</td>
                                    <td colspan=2>RAL-5005; t=0,7мм</td>
                                </tr>
                                <tr>
                                    <td>УЧАСТОК:</td>
                                    <td colspan=2>'.$row['TicketMetalCadPlace'].'</td>
                                </tr>
                                <tr>
                                    <td>БРИГАДА:</td>
                                    <td colspan=2>'.$row['TicketMetalCadBrigade'].'</td>
                                </tr>
                                <tr>
                                    <td>Дата:</td>
                                    <td>план: '.$formattedDatePlan.'</td>
                                    <td style="width: 175px;">факт: </td>
                                </tr>
                                <tr>
                                    <td colspan=3>Адрес доставки: '.$row['TicketMetalCadAdress'].'</td>
                                </tr>
                                <tr>
                                    <td colspan=4>Кол-во листов(расчет)</td>
                                </tr>
                            </table>
                            ';
                        }
                   }
                ?>

                

                <?php 
                    $sql = "SELECT ProductMetalCad.*, TicketMetalCad.TicketMetalCadStatusId
                            FROM ProductMetalCad
                            INNER JOIN TicketMetalCad ON ProductMetalCad.TicketMetalCadId = TicketMetalCad.TicketMetalCadId
                            WHERE ProductMetalCad.TicketMetalCadId = $ticketId";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $num = 0;
                        while($row = $result->fetch_assoc()) {
                            $num = $num + 1;
                            echo '
                                <p class="length">L='. $row['ProductMetalCadLength'] .'м - '.$row['ProductMetalCadQuantity'].'шт</p>
                                <input class="print-input" type="text" data-id="'.$row['ProductMetalCadId'].'" value="'.$num.' '.$row['ProductMetalCadName'].'" onchange="updateProductName(this)" onkeypress="updateProductNameOnEnter(event, this)">
                                <canvas class="print-canvas" width="1000" height="300" tabindex="0" data-id="'.$row['ProductMetalCadId'].'"></canvas>
                            ';

                            // Если выводится третья строка, то выводим необходимые элементы
                            if ($num == 3) {
                                echo '<p style="opacity: 0;">Пустое место</p>';
                                echo '
                                    <div class="title-content agree">
                                        <p class="title">Продолжение заявки №1</p>
                                        <p class="date-create">дата: 02.04.2024</p>
                                    </div>
                                ';
                            }

                            // Если выводится четвертая строка, то выводим необходимые элементы
                            if ($num % 7 == 0 && $num > 3) {
                                echo '<p style="opacity: 0;">Пустое место</p>';
                                echo '
                                    <div class="title-content agree">
                                        <p class="title">Продолжение заявки №1</p>
                                        <p class="date-create">дата: 02.04.2024</p>
                                    </div>
                                ';
                            }

                            if ($num % 11 == 0 && $num > 3) {
                                echo '<p style="opacity: 0;">Пустое место</p>';
                                echo '
                                    <div class="title-content agree">
                                        <p class="title">Продолжение заявки №1</p>
                                        <p class="date-create">дата: 02.04.2024</p>
                                    </div>
                                ';
                            }

                            if ($num % 15 == 0 && $num > 3) {
                                echo '<p style="opacity: 0;">Пустое место</p>';
                                echo '
                                    <div class="title-content agree">
                                        <p class="title">Продолжение заявки №1</p>
                                        <p class="date-create">дата: 02.04.2024</p>
                                    </div>
                                ';
                            }

                            if ($num % 19 == 0 && $num > 3) {
                                echo '<p style="opacity: 0;">Пустое место</p>';
                                echo '
                                    <div class="title-content agree">
                                        <p class="title">Продолжение заявки №1</p>
                                        <p class="date-create">дата: 02.04.2024</p>
                                    </div>
                                ';
                            }
                        }
                    }
                ?>

                    

                <script src="/js/jquery.js"></script>
                <script>
                        var canvasHistory = {};
                        var tempCanvas = document.createElement('canvas');
                        var tempContext = tempCanvas.getContext('2d');

                        var canvasList = document.getElementsByTagName('canvas');
                        for (var i = 0; i < canvasList.length; i++) {
                            var canvas = canvasList[i];
                            var context = canvas.getContext('2d');
                            drawGrid(canvas, context);
                            var canvasData = { lines: [], isDrawing: false };
                            canvasData.history = { lines: [], lastSavedIndex: -1 };
                            
                        }

                        document.addEventListener('keydown', function(e) {
                            if (e.ctrlKey && e.key === 'z') {
                                e.preventDefault();
                                cancelLastAction();
                            }
                        });

                        function drawGrid(canvas, context) {
                            var gridSize = 20;
                            context.beginPath();
                            for (var x = 0; x <= canvas.width; x += gridSize) {
                                context.moveTo(x, 0);
                                context.lineTo(x, canvas.height);
                            }
                            for (var y = 0; y <= canvas.height; y += gridSize) {
                                context.moveTo(0, y);
                                context.lineTo(canvas.width, y);
                            }
                            context.strokeStyle = 'lightgray';
                            context.lineWidth = 1;
                            context.stroke();
                        }

                        function drawGridAndLines(canvas, context, data) {
                            context.clearRect(0, 0, canvas.width, canvas.height);
                            drawGrid(canvas, context); 
                            redrawCanvas(canvas, context, data); 
                            drawTempLine(canvas, data, { clientX: 0, clientY: 0 }); // Добавляем предварительный просмотр временной линии
                        }

                        function startDrawing(canvas, data, e) {
                            data.isDrawing = true;
                            var rect = canvas.getBoundingClientRect();
                            var gridSize = 20;
                            var mouseX = e.clientX - rect.left;
                            var mouseY = e.clientY - rect.top;
                            var startX = Math.round(mouseX / gridSize) * gridSize;
                            var startY = Math.round(mouseY / gridSize) * gridSize;
                            data.lines.push({ startX: startX, startY: startY, endX: startX, endY: startY });
                            redrawCanvas(canvas, canvas.getContext('2d'), data);
                        }

                        function endDrawing(canvas, data) {
                            if (!data.isDrawing) return;
                            data.isDrawing = false;
                            tempContext.clearRect(0, 0, canvas.width, canvas.height);

                            var currentLine = data.lines[data.lines.length - 1];
                            var midX = (currentLine.startX + currentLine.endX) / 2;
                            var midY = (currentLine.startY + currentLine.endY) / 2;
                            
                            // Проверяем ориентацию линии и корректируем координаты для числа
                            var offsetX = 0;
                            var offsetY = 0;
                            if (Math.abs(currentLine.endY - currentLine.startY) < Math.abs(currentLine.endX - currentLine.startX)) {
                                // Горизонтальная или почти горизонтальная линия
                                offsetY = -20; // Поднимаем число на 20 пикселей
                            } else {
                                // Вертикальная или почти вертикальная линия
                                offsetX = -20; // Сдвигаем число влево на 20 пикселей
                            }

                            drawNumberOnLine(canvas, canvas.getContext('2d'), midX + offsetX, midY + offsetY, '0');

                            var productId = canvas.getAttribute('data-id');
                            saveNumberToDatabase(productId, midX, midY, 0);
                            
                            var history = data.history;
                            var lastSavedIndex = history.lastSavedIndex;
                            
                            var newLines = data.lines.slice(lastSavedIndex + 1); 
                                
                            history.lines.push(...newLines); 
                            history.lastSavedIndex = history.lines.length - 1; 
                            
                            saveLinesToDatabase(canvas, newLines); 
                            redrawCanvas(canvas, canvas.getContext('2d'), data);
                        }

                        function drawNumberOnLine(canvas, context, x, y, number) {
                            context.font = '20px Arial';
                            context.fillStyle = 'black';
                            context.textAlign = 'center';
                            context.fillText(number, x - 15, y - 5);
                        }


                        canvas.addEventListener('click', function(e) {
                            var rect = canvas.getBoundingClientRect();
                            var mouseX = e.clientX - rect.left;
                            var mouseY = e.clientY - rect.top;

                            var allNumbers = [...canvasHistory[canvas.id].history.numbers, ...canvasHistory[canvas.id].numbers];
                            for (var i = 0; i < allNumbers.length; i++) {
                                var numberData = allNumbers[i];
                                var dist = Math.sqrt(Math.pow(mouseX - numberData.x, 2) + Math.pow(mouseY - numberData.y, 2));
                                if (dist < 10) {
                                    var newNumber = prompt('Enter new number:', numberData.number);
                                    if (newNumber !== null) {
                                        updateNumber(numberData.productId, numberData.x, numberData.y, newNumber);
                                    }
                                    break;
                                }
                            }
                        });

                        function updateNumberInDatabase(productId, x, y, newNumber) {
                            var numberData = {
                                productId: productId,
                                x: x,
                                y: y,
                                number: newNumber
                            };
                            $.ajax({
                                url: 'function/update_number.php', // Путь к скрипту для обновления значения цифры в БД
                                method: 'POST',
                                data: numberData,
                                success: function(response) {
                                    console.log('Number updated successfully');
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error updating number:', error);
                                }
                            });
                        }


                        function saveNumberToDatabase(productId, x, y, number) {
                            var numberData = {
                                productId: productId,
                                x: x,
                                y: y,
                                number: number
                            };
                            $.ajax({
                                url: 'function/save_number.php',
                                method: 'POST',
                                data: numberData,
                                success: function(response) {
                                    console.log('Number saved successfully');
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error saving number:', error);
                                }
                            });
                        }

                        function drawTempLine(canvas, data, e) {
                            if (!data.isDrawing) return;
                            var rect = canvas.getBoundingClientRect();
                            var gridSize = 20;
                            var mouseX = e.clientX - rect.left;
                            var mouseY = e.clientY - rect.top;
                            var endX = Math.round(mouseX / gridSize) * gridSize;
                            var endY = Math.round(mouseY / gridSize) * gridSize;

                            var currentLine = data.lines[data.lines.length - 1];
                            currentLine.endX = endX;
                            currentLine.endY = endY;

                            tempContext.clearRect(0, 0, tempCanvas.width, tempCanvas.height);

                            // Рисуем основные линии на временном холсте
                            var allLines = [...data.history.lines, ...data.lines];
                            for (var i = 0; i < allLines.length; i++) {
                                var line = allLines[i];
                                tempContext.beginPath();
                                tempContext.moveTo(line.startX, line.startY);
                                tempContext.lineTo(line.endX, line.endY);
                                tempContext.strokeStyle = 'black';
                                tempContext.lineWidth = 2;
                                tempContext.stroke();
                            }

                            // Рисуем временную линию на временном холсте зеленым цветом
                            tempContext.beginPath();
                            tempContext.moveTo(currentLine.startX, currentLine.startY);
                            tempContext.lineTo(currentLine.endX, currentLine.endY);
                            tempContext.strokeStyle = '#59B077'; // Зеленый цвет для временной линии
                            tempContext.lineWidth = 2;
                            tempContext.stroke();

                            // Отображаем временный холст как задний фон основного холста
                            canvas.style.background = 'url(' + tempCanvas.toDataURL() + ')';
                        }


                        function redrawCanvas(canvas, context, data) {
                            var historyLines = data.history ? data.history.lines : [];
                            var allLines = [...historyLines, ...data.lines];

                            tempContext.clearRect(0, 0, canvas.width, canvas.height);
                            tempContext.drawImage(canvas, 0, 0);

                            for (var i = 0; i < allLines.length - 1; i++) {
                                var line = allLines[i];
                                context.beginPath();
                                context.moveTo(line.startX, line.startY);
                                context.lineTo(line.endX, line.endY);
                                context.strokeStyle = 'black';
                                context.lineWidth = 4;
                                context.stroke();
                            }

                            var currentLine = data.lines[data.lines.length - 1];
                            context.beginPath();
                            context.moveTo(currentLine.startX, currentLine.startY);
                            context.lineTo(currentLine.endX, currentLine.endY);
                            context.strokeStyle = 'black';
                            context.lineWidth = 4;
                            context.stroke();
                        }

                        function cancelLastLine(canvas) {
                            var history = canvasHistory[canvas.id].lines;
                            var lastSavedIndex = canvasHistory[canvas.id].lastSavedIndex;
                            if (!history || lastSavedIndex < 0) return;

                            history.splice(lastSavedIndex + 1);
                            canvasHistory[canvas.id].lastSavedIndex = history.length - 1;
                            redrawCanvas(canvas, context, history);
                        }

                        function cancelLastAction() {
                            var activeCanvas = document.activeElement;
                            if (!activeCanvas || !canvasHistory[activeCanvas.id]) return;

                            cancelLastLine(activeCanvas);
                        }

                        function saveLinesToDatabase(canvas, lines) {
                            var productId = canvas.getAttribute('data-id');
                            for (var i = 0; i < lines.length; i++) {
                                var line = lines[i];
                                var lineData = {
                                    productId: productId,
                                    startX: line.startX,
                                    startY: line.startY,
                                    endX: line.endX,
                                    endY: line.endY
                                };
                                $.ajax({
                                    url: 'function/save_lines.php',
                                    method: 'POST',
                                    data: lineData,
                                    success: function(response) {
                                        console.log('Line saved successfully');
                                        loadLinesAndDraw();
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error saving line:', error);
                                    }
                                });
                            }
                        }

                        function loadLinesAndDraw() {
                            var canvasList = document.getElementsByTagName('canvas');
                            for (var i = 0; i < canvasList.length; i++) {
                                var canvas = canvasList[i];
                                var productId = canvas.getAttribute('data-id');
                                $.ajax({
                                    url: 'function/get_lines_and_numbers.php',
                                    method: 'POST',
                                    data: { productId: productId },
                                    dataType: 'json',
                                    success: function(canvas, response) {
                                        return function(response) {
                                            var context = canvas.getContext('2d');
                                            drawGridAndLines(canvas, context, { lines: response.lines });
                                            drawNumbersOnCanvas(canvas, context, response.numbers);
                                            updateProductSum()
                                        };
                                    }(canvas),
                                    error: function(xhr, status, error) {
                                        console.error('Error loading lines and numbers:', error);
                                    }
                                });
                            }
                        }

                        function drawNumbersOnCanvas(canvas, context, numbers) {
                            for (var i = 0; i < numbers.length; i++) {
                                var numberData = numbers[i];
                                var offsetX = 0;
                                var offsetY = 0;

                                // Если это новая линия, определяем наклон и устанавливаем позицию цифры
                                if (numberData.hasOwnProperty('lineSlope')) {
                                    // Определяем наклон линии
                                    var lineSlope = numberData.lineSlope;

                                    // Устанавливаем позицию в зависимости от наклона линии
                                    if (lineSlope >= -0.5 && lineSlope <= 0.5) {
                                        // Горизонтальная линия, добавляем отступ вверх
                                        offsetY = -20;
                                    } else if (lineSlope >= 1.5 || lineSlope <= -1.5) {
                                        // Вертикальная линия, добавляем отступ влево
                                        offsetX = -20;
                                    }
                                }

                                // Устанавливаем позицию цифры с учетом отступа
                                drawNumberOnLine(canvas, context, numberData.x + offsetX, numberData.y + offsetY, numberData.number);
                            }
                        }

                        // Функция для определения наклона линии
                        function getLineSlope(x1, y1, x2, y2) {
                            return (y2 - y1) / (x2 - x1);
                        }

                        function updateTempLine(canvas, tempCanvas, tempContext, e) {
                            var data = canvasHistory[canvas.id];
                            if (!data.isDrawing) return;
                            var rect = canvas.getBoundingClientRect();
                            var gridSize = 20;
                            var mouseX = e.clientX - rect.left;
                            var mouseY = e.clientY - rect.top;
                            var endX = Math.round(mouseX / gridSize) * gridSize;
                            var endY = Math.round(mouseY / gridSize) * gridSize;

                            var currentLine = data.lines[data.lines.length - 1];
                            currentLine.endX = endX;
                            currentLine.endY = endY;

                            tempContext.clearRect(0, 0, tempCanvas.width, tempCanvas.height);
                            redrawCanvas(tempCanvas, tempContext, data);
                        }

                        tempCanvas.addEventListener('mousemove', function(e) {
                            updateTempLine(canvas, tempCanvas, tempContext, e);
                        });

                        tempCanvas.width = canvas.width;
                        tempCanvas.height = canvas.height;

                        window.onload = loadLinesAndDraw;

                        

                </script>

            </div>
        </div>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="./js/mobile.js"></script>
    <script src="/js/metal-cad-ticket.js"></script>
    <script>

        document.getElementById('product-add-button').addEventListener('click', function() {
            var ticketId = this.getAttribute('ticket-id');
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Обработка успешного ответа
                    // Например, обновление интерфейса или другие действия
                    location.reload();
                }
            };
            xhttp.open("GET", "function/add_product.php?ticketId=" + ticketId, true);
            xhttp.send();

            location
        });

        <?php 

            $sqlCol = "SELECT TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

            $result = $conn->query($sqlCol);
            if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
                    if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                        echo    "const colorInput = document.getElementById('colorTicketInput');
                                    const colorSelectOptions = document.querySelector('.color-select-options');
                                    const colorList = document.querySelectorAll('.color-select-options ul li');
    
                                    document.addEventListener('DOMContentLoaded', function() {
                                        colorInput.addEventListener('click', function() {
                                            colorSelectOptions.style.display = 'block';
                                        });
    
                                        colorList.forEach(colorItem => {
                                            colorItem.addEventListener('click', function() {
                                                const selectedColorId = this.dataset.colorId;
                                                const selectedColorName = this.textContent;
    
                                                // Отправить выбранный цвет в базу данных TicketMetalCadColor
                                                fetch('function/update_ticket_color.php', { 
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        ticketId:".$ticketId.",
                                                        colorId: selectedColorId
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    console.log(selectedColorId);
                                                    // Обработать успешный ответ, если необходимо
                                                    console.log('Color updated successfully');
                                                })
                                                .catch(error => {
                                                    // Обработать ошибку, если необходимо
                                                    console.error('Error updating color:', error);
                                                });
    
                                                // Установить выбранный цвет в input и закрыть список
                                                colorInput.value = selectedColorName;
                                                colorSelectOptions.style.display = 'none';
                                            });
                                        });
                                    });";
                    } elseif($roleId == 2 || $roleId == 5){
                        echo    "const colorInput = document.getElementById('colorTicketInput');
                                    const colorSelectOptions = document.querySelector('.color-select-options');
                                    const colorList = document.querySelectorAll('.color-select-options ul li');
    
                                    document.addEventListener('DOMContentLoaded', function() {
                                        colorInput.addEventListener('click', function() {
                                            colorSelectOptions.style.display = 'block';
                                        });
    
                                        colorList.forEach(colorItem => {
                                            colorItem.addEventListener('click', function() {
                                                const selectedColorId = this.dataset.colorId;
                                                const selectedColorName = this.textContent;
    
                                                // Отправить выбранный цвет в базу данных TicketMetalCadColor
                                                fetch('function/update_ticket_color.php', { 
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        ticketId:".$ticketId.",
                                                        colorId: selectedColorId
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    console.log(selectedColorId);
                                                    // Обработать успешный ответ, если необходимо
                                                    console.log('Color updated successfully');
                                                })
                                                .catch(error => {
                                                    // Обработать ошибку, если необходимо
                                                    console.error('Error updating color:', error);
                                                });
    
                                                // Установить выбранный цвет в input и закрыть список
                                                colorInput.value = selectedColorName;
                                                colorSelectOptions.style.display = 'none';
                                            });
                                        });
                                    });";
                    }
                }
        ?>

        

    <?php
        $sqlThik = "SELECT TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

        $result = $conn->query($sqlThik);
            if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
               if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                    echo "
                        const thicknessInput = document.getElementById('thicknessTicketInput');
                        const thicknessSelectOptions = document.querySelector('.thickness-select-options');
                        const thicknessList = document.querySelectorAll('.thickness-select-options ul li');
                    
                        document.addEventListener('DOMContentLoaded', function() {
                    
                            thicknessInput.addEventListener('click', function() {
                                thicknessSelectOptions.style.display = 'block';
                            });
                    
                            thicknessList.forEach(thicknessItem => {
                                thicknessItem.addEventListener('click', function() {
                                    const selectedThicknessId = this.dataset.thicknessId;
                                    const selectedThicknessValue = this.textContent;
                    
                                    // Отправить выбранную толщину в базу данных TicketMetalCadThickness
                                    fetch('function/update_ticket_thickness.php', { 
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            ticketId:".$ticketId.",
                                            thicknessId: selectedThicknessId
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        // Обработать успешный ответ, если необходимо
                                        console.log('Thickness updated successfully');
                                    })
                                    .catch(error => {
                                        // Обработать ошибку, если необходимо
                                        console.error('Error updating thickness:', error);
                                    });
                    
                                    // Установить выбранную толщину в input и закрыть список
                                    thicknessInput.value = selectedThicknessValue;
                                    thicknessSelectOptions.style.display = 'none';
                                });
                            });
                        });
                    
                    
                        document.addEventListener('click', function(event) {
                            const target = event.target;
                            const colorInput = document.getElementById('colorTicketInput');
                            const colorSelectOptions = document.querySelector('.color-select-options');
                            const thicknessInput = document.getElementById('thicknessTicketInput');
                            const thicknessSelectOptions = document.querySelector('.thickness-select-options');
                    
                            // Проверяем, кликнули ли мы вне выпадающего списка цветов
                            if (!colorSelectOptions.contains(target) && target !== colorInput) {
                                colorSelectOptions.style.display = 'none';
                            }
                    
                            // Проверяем, кликнули ли мы вне выпадающего списка толщин
                            if (!thicknessSelectOptions.contains(target) && target !== thicknessInput) {
                                thicknessSelectOptions.style.display = 'none';
                            }
                        });
                    
                        colorInput.addEventListener('click', function() {
                            // Показываем выпадающий список цветов при клике на поле ввода
                            colorSelectOptions.style.display = 'block';
                        });
                    
                        thicknessInput.addEventListener('click', function() {
                            // Показываем выпадающий список толщин при клике на поле ввода
                            thicknessSelectOptions.style.display = 'block';
                        });
                        ";
               }elseif($roleId == 2 || $roleId == 5){
                    echo "
                        const thicknessInput = document.getElementById('thicknessTicketInput');
                        const thicknessSelectOptions = document.querySelector('.thickness-select-options');
                        const thicknessList = document.querySelectorAll('.thickness-select-options ul li');
                    
                        document.addEventListener('DOMContentLoaded', function() {
                    
                            thicknessInput.addEventListener('click', function() {
                                thicknessSelectOptions.style.display = 'block';
                            });
                    
                            thicknessList.forEach(thicknessItem => {
                                thicknessItem.addEventListener('click', function() {
                                    const selectedThicknessId = this.dataset.thicknessId;
                                    const selectedThicknessValue = this.textContent;
                    
                                    // Отправить выбранную толщину в базу данных TicketMetalCadThickness
                                    fetch('function/update_ticket_thickness.php', { 
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            ticketId:".$ticketId.",
                                            thicknessId: selectedThicknessId
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        // Обработать успешный ответ, если необходимо
                                        console.log('Thickness updated successfully');
                                    })
                                    .catch(error => {
                                        // Обработать ошибку, если необходимо
                                        console.error('Error updating thickness:', error);
                                    });
                    
                                    // Установить выбранную толщину в input и закрыть список
                                    thicknessInput.value = selectedThicknessValue;
                                    thicknessSelectOptions.style.display = 'none';
                                });
                            });
                        });
                    
                    
                        document.addEventListener('click', function(event) {
                            const target = event.target;
                            const colorInput = document.getElementById('colorTicketInput');
                            const colorSelectOptions = document.querySelector('.color-select-options');
                            const thicknessInput = document.getElementById('thicknessTicketInput');
                            const thicknessSelectOptions = document.querySelector('.thickness-select-options');
                    
                            // Проверяем, кликнули ли мы вне выпадающего списка цветов
                            if (!colorSelectOptions.contains(target) && target !== colorInput) {
                                colorSelectOptions.style.display = 'none';
                            }
                    
                            // Проверяем, кликнули ли мы вне выпадающего списка толщин
                            if (!thicknessSelectOptions.contains(target) && target !== thicknessInput) {
                                thicknessSelectOptions.style.display = 'none';
                            }
                        });
                    
                        colorInput.addEventListener('click', function() {
                            // Показываем выпадающий список цветов при клике на поле ввода
                            colorSelectOptions.style.display = 'block';
                        });
                    
                        thicknessInput.addEventListener('click', function() {
                            // Показываем выпадающий список толщин при клике на поле ввода
                            thicknessSelectOptions.style.display = 'block';
                        });  
                    ";
               }
            }
    ?>

    




    function updateBrigade(newBrigade) {
        var ticketId = <?php echo $ticketId; ?>; // Получаем значение ticketId из PHP
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Можно добавить дополнительную обработку здесь, если нужно
            }
        };
        xhttp.open("GET", "function/update_brigade.php?ticketId=" + ticketId + "&newBrigade=" + newBrigade, true);
        xhttp.send();
    }

    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("brigade").blur(); // Снятие фокуса с input
        }
    }

    function updateAddress(newAddress) {
        var ticketId = <?php echo $ticketId; ?>; // Получаем значение ticketId из PHP
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Можно добавить дополнительную обработку здесь, если нужно
            }
        };
        xhttp.open("GET", "function/update_address.php?ticketId=" + ticketId + "&newAddress=" + newAddress, true);
        xhttp.send();
    }

    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("address").blur(); // Снятие фокуса с input
        }
    }


    function updatePlaceTicket(newPlace) {
        var ticketId = <?php echo $ticketId; ?>; // Получаем значение ticketId из PHP
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Можно добавить дополнительную обработку здесь, если нужно
            }
        };
        xhttp.open("GET", "function/update_place.php?ticketId=" + ticketId + "&newPlace=" + newPlace, true);
        xhttp.send();
    }

    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("place").blur(); // Снятие фокуса с input
        }
    }

    function updateDatePlan(newDatePlan) {
        var ticketId = <?php echo $ticketId; ?>; // Получаем значение ticketId из PHP
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Можно добавить дополнительную обработку здесь, если нужно
            }
        };
        xhttp.open("GET", "function/update_date_plan.php?ticketId=" + ticketId + "&newDatePlan=" + newDatePlan, true);
        xhttp.send();
    }

    function updateLength(productId, cell, event) {
        // Проверяем, была ли нажата клавиша Enter (код клавиши 13)
        if (event.keyCode === 13) {
            // Заканчиваем редактирование ячейки, снимаем с нее фокус
            cell.blur();
            return;
        }

        var newLength = cell.textContent; // Получаем новое значение из редактируемой ячейки
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Обработка ответа от сервера, если нужно
                updateTotalMetr();
            }
        };
        xhttp.open("GET", "function/update_product_length.php?productId=" + productId + "&newLength=" + newLength, true);
        xhttp.send();
    }

    function updateLengthOnEnter(event) {
        // Проверяем, была ли нажата клавиша Enter (код клавиши 13)
        if (event.keyCode === 13) {
            // Получаем текущую активную ячейку
            var activeElement = document.activeElement;
            // Если текущий активный элемент - это редактируемая ячейка, снимаем с нее фокус
            if (activeElement.contentEditable === 'true') {
                activeElement.blur();
            }
        }
    }

    function updatePlace(productId, cell, event) {
        if (event.keyCode === 13) {
            cell.blur();
            return;
        }

        var newPlace = cell.textContent;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Обработка ответа от сервера, если нужно
            }
        };
        xhttp.open("GET", "function/update_product_place.php?productId=" + productId + "&newPlace=" + newPlace, true);
        xhttp.send();
    }

    function updatePlaceOnEnter(event) {
        if (event.keyCode === 13) {
            var activeElement = document.activeElement;
            if (activeElement.contentEditable === 'true') {
                activeElement.blur();
            }
        }
    }

    function updateQuantity(productId, cell, event) {
        if (event.keyCode === 13) {
            cell.blur();
            return;
        }

        var newQuantity = cell.textContent;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // После успешного обновления продукта вызываем скрипт для обновления общего количества продуктов
                updateTotalQuantity();
                updateTotalMetr();
            }
        };
        xhttp.open("GET", "function/update_product_quantity.php?productId=" + productId + "&newQuantity=" + newQuantity, true);
        xhttp.send();
    }

    function updateTotalQuantity() {
        var ticketQuantityInput = document.getElementById("ticketQuantityInput");
        var ticketId = <?php echo $ticketId; ?>; // Предполагая, что $ticketId доступен в JavaScript

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Обновляем общее количество продуктов в input
                ticketQuantityInput.value = this.responseText;
            }
        };
        xhttp.open("GET", "function/update_total_quantity.php?ticketId=" + ticketId, true);
        xhttp.send();
    }

    function updateTotalMetr() {
        var ticketMetrInput = document.getElementById("ticketMetrInput");
        var ticketId = <?php echo $ticketId; ?>; // Предполагая, что $ticketId доступен в JavaScript

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Обновляем общее количество продуктов в input
                ticketMetrInput.value = this.responseText;
            }
        };
        xhttp.open("GET", "function/update_total_metr.php?ticketId=" + ticketId, true);
        xhttp.send();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.delete');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {

                var ticketId = this.getAttribute('data-ticket-id');
                fetch('function/delete_ticket.php?ticketId=' + ticketId)

                window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'
            });
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.send-to-approval');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {

                var ticketId = this.getAttribute('data-ticket-id');
                fetch('function/send_to_approval.php?ticketId=' + ticketId)

                window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.send-to-workshop');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {

                var ticketId = this.getAttribute('data-ticket-id');
                fetch('function/send_to_workshop.php?ticketId=' + ticketId)

                window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.approve');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {

                var ticketId = this.getAttribute('data-ticket-id');
                fetch('function/approve.php?ticketId=' + ticketId)

                window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.send-to-revision');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {

                var ticketId = this.getAttribute('data-ticket-id');
                fetch('function/send_to_revision.php?ticketId=' + ticketId)

                window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.deny');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {

                var ticketId = this.getAttribute('data-ticket-id');
                fetch('function/deny.php?ticketId=' + ticketId)

                window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'
            });
        });
    });



    function updateTicketData(productId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                updateTicketMeters(productId); // После обновления количества, обновляем метры погонные
                updateQuantityOnPage(productId); // Обновляем количество на странице
            }
        };
        xhttp.open("GET", "function/update_ticket_data.php?productId=" + productId, true); // Здесь productId или ticketId, в зависимости от вашей логики
        xhttp.send();
    }

    function updateQuantityOnEnter(event) {
        if (event.keyCode === 13) {
            var activeElement = document.activeElement;
            if (activeElement.contentEditable === 'true') {
                activeElement.blur();
            }
        }
    }

    function updateProductName(input) {
        var productId = input.getAttribute('data-id');
        var newName = input.value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Обработка ответа от сервера, если нужно
                console.log("Product name updated successfully");
            }
        };
        xhttp.open("GET", "function/update_product_name.php?productId=" + productId + "&newName=" + newName, true);
        xhttp.send();
    }

    function updateProductNameOnEnter(event, input) {
        if (event.keyCode === 13) {
            input.blur();
        }
    }


    function updateProductSum(){
        // Получаем элемент <td> по его id
        var productSumElements = document.querySelectorAll('.product-sum-value');

        // Для каждого элемента устанавливаем обработчик события
        productSumElements.forEach(function(element) {
            var productId = element.getAttribute('data-id');

            // Отправляем асинхронный запрос на сервер
            fetch('function/update_product_sum.php?productId=' + productId)
                .then(response => response.text())
                .then(data => {
                    // Обновляем содержимое элемента суммой, полученной от сервера
                    element.textContent = data;
                })
                .catch(error => console.error('Ошибка при обновлении суммы:', error));
        });
    }

    function printPage() {
        // Получаем контейнер с данными для печати
        var printContent = document.getElementById("printContent");
        // Открываем новое окно для печати
        var printWindow = window.open('', '_blank');
        // Вставляем содержимое для печати
        printWindow.document.write(printContent.innerHTML);

        // Задаем стили для body в новом окне
        printWindow.document.body.style.padding = '20px';
        printWindow.document.body.style.fontFamily = "'GOST Common', Arial";

        // Добавляем нижний колонтитул
        var footer = printWindow.document.createElement('div');
        footer.style.position = 'fixed';
        footer.style.bottom = '0';
        footer.style.right = '0';
        footer.style.paddingRight = '25px';
        footer.style.paddingBottom = '40px';
        footer.style.backgroundColor = 'black';
        <?php
             $sqlPrint = "SELECT tmc.TicketMetalCadName, tmc.TicketMetalCadId, tmc.TicketMetalCadDateCreate, tmc.TicketMetalCadQuantityMetr, tmc.TicketMetalCadApplicant, u.name, u.surname, tmc.TicketMetalCadColor, c.ColorName, tmc.TicketMetalCadThickness, t.ThicknessValue, tmc.TicketMetalCadStatusId, tmc.TicketMetalCadNum
                                FROM TicketMetalCad AS tmc
                                JOIN user AS u ON tmc.TicketMetalCadApplicant = u.userId
                                JOIN ColorCad AS c ON tmc.TicketMetalCadColor = c.ColorId
                                JOIN ThicknessMetalCad AS t ON tmc.TicketMetalCadThickness = t.ThicknessId
                                WHERE tmc.ProjectId = $projectId";
             $result = $conn->query($sqlPrint);

             if ($result->num_rows > 0) { 
                while($row = $result->fetch_assoc()){ 
                $mader = $row['name'].' '. $row['surname'];
                } 
            }

            $sqlFile = "SELECT ProjectName FROM ProjectMetalCad WHERE ProjectId = $projectId";
            $result = $conn->query($sqlFile);

            if ($result->num_rows > 0) { 
                while($row = $result->fetch_assoc()){ 
                  $projectName = $row['ProjectName'];
                } 
            }

            $sqlNumTicket = "SELECT TicketMetalCadName, TicketMetalCadNum from TicketMetalCad where TicketMetalCadId = $ticketId";

            $result = $conn->query($sqlNumTicket);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $numTicket =  $row['TicketMetalCadNum'];
            }

            $sqlPlace = "SELECT * from TicketMetalCad where TicketMetalCadId = $ticketId";

            $result = $conn->query($sqlPlace);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Пример строки даты из базы данных
                $ticketMetalCadDateCreateT = $row['TicketMetalCadDateCreate'];

                // // Преобразование строки даты во временную метку
                $timestampT = strtotime($ticketMetalCadDateCreateT);

                // // Форматирование даты в требуемый формат
                $formattedDateT = date("d.m.Y", $timestampT);


                $placeTicket = $row['TicketMetalCadPlace'];
                
            }
        ?>
        footer.innerHTML = 'СОСТАВИЛ: <?php echo  $mader; ?>';
        printWindow.document.body.appendChild(footer);

        // Закрываем запись в новом окне
        printWindow.document.close();
        
        // Устанавливаем задержку перед вызовом print()
        setTimeout(function() {

            

            // Указываем имя файла для сохранения
            printWindow.document.title = '<?php echo $projectName.'_№_'.$numTicket.'_'.$placeTicket.'_'.$formattedDateT ?>';
            // Добавляем атрибут download с указанием названия файла
            printWindow.document.body.setAttribute('download', '<?php echo $projectName.'_№_'.$numTicket.'_'.$placeTicket.'_'.$formattedDateT ?>.pdf');
            // Вызываем функцию сохранения файла как PDF с указанным именем
            printWindow.print();

           
        }, 50); // 10000 миллисекунд = 10 секунд
    }


    // Рисование чертежей
    var canvasHistory = {};
    var tempCanvas = document.createElement('canvas');
    var tempContext = tempCanvas.getContext('2d');

    var canvasList = document.getElementsByTagName('canvas');
    for (var i = 0; i < canvasList.length; i++) {
        var canvas = canvasList[i];
        var context = canvas.getContext('2d');
        drawGrid(canvas, context);
        var canvasData = { lines: [], isDrawing: false };
        canvasData.history = { lines: [], lastSavedIndex: -1 };
        <?php 
        $sqlProd = "SELECT TicketMetalCadStatusId from TicketMetalCad where TicketMetalCadId = $ticketId";

        $result = $conn->query($sqlThik);
            if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
               if($row['TicketMetalCadStatusId'] == 1 || $row['TicketMetalCadStatusId'] == 4){
                    echo " 
                        canvas.addEventListener('mousedown', startDrawing.bind(null, canvas, canvasData));
                        canvas.addEventListener('mouseup', endDrawing.bind(null, canvas, canvasData));
                        canvas.addEventListener('mousemove', drawTempLine.bind(null, canvas, canvasData));
                        canvas.addEventListener('keydown', function(e) {
                            if (e.ctrlKey && e.key === 'z') {
                                e.preventDefault();
                                cancelLastLine(this);
                            }
                        });
                        ";
               } elseif($roleId == 2 || $roleId == 5){ 
                    echo " 
                        canvas.addEventListener('mousedown', startDrawing.bind(null, canvas, canvasData));
                        canvas.addEventListener('mouseup', endDrawing.bind(null, canvas, canvasData));
                        canvas.addEventListener('mousemove', drawTempLine.bind(null, canvas, canvasData));
                        canvas.addEventListener('keydown', function(e) {
                            if (e.ctrlKey && e.key === 'z') {
                                e.preventDefault();
                                cancelLastLine(this);
                            }
                        });
                    ";
               }
            }
        ?>
        
    }

    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'z') {
            e.preventDefault();
            cancelLastAction();
        }
    });

    function drawGrid(canvas, context) {
        var gridSize = 20;
        context.beginPath();
        for (var x = 0; x <= canvas.width; x += gridSize) {
            context.moveTo(x, 0);
            context.lineTo(x, canvas.height);
        }
        for (var y = 0; y <= canvas.height; y += gridSize) {
            context.moveTo(0, y);
            context.lineTo(canvas.width, y);
        }
        context.strokeStyle = 'lightgray';
        context.lineWidth = 1;
        context.stroke();
    }

    function drawGridAndLines(canvas, context, data) {
        context.clearRect(0, 0, canvas.width, canvas.height);
        drawGrid(canvas, context); 
        redrawCanvas(canvas, context, data); 
        drawTempLine(canvas, data, { clientX: 0, clientY: 0 }); // Добавляем предварительный просмотр временной линии
    }

    function startDrawing(canvas, data, e) {
        data.isDrawing = true;
        var rect = canvas.getBoundingClientRect();
        var gridSize = 20;
        var mouseX = e.clientX - rect.left;
        var mouseY = e.clientY - rect.top;
        var startX = Math.round(mouseX / gridSize) * gridSize;
        var startY = Math.round(mouseY / gridSize) * gridSize;
        data.lines.push({ startX: startX, startY: startY, endX: startX, endY: startY });
        redrawCanvas(canvas, canvas.getContext('2d'), data);
    }

    function endDrawing(canvas, data) {
        if (!data.isDrawing) return;
        data.isDrawing = false;
        tempContext.clearRect(0, 0, canvas.width, canvas.height);

        var currentLine = data.lines[data.lines.length - 1];
        var midX = (currentLine.startX + currentLine.endX) / 2;
        var midY = (currentLine.startY + currentLine.endY) / 2;
        
        // Проверяем ориентацию линии и корректируем координаты для числа
        var offsetX = 0;
        var offsetY = 0;
        if (Math.abs(currentLine.endY - currentLine.startY) < Math.abs(currentLine.endX - currentLine.startX)) {
            // Горизонтальная или почти горизонтальная линия
            offsetY = -20; // Поднимаем число на 20 пикселей
        } else {
            // Вертикальная или почти вертикальная линия
            offsetX = -20; // Сдвигаем число влево на 20 пикселей
        }

        drawNumberOnLine(canvas, canvas.getContext('2d'), midX + offsetX, midY + offsetY, '0');

        var productId = canvas.getAttribute('data-id');
        saveNumberToDatabase(productId, midX, midY, 0);
        
        var history = data.history;
        var lastSavedIndex = history.lastSavedIndex;
        
        var newLines = data.lines.slice(lastSavedIndex + 1); 
            
        history.lines.push(...newLines); 
        history.lastSavedIndex = history.lines.length - 1; 
        
        saveLinesToDatabase(canvas, newLines); 
        redrawCanvas(canvas, canvas.getContext('2d'), data);
    }

    function drawNumberOnLine(canvas, context, x, y, number) {
        context.font = '20px Arial';
        context.fillStyle = 'black';
        context.textAlign = 'center';
        context.fillText(number, x - 15, y - 5);
    }


    canvas.addEventListener('click', function(e) {
        var rect = canvas.getBoundingClientRect();
        var mouseX = e.clientX - rect.left;
        var mouseY = e.clientY - rect.top;

        var allNumbers = [...canvasHistory[canvas.id].history.numbers, ...canvasHistory[canvas.id].numbers];
        for (var i = 0; i < allNumbers.length; i++) {
            var numberData = allNumbers[i];
            var dist = Math.sqrt(Math.pow(mouseX - numberData.x, 2) + Math.pow(mouseY - numberData.y, 2));
            if (dist < 10) {
                var newNumber = prompt('Enter new number:', numberData.number);
                if (newNumber !== null) {
                    updateNumber(numberData.productId, numberData.x, numberData.y, newNumber);
                }
                break;
            }
        }
    });

    function updateNumberInDatabase(productId, x, y, newNumber) {
        var numberData = {
            productId: productId,
            x: x,
            y: y,
            number: newNumber
        };
        $.ajax({
            url: 'function/update_number.php', // Путь к скрипту для обновления значения цифры в БД
            method: 'POST',
            data: numberData,
            success: function(response) {
                console.log('Number updated successfully');
            },
            error: function(xhr, status, error) {
                console.error('Error updating number:', error);
            }
        });
    }


    function saveNumberToDatabase(productId, x, y, number) {
        var numberData = {
            productId: productId,
            x: x,
            y: y,
            number: number
        };
        $.ajax({
            url: 'function/save_number.php',
            method: 'POST',
            data: numberData,
            success: function(response) {
                console.log('Number saved successfully');
            },
            error: function(xhr, status, error) {
                console.error('Error saving number:', error);
            }
        });
    }

    function drawTempLine(canvas, data, e) {
        if (!data.isDrawing) return;
        var rect = canvas.getBoundingClientRect();
        var gridSize = 20;
        var mouseX = e.clientX - rect.left;
        var mouseY = e.clientY - rect.top;
        var endX = Math.round(mouseX / gridSize) * gridSize;
        var endY = Math.round(mouseY / gridSize) * gridSize;

        var currentLine = data.lines[data.lines.length - 1];
        currentLine.endX = endX;
        currentLine.endY = endY;

        tempContext.clearRect(0, 0, tempCanvas.width, tempCanvas.height);

        // Рисуем основные линии на временном холсте
        var allLines = [...data.history.lines, ...data.lines];
        for (var i = 0; i < allLines.length; i++) {
            var line = allLines[i];
            tempContext.beginPath();
            tempContext.moveTo(line.startX, line.startY);
            tempContext.lineTo(line.endX, line.endY);
            tempContext.strokeStyle = 'black';
            tempContext.lineWidth = 2;
            tempContext.stroke();
        }

        // Рисуем временную линию на временном холсте зеленым цветом
        tempContext.beginPath();
        tempContext.moveTo(currentLine.startX, currentLine.startY);
        tempContext.lineTo(currentLine.endX, currentLine.endY);
        tempContext.strokeStyle = '#59B077'; // Зеленый цвет для временной линии
        tempContext.lineWidth = 2;
        tempContext.stroke();

        // Отображаем временный холст как задний фон основного холста
        canvas.style.background = 'url(' + tempCanvas.toDataURL() + ')';
    }


    function redrawCanvas(canvas, context, data) {
        var historyLines = data.history ? data.history.lines : [];
        var allLines = [...historyLines, ...data.lines];

        tempContext.clearRect(0, 0, canvas.width, canvas.height);
        tempContext.drawImage(canvas, 0, 0);

        for (var i = 0; i < allLines.length - 1; i++) {
            var line = allLines[i];
            context.beginPath();
            context.moveTo(line.startX, line.startY);
            context.lineTo(line.endX, line.endY);
            context.strokeStyle = 'black';
            context.lineWidth = 4;
            context.stroke();
        }

        var currentLine = data.lines[data.lines.length - 1];
        context.beginPath();
        context.moveTo(currentLine.startX, currentLine.startY);
        context.lineTo(currentLine.endX, currentLine.endY);
        context.strokeStyle = 'black';
        context.lineWidth = 4;
        context.stroke();
    }

    function cancelLastLine(canvas) {
        var history = canvasHistory[canvas.id].lines;
        var lastSavedIndex = canvasHistory[canvas.id].lastSavedIndex;
        if (!history || lastSavedIndex < 0) return;

        history.splice(lastSavedIndex + 1);
        canvasHistory[canvas.id].lastSavedIndex = history.length - 1;
        redrawCanvas(canvas, context, history);
    }

    function cancelLastAction() {
        var activeCanvas = document.activeElement;
        if (!activeCanvas || !canvasHistory[activeCanvas.id]) return;

        cancelLastLine(activeCanvas);
    }

    function saveLinesToDatabase(canvas, lines) {
        var productId = canvas.getAttribute('data-id');
        for (var i = 0; i < lines.length; i++) {
            var line = lines[i];
            var lineData = {
                productId: productId,
                startX: line.startX,
                startY: line.startY,
                endX: line.endX,
                endY: line.endY
            };
            $.ajax({
                url: 'function/save_lines.php',
                method: 'POST',
                data: lineData,
                success: function(response) {
                    console.log('Line saved successfully');
                    loadLinesAndDraw();
                },
                error: function(xhr, status, error) {
                    console.error('Error saving line:', error);
                }
            });
        }
    }

    function loadLinesAndDraw() {
        var canvasList = document.getElementsByTagName('canvas');
        for (var i = 0; i < canvasList.length; i++) {
            var canvas = canvasList[i];
            var productId = canvas.getAttribute('data-id');
            $.ajax({
                url: 'function/get_lines_and_numbers.php',
                method: 'POST',
                data: { productId: productId },
                dataType: 'json',
                success: function(canvas, response) {
                    return function(response) {
                        var context = canvas.getContext('2d');
                        drawGridAndLines(canvas, context, { lines: response.lines });
                        drawNumbersOnCanvas(canvas, context, response.numbers);
                        updateProductSum()
                    };
                }(canvas),
                error: function(xhr, status, error) {
                    console.error('Error loading lines and numbers:', error);
                }
            });
        }
    }

    function drawNumbersOnCanvas(canvas, context, numbers) {
        for (var i = 0; i < numbers.length; i++) {
            var numberData = numbers[i];
            var offsetX = 0;
            var offsetY = 0;

            // Если это новая линия, определяем наклон и устанавливаем позицию цифры
            if (numberData.hasOwnProperty('lineSlope')) {
                // Определяем наклон линии
                var lineSlope = numberData.lineSlope;

                // Устанавливаем позицию в зависимости от наклона линии
                if (lineSlope >= -0.5 && lineSlope <= 0.5) {
                    // Горизонтальная линия, добавляем отступ вверх
                    offsetY = -20;
                } else if (lineSlope >= 1.5 || lineSlope <= -1.5) {
                    // Вертикальная линия, добавляем отступ влево
                    offsetX = -20;
                }
            }

            // Устанавливаем позицию цифры с учетом отступа
            drawNumberOnLine(canvas, context, numberData.x + offsetX, numberData.y + offsetY, numberData.number);
        }
    }

    // Функция для определения наклона линии
    function getLineSlope(x1, y1, x2, y2) {
        return (y2 - y1) / (x2 - x1);
    }

    function updateTempLine(canvas, tempCanvas, tempContext, e) {
        var data = canvasHistory[canvas.id];
        if (!data.isDrawing) return;
        var rect = canvas.getBoundingClientRect();
        var gridSize = 20;
        var mouseX = e.clientX - rect.left;
        var mouseY = e.clientY - rect.top;
        var endX = Math.round(mouseX / gridSize) * gridSize;
        var endY = Math.round(mouseY / gridSize) * gridSize;

        var currentLine = data.lines[data.lines.length - 1];
        currentLine.endX = endX;
        currentLine.endY = endY;

        tempContext.clearRect(0, 0, tempCanvas.width, tempCanvas.height);
        redrawCanvas(tempCanvas, tempContext, data);
    }

    tempCanvas.addEventListener('mousemove', function(e) {
        updateTempLine(canvas, tempCanvas, tempContext, e);
    });

    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;

    window.onload = loadLinesAndDraw;
            
    </script>
</body>
</html>