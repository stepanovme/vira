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
    <link rel="stylesheet" href="css/metal-cad-settings.css">
    <title>Сотрудники</title>
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
                        <button class="active">Н</button>
                        <button onclick="window.location.href = 'metal-cad-analyt.php?projectId=<?php echo $projectId;?>'">А</button>
                    </div>
                    <div class="project-nav">
                        <button onclick="window.location.href = 'metal-cad-project.php?projectId=<?php echo $projectId;?>'">Заявка</button>
                        <button class="active">Настройки</button>
                        <button onclick="window.location.href = 'metal-cad-analyt.php?projectId=<?php echo $projectId;?>'">Аналитика</button>
                    </div>
                </div>

                <div class="settings">
                    <form action="">
                        <label for="">Название</label>
                        <input type="text">
                        <label for="">Объект</label>
                        <input type="text">
                        <label for="">Цвета</label>
                        <select name="" id="">
                            <option value="" selected disabled>Цвета</option>
                        </select>
                        <label for="">Толщины</label>
                        <select name="" id="">
                            <option value="" selected disabled>Толщины</option>
                        </select>
                        <label for="">Ответственный</label>
                        <select name="" id="">
                            <option value="" selected disabled>Ответственный</option>
                        </select>
                        <label for="">Участники</label>
                        <select name="" id="">
                            <option value="" selected disabled>Участники</option>
                        </select>
                        <label for="">План по проекту</label>
                        <input type="text">
                        <label for="">Дата проекта</label>
                        <input type="text">
                        <label for="">Статус</label>
                        <input type="text">
                    </form>
                </div>
                <div class="buttons">
                    <button class="save">Сохранить</button>
                    <button class="delete">Удалить проект</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="./js/mobile.js"></script>
    <script src="/js/metal-cad-settings.js"></script>
</body>
</html>