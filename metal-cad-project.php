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
    <title>Сотрудники</title>
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
                <div class="content-header">
                    <div class="title">
                        <button class="back"></button>
                        <h1>Многоэтажка на Дмитривском шоссе</h1>
                    </div>
                    <div class="status-project plan">Планирование</div>
                </div>
                <div class="subtitle">
                    <div class="project-nav">
                        <button class="active">Заявка</button>
                        <button>Настройки</button>
                        <button>Аналитика</button>
                    </div>
                    <div class="ticket-select">
                        <button class="slide"></button>
                        <button class="table"></button>
                        <button class="add-ticket">Добавить</button>
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
                    <div class="slide plan">
                        <div class="title">Заявка на гибку №1</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="color">RAL 7024 0,5 мм</div>
                        <div class="status">Планирование</div>
                    </div>
                </div>

                <div class="table">
                    <div class="table-information-bars">
                        <div class="bar">
                            <div class="name">
                                <p>Проекта</p>
                                <p class="value">80/100 м.пог.</p>
                            </div>
                            <div class="progress-bar"></div>
                        </div>
                    </div>
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
                            <tr>
                                <td id="ticket-num-value">1</td>
                                <td id="ticket-name-value">NEXT 2</td>
                                <td id="ticket-date-value">25.04.2024г.</td>
                                <td id="ticket-color-value">RAL 7024</td>
                                <td id="ticket-thikness-value">0.7</td>
                                <td id="ticket-metr-value">14</td>
                                <td id="ticket-responsible-value">Евгений Прищеп</td>
                                <td id="ticket-status-value"><div class="status plan">Планирование</div></td>
                            </tr>
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