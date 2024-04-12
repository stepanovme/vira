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
                            <div class="custom-select">
                                <input type="text" id="thicknessTicketInput" readonly placeholder="Выберите толщину">
                                <div class="select-options thickness-select-options">
                                    <ul>
                                        <?php 
                                            $sql = "SELECT pc.ThicknessId, cc.ThicknessValue AS ProjectThicknessValue, ccc.ThicknessValue AS ThicknessValueName
                                                    FROM ProjectMetalCadThickness pc
                                                    JOIN ThicknessMetalCad cc ON pc.ThicknessId = cc.ThicknessId
                                                    JOIN TicketMetalCad t ON pc.ProjectMetalCadId = t.ProjectId
                                                    JOIN ThicknessMetalCad ccc ON t.TicketMetalCadThickness = ccc.ThicknessId
                                                    WHERE t.TicketMetalCadId = 1";

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
                                    echo '<input type="text" name="place" id="place" value="'.$row['TicketMetalCadPlace'].'" onchange="updatePlace(this.value)" onkeypress="handleKeyPress(event)">';
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
                        $sql = "SELECT * from ProductMetalCad where TicketMetalCadId = $ticketId";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $num = 0;
                            while($row = $result->fetch_assoc()) {
                                $num = $num + 1;
                                echo '
                                    <tr>
                                        <td id="product-num-value">'.$num.'</td>
                                        <td id="product-name-value">
                                            <input type="text" value="'.$row['ProductMetalCadName'].'">
                                            <canvas></canvas>
                                        </td>
                                        <td id="product-sum-value">'.$row['ProductMetalCadSum'].'</td>
                                        <td id="product-length-value" contenteditable="true" onblur="updateLength(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updateLengthOnEnter(event)">' . $row['ProductMetalCadLength'] . '</td>
                                        <td id="product-quantity-value" contenteditable="true" onblur="updateQuantity(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updateQuantityOnEnter(event)">' . $row['ProductMetalCadQuantity'] . '</td>
                                        <td id="product-place-value" contenteditable="true" onblur="updatePlace(' . $row['ProductMetalCadId'] . ', this, event)" onkeypress="updatePlaceOnEnter(event)">' . $row['ProductMetalCadPlace'] . '</td>
                                    </tr>
                                ';
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="./js/mobile.js"></script>
    <script src="/js/metal-cad-ticket.js"></script>
    <script>
        const colorInput = document.getElementById('colorTicketInput');
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
                    colorInput.value = selectedColorName;
                    colorSelectOptions.style.display = 'none';
                });
            });
        });

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
                        ticketId: <?php echo $ticketId; ?>,
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


    function updatePlace(newPlace) {
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
                updateTicketMeters(productId);
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



    </script>
</body>
</html>