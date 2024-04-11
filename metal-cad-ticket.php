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
                        <button class="back" onclick="window.location.href = 'metal-cad.php'"></button>
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
                                <div class="select-options">
                                    <ul>
                                        <?php 
                                            $sql = "SELECT pc.ColorId, cc.ColorName AS ProjectColorName, ccc.ColorName AS ColorTicketName
                                                    FROM ProjectMetalCadColor pc
                                                    JOIN ColorCad cc ON pc.ColorId = cc.ColorId
                                                    JOIN TicketMetalCad t ON pc.ProjectMetalCadId = t.ProjectId
                                                    JOIN ColorCad ccc ON t.TicketMetalCadColor = ccc.ColorId
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
                            <select name="" id="">
                                <option value="">Толщина</option>
                            </select>
                        </div>
                        <div class="line">
                            <label for="">Участок:</label>
                            <select name="" id="">
                                <option value="">Участок</option>
                            </select>
                        </div>
                        <div class="line">
                            <label for="">Бригада:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadBrigade from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo '<input type="text" value="'.$row['TicketMetalCadBrigade'].'">';
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
                                    echo '<input type="text" value="'.$row['TicketMetalCadAdress'].'">';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="column">
                        <div class="line">
                            <label for="">Дата план:</label>
                            <?php 
                                $sql = "SELECT TicketMetalCadDateCreate from TicketMetalCad where TicketMetalCadId = $ticketId";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo '<input type="date" value="'.$row['TicketMetalCadDateCreate'].'">';
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
                                    echo '<input type="text" value="'.$row['TicketMetalCadQuantityProduct'].'" readonly>';
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
                                    echo '<input type="text" value="'.$row['TicketMetalCadQuantityMetr'].'" readonly>';
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
                        <tr>
                            <td id="product-num-value">1</td>
                            <td id="product-name-value">
                                <input type="text">
                                <canvas></canvas>
                            </td>
                            <td id="product-sum-value">196</td>
                            <td id="product-length-value">196</td>
                            <td id="product-quantity-value">196</td>
                            <td id="product-place-value">1 этаж</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="./js/mobile.js"></script>
    <script src="/js/metal-cad-ticket.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('colorTicketInput');
        const selectOptions = document.querySelector('.custom-select .select-options');
        const colorList = document.querySelectorAll('.custom-select .select-options ul li');

        input.addEventListener('click', function() {
            selectOptions.style.display = 'block';
        });

        colorList.forEach(colorItem => {
            colorItem.addEventListener('click', function() {
                const selectedColorId = this.dataset.colorId;
                const selectedColorName = this.textContent;

                console.log(selectedColorId);
                // Отправить выбранный цвет в базу данных TicketMetalCadColor
                fetch('function/update_ticket_color.php', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ticketId: <?php echo $ticketId; ?>,
                        colorId: selectedColorId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Обработать успешный ответ, если необходимо
                    console.log('Color updated successfully');
                })
                .catch(error => {
                    // Обработать ошибку, если необходимо
                    console.error('Error updating color:', error);
                });

                // Установить выбранный цвет в input и закрыть список
                input.value = selectedColorName;
                selectOptions.style.display = 'none';
            });
        });
    });

    </script>
</body>
</html>