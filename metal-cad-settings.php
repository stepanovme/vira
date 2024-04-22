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
                    <?php 
                        $settingProject = "SELECT * FROM ProjectMetalCad WHERE ProjectId = $projectId";

                        $result = $conn->query($settingProject);

                        if($result->num_rows>0){
                            while($row =  $result->fetch_assoc()){
                                echo '

                                    <form action="">
                                        <label for="">Название</label>
                                        <input type="text" id="projectName" value="'.$row['ProjectName'].'" onchange="updateNameProject(this.value)" onkeypress="handleKeyPress(event)">
                                        <label for="">Объект</label>
                                        <input type="text" id="projectObject" value="'.$row['ProjectObject'].'" onchange="updateObjectProject(this.value)" onkeypress="handleKeyPress(event)">
                                        <label class="label">Цвет</label>
                                        <div class="dropdown">
                                            <input type="text" id="colorInput" onclick="toggleDropdown()" readonly>
                                            <div id="colorDropdown" class="dropdown-content"></div>
                                        </div>
                                        <label class="label">Толщина</label>
                                        <div class="dropdown">
                                            <input type="text" id="thicknessInput" onclick="toggleThicknessDropdown()" readonly>
                                            <div id="thicknessDropdown" class="dropdown-content"></div>
                                        </div>
                                        <label class="label">Ответственный</label>
                                        <div class="dropdown">
                                            <input type="text" id="responsibleInput" onclick="toggleResponsibleDropdown()" readonly>
                                            <div id="responsibleDropdown" class="dropdown-content"></div>
                                        </div>
                                        <label for="">Участники</label>
                                        <select name="" id="">
                                            <option value="" selected disabled>Участники</option>
                                        </select>
                                        <label for="" value="'.$row['ProjectPlan'].'">План по проекту</label>
                                        <input type="text">
                                        <label for="">Дата проекта</label>
                                        <input type="text">
                                        <label for="">Статус</label>
                                        <input type="text">
                                    </form>
                                
                                    ';
                            }
                        }
                    ?>
                    
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
    <script>
        function updateNameProject(newPlace) {
            var ticketId = <?php echo $projectId; ?>;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Можно добавить дополнительную обработку здесь, если нужно
                }
            };
            xhttp.open("GET", "function/update_name_project.php?ticketId=" + ticketId + "&newPlace=" + newPlace, true);
            xhttp.send();
        }

        function handleKeyPress(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("projectName").blur();
            }
        }

        function updateObjectProject(newPlace) {
            var ticketId = <?php echo $projectId; ?>;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Можно добавить дополнительную обработку здесь, если нужно
                }
            };
            xhttp.open("GET", "function/update_object_project.php?ticketId=" + ticketId + "&newPlace=" + newPlace, true);
            xhttp.send();
        }

        function handleKeyPress(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("projectObject").blur();
            }
        }

        // Получение всех цветов из сервера
        function getColors() {
            fetch('function/get_colors.php')
                .then(response => response.json())
                .then(colors => {
                    showColors(colors);
                })
                .catch(error => {
                    console.error('Error fetching colors:', error);
                });
        }

        // Получение цветов, уже участвующих в проекте
        function getProjectColors(projectId) {
            fetch('function/get_project_colors.php?projectId=' + projectId)
                .then(response => response.json())
                .then(projectColors => {
                    // Заполнение input и отметка выбранных элементов в выпадающем списке
                    fillProjectColors(projectColors);

                    // Вызываем событие, оповещающее о загрузке цветов проекта
                    window.dispatchEvent(new Event("projectColorsLoaded"));
                })
                .catch(error => {
                    console.error('Error fetching project colors:', error);
                });
        }

        // Функция для заполнения input и отметки выбранных элементов в выпадающем списке
        function fillProjectColors(projectColors) {
            const input = document.getElementById("colorInput");
            const dropdownContent = document.getElementById("colorDropdown");

            // Очистка предыдущих значений
            input.value = "";
            input.removeAttribute("data-color-ids");
            const dropdownOptions = dropdownContent.getElementsByTagName("div");
            for (let option of dropdownOptions) {
                option.classList.remove("selected");
            }

            const selectedColors = [];
            const selectedColorIds = [];

            // Заполнение input и отметка выбранных элементов
            projectColors.forEach(colorObject => {
                const colorName = colorObject.name;
                const colorId = colorObject.id;

                input.value += colorName + ', ';
                selectedColors.push(colorName);
                selectedColorIds.push(colorId);

                for (let option of dropdownOptions) {
                    if (option.textContent === colorName) {
                        option.classList.add("selected");
                        break;
                    }
                }
            });

            // Убираем лишние запятые и пробелы в конце
            input.value = input.value.replace(/,\s*$/, "");

            // Устанавливаем атрибут data-color-ids для input
            input.dataset.colorIds = selectedColorIds.join(',');
        }

        document.addEventListener("DOMContentLoaded", () => {
            getColors(); // Получаем все цвета
            const projectId = <?php echo $projectId; ?>; // Замените на реальный ID проекта
            getProjectColors(projectId); // Получаем цвета проекта

            // Вызываем fillProjectColors после получения цветов проекта
            window.addEventListener("projectColorsLoaded", () => {
                fillProjectColors(projectColors);
            });
        });

        // Функция для отображения/скрытия выпадающего списка
        function toggleDropdown() {
            const dropdown = document.getElementById("colorDropdown");
            dropdown.classList.toggle("show");
        }

        // Обработчик события для выбора цвета
        function selectColor(colorObject) {
            const input = document.getElementById("colorInput");
            const selectedColors = input.value.split(',').map(color => color.trim());
            const selectedColorIds = input.dataset.colorIds ? input.dataset.colorIds.split(',') : [];
            const color = colorObject.name;
            const colorId = colorObject.id;
            
            // Проверяем, был ли выбран цвет ранее
            const index = selectedColors.indexOf(color);
            if (index !== -1) {
                // Если цвет уже выбран, удаляем его из списка выбранных цветов и соответствующий ему ColorId
                selectedColors.splice(index, 1);
                selectedColorIds.splice(index, 1);
            } else {
                // Если цвет не выбран, добавляем его в список и сохраняем его ColorId
                selectedColors.push(color);
                selectedColorIds.push(colorId);
            }
        
            // Обновляем значение input и атрибут data-id-цвет
            input.value = selectedColors.join(', ').replace(/^, /, ''); // Убираем запятую перед первым элементом
            input.dataset.colorIds = selectedColorIds.join(',');
        
            // Применяем стили к выбранным элементам в выпадающем списке
            const dropdownContent = document.getElementById("colorDropdown");
            const dropdownOptions = dropdownContent.getElementsByTagName("div");
            for (let option of dropdownOptions) {
                if (option.textContent === color) {
                    option.classList.toggle("selected");
                }
            }
        }

        // Отображение цветов в выпадающем списке
        function showColors(colors) {
            const dropdownContent = document.getElementById("colorDropdown");
            dropdownContent.innerHTML = ""; // Очистка содержимого перед добавлением новых цветов
            
            colors.forEach(colorObject => {
                const colorOption = document.createElement("div");
                colorOption.textContent = colorObject.name;
                colorOption.addEventListener("click", () => {
                    selectColor(colorObject);
                });
                dropdownContent.appendChild(colorOption);
            });
        }

        // Создаем экземпляр MutationObserver
        const observer = new MutationObserver(mutationsList => {
            for (let mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-color-ids') {
                    const projectId = <?php echo $projectId; ?>; // Замените на реальный ID проекта
                    const colorIds = document.getElementById("colorInput").dataset.colorIds.split(',');

                    // Удаляем все записи для данного проекта
                    fetch('function/delete_project_colors.php?projectId=' + projectId)
                        .then(response => response.text())
                        .then(() => {
                            // Добавляем новые записи
                            colorIds.forEach(colorId => {
                                fetch('function/add_project_color.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        projectId: projectId,
                                        colorId: colorId
                                    })
                                })
                                .catch(error => {
                                    console.error('Error adding project color:', error);
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Error deleting project colors:', error);
                        });
                }
            }
        });

        // Наблюдаем за изменениями атрибутов элемента с id="colorInput"
        observer.observe(document.getElementById("colorInput"), { attributes: true });

        // Функция для скрытия выпадающего списка при клике вне его области
        document.addEventListener("click", function(event) {
            const colorDropdown = document.getElementById("colorDropdown");
            const colorInput = document.getElementById("colorInput");
            if (event.target !== colorDropdown && event.target !== colorInput) {
                colorDropdown.classList.remove("show");
            }
        });


        

    </script>
</body>
</html>