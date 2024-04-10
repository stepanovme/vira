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
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/metal-cad.css">
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
                    <h1>Проекты по гибке металла</h1>
                    <button id="add">Добавить</button>
                    <button id="mobile-add">+</button>
                </div>
                <div class="search-header">
                    <?php 
                        if($roleId == 2 || $roleId == 5){
                            $sql = "SELECT ProjectMetalCad.*, user.name, user.surname 
                                FROM ProjectMetalCad
                                INNER JOIN user ON ProjectMetalCad.ProjectResponsible = user.userId";
                            $result = $conn->query($sql);
                            $num_actual = 0;
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $num_actual = $num_actual + 1;
                            }
                        } else if($roleId == 3 || $roleId == 4){
                            $sql = "SELECT ProjectMetalCad.*, user.name, user.surname 
                                FROM ProjectMetalCad
                                INNER JOIN user ON ProjectMetalCad.ProjectResponsible = user.userId
                                WHERE ProjectResponsible = $user_id";
                            $result = $conn->query($sql);
                            $num_actual = 0;
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $num_actual = $num_actual + 1;
                            }
                        }
                    ?>
                    <p class="actual">Актуальные <?php echo $num_actual;?></p>
                    <button class="slide" id="slide"></button>
                    <button class="table-btn"></button>
                </div>
                <div class="slide-list">
                    <?php
                        if($roleId == 2 || $roleId == 5){
                            $sql = "SELECT ProjectMetalCad.*, user.name, user.surname 
                                FROM ProjectMetalCad
                                INNER JOIN user ON ProjectMetalCad.ProjectResponsible = user.userId";

                            $result = $conn->query($sql);
                        
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()){
                                    if($row['StatusId'] == 1) {
                                        echo '<div class="slide plan">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Планирование</div>
                                        </div>';
                                    } elseif($row['StatusId'] == 2){
                                        echo '<div class="slide work">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">В работе</div>
                                        </div>';
                                    } elseif($row['StatusId'] == 3){
                                        echo '<div class="slide sent">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Отправлено</div>
                                        </div>';
                                    } elseif($row['StatusId'] == 4){
                                        echo '<div class="slide shipped">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Отгружен</div>
                                        </div>';
                                    } elseif($row['StatusId'] == 5){
                                        echo '<div class="slide completed">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Завершено</div>
                                        </div>';
                                    }
                                }
                            }
                        } else if($roleId == 3 || $roleId == 4){
                            $sql = "SELECT ProjectMetalCad.*, user.name, user.surname 
                                    FROM ProjectMetalCad
                                    INNER JOIN user ON ProjectMetalCad.ProjectResponsible = user.userId
                                    WHERE ProjectResponsible = $user_id";

                            $result = $conn->query($sql);
                        
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()){
                                    if($row['StatusId'] == 1) {
                                        echo '<div class="slide plan">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Планирование</div>
                                        </div>';
                                    } elseif($row['StatusId'] == 2){
                                        echo '<div class="slide work">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">В работе</div>
                                        </div>';
                                    } elseif($row['StatusId'] == 3){
                                        echo '<div class="slide sent">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Отправлено</div>
                                        </div>';
                                    } elseif($row['StatusId'] == 4){
                                        echo '<div class="slide shipped">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Отгружен</div>
                                        </div>';
                                    } elseif($row['StatusId'] == 5){
                                        echo '<div class="slide completed">
                                            <div class="title">'.$row['ProjectName'].'</div>
                                            <div class="responsible">'.$row['name'].' '.$row['surname'].'</div>
                                            <div class="status">Завершено</div>
                                        </div>';
                                    }
                                }
                            }
                        }
                        
                    ?>
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

                            <?php
                            if($roleId == 2 || $roleId == 5){
                                $sql = "SELECT ProjectMetalCad.*, user.name, user.surname 
                                    FROM ProjectMetalCad
                                    INNER JOIN user ON ProjectMetalCad.ProjectResponsible = user.userId";

                                $result = $conn->query($sql);
                            
                                if ($result->num_rows > 0) {
                                    $num = 0;
                                    while ($row = $result->fetch_assoc()){
                                        $dateString = $row['ProjectDateCreated'];
                                        $dateTimestamp = strtotime($dateString);
                                        $formattedDate = date('d.m.20y', $dateTimestamp);

                                        $num = $num + 1;
                                        if($row['StatusId'] == 1) {
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status plan">Планирование</div></td>
                                                </tr>';
                                        } elseif($row['StatusId'] == 2){
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status work">В работе</div></td>
                                                </tr>';
                                        } elseif($row['StatusId'] == 3){
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status sent">Отправлено</div></td>
                                                </tr>';
                                        } elseif($row['StatusId'] == 4){
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status shipped">Отгружен</div></td>
                                                </tr>';
                                        } elseif($row['StatusId'] == 5){
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status completed">Завершено</div></td>
                                                </tr>';
                                        }
                                    }
                                }
                            } else if($roleId == 3 || $roleId == 4){
                                $sql = "SELECT ProjectMetalCad.*, user.name, user.surname 
                                        FROM ProjectMetalCad
                                        INNER JOIN user ON ProjectMetalCad.ProjectResponsible = user.userId
                                        WHERE ProjectResponsible = $user_id";

                                $result = $conn->query($sql);

                                $num = 0;
                            
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()){ 
                                        $dateString = $row['ProjectDateCreated'];
                                        $dateTimestamp = strtotime($dateString);
                                        $formattedDate = date('d.m.20y', $dateTimestamp);
                                        if($row['StatusId'] == 1) {
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status plan">Планирование</div></td>
                                                </tr>';
                                        } elseif($row['StatusId'] == 2){
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status work">В работе</div></td>
                                                </tr>';
                                        } elseif($row['StatusId'] == 3){
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status sent">Отправлено</div></td>
                                                </tr>';
                                        } elseif($row['StatusId'] == 4){
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status shipped">Отгружен</div></td>
                                                </tr>';
                                        } elseif($row['StatusId'] == 5){
                                            echo '<tr>
                                                    <td id="table-num-value">'.$num.'</td>
                                                    <td id="table-project-value">'.$row['ProjectName'].'</td>
                                                    <td id="table-plan-value">'.$row['ProjectPlan'].'</td>
                                                    <td id="table-fact-value">'.$row['ProjectFact'].'</td>
                                                    <td id="table-date-value">'.$formattedDate.'г.</td>
                                                    <td id="table-responseble-value">'.$row['name'].' '.$row['surname'].'</td>
                                                    <td id="table-status-value"><div class="status completed">Завершено</div></td>
                                                </tr>';
                                        }
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