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
    <link rel="stylesheet" href="css/employee.css">
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
                    <h1>Сотрудники</h1>
                    <button id="add">Добавить</button>
                    <button id="mobile-add">+</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Сотрудник</th>
                            <th id="login-th">Логин</th>
                            <th id="password-th">Пароль</th>
                            <th>Роль</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $sql = "SELECT u.userId, u.name, u.surname, u.login, u.password, u.roleId, r.roleName
                                    FROM user u
                                    INNER JOIN role r ON u.roleId = r.roleId";
                            $result = $conn->query($sql);
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td>'.$row['name'].' '.$row['surname'].'</td>
                                            <td id="login">'.$row['login'].'</td>
                                            <td id="password">'.$row['password'].'</td>
                                            <td>
                                                <select class="role-select" data-userid="'.$row['userId'].'">
                                                    <option value="'.$row['roleId'].'">'.$row['roleName'].'</option>
                                                    <option value="1">Пользователь</option>
                                                    <option value="2">Администратор</option>
                                                    <option value="3">Прораб</option>
                                                    <option value="4">Бригадир</option>
                                                    <option value="5">Директор</option>';
                                    echo '</select>
                                            </td>
                                        </tr>';
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script src="./js/mobile.js"></script>
    <script src="./js/employee.js"></script>
    <script>
        
    </script>
</body>
</html>