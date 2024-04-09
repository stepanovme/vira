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

if ($roleId != 2 && $roleId != 5) {
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
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/metal-cad.css">
    <title>Сотрудники</title>
</head>
<body>
    <div class="wrapper">
        <div class="navbar">
            <div class="logo">VIRA</div>
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
                    <h1>Проекты по гибке металла</h1>
                    <button id="add">Добавить</button>
                    <button id="mobile-add">+</button>
                </div>
                <div class="search-header">
                    <p class="actual">Актуальные 6</p>
                    <button class="slide" id="slide"></button>
                    <button class="table-btn"></button>
                </div>  
                <div class="slide-list">
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                    <div class="slide plan">
                        <div class="title">Многоэтажка на Дмитривском шоссе</div>
                        <div class="responsible">Евгений Прищеп</div>
                        <div class="status">Планирование</div>
                    </div>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th id="table-num">№</th>
                                <th id="table-project">Проект</th>
                                <th id="table-plan">План</th>
                                <th id="table-fact">Факт</th>
                                <th id="table-date">Дата создания</th>
                                <th id="table-responseble">Ответственный</th>
                                <th id="table-status">Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="table-num-value">1</td>
                                <td id="table-project-value">Многоэтажка на Дмитривском шоссе</td>
                                <td id="table-plan-value">10000</td>
                                <td id="table-fact-value">14000</td>
                                <td id="table-date-value">14.05.2024г.</td>
                                <td id="table-responseble-value">Евгений Прищеп</td>
                                <td id="table-status-value"><div class="status plan">Планирование</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">
        <form>
            <p class="modal-title">Создание проекта</p>
            <label class="label">Название проекта</label>
            <input type="text" name="" id="" placeholder="Катунино">
            <label class="label">Объект</label>
            <input type="text" name="" id="" placeholder="Кактунино">
            <label class="label">Цвет</label>
            <select name="" id="">
                <option value="" selected disabled>Цвет</option>
            </select>
            <label class="label">Толщина</label>
            <select name="" id="">
                <option value="" selected disabled>Толщина</option>
            </select>
            <label class="label">Ответственный</label>
            <select name="" id="">
                <option value="" selected disabled>Ответственный</option>
            </select>
            <button id="modal-add" type="submit">Добавить</button>
            <button class="close" type="button">Отменить</button>
        </form>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="./js/mobile.js"></script>
    <script src="./js/metal-cad.js"></script>
</body>
</html>