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
                                        <input type="text" id="projectName" value="'.$row['ProjectName'].'" onchange="updateNameProject(this.value)" onkeypress="handleKeyPressName(event)">
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
                                        <label class="label">Руководители проектов</label>
                                        <div class="dropdown">
                                            <input type="text" id="responsibleInput" onclick="toggleResponsibleDropdown()" readonly>
                                            <div id="responsibleDropdown" class="dropdown-content"></div>
                                        </div>
                                        <label for="participantsInput">Участники</label>
                                        <div class="dropdown">
                                            <input type="text" id="participantsInput" onclick="toggleParticipantsDropdown()" readonly>
                                            <div id="participantsDropdown" class="dropdown-content"></div>
                                        </div>
                                        <label for="">План по проекту</label>
                                        <input type="text" id="projectPlan" value="'.$row['ProjectPlan'].'" onchange="updatePlanProject(this.value)" onkeypress="handleKeyPressPlan(event)">
                                        <label for="">Дата проекта</label>
                                        <input type="date"  id="projectDate" value="'.date('Y-m-d', strtotime($row['ProjectDateCreated'])).'" onchange="updateDateProject(this.value)" onkeypress="handleKeyPressDate(event)">
                                    </form>
                                
                                    ';
                            }
                        }
                    ?>
                    
                </div>
                <div class="buttons">
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

        function handleKeyPressName(event) {
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

        function updatePlanProject(newPlace) {
            var ticketId = <?php echo $projectId; ?>;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Можно добавить дополнительную обработку здесь, если нужно
                }
            };
            xhttp.open("GET", "function/update_plan_project.php?ticketId=" + ticketId + "&newPlace=" + newPlace, true);
            xhttp.send();
        }

        function handleKeyPressPlan(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("projectPlan").blur();
            }
        }

        function updateDateProject(newPlace) {
            var ticketId = <?php echo $projectId; ?>;
            // Преобразуем значение даты к формату "yyyy-MM-dd"
            var dateValue = newPlace.split(' ')[0]; // Получаем только дату, отбрасывая время
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Дополнительная обработка ответа, если необходимо
                }
            };
            // Отправляем только дату на сервер
            xhttp.open("GET", "function/update_date_project.php?ticketId=" + ticketId + "&newPlace=" + dateValue, true);
            xhttp.send();
        }

        function handleKeyPressDate(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("projectDate").blur();
            }
        }

        
        // Объявляем переменную observer здесь
        let observer;

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
        observer = new MutationObserver(mutationsList => {
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

        // Функция для получения толщин из БД
        function getThickness() {
            fetch('function/get_thickness.php')
                .then(response => response.json())
                .then(thicknesses => {
                    // Добавляем толщины в выпадающий список
                    const dropdownContent = document.getElementById("thicknessDropdown");
                    thicknesses.forEach(thickness => {
                        const thicknessOption = document.createElement("div");
                        thicknessOption.textContent = thickness.value;
                        thicknessOption.dataset.id = thickness.id; // Сохраняем id толщины в атрибуте data-id
                        thicknessOption.addEventListener("click", () => {
                            selectThickness(thickness.value, thickness.id); // Передаем значение и id толщины в функцию обработки
                        });
                        dropdownContent.appendChild(thicknessOption);
                    });

                    // Получаем ID проекта и запрашиваем толщины проекта
                    const projectId = <?php echo $projectId; ?>; // Замените на реальный ID проекта
                    getProjectThicknesses(projectId);
                })
                .catch(error => {
                    console.error('Error fetching thicknesses:', error);
                });
        }

        document.addEventListener("DOMContentLoaded", getThickness);


        // Функция для получения толщин проекта из БД
        function getProjectThicknesses(projectId) {
            fetch('function/get_project_thicknesses.php?projectId=' + projectId)
                .then(response => response.json())
                .then(projectThicknesses => {
                    fillProjectThicknesses(projectThicknesses);
                })
                .catch(error => {
                    console.error('Error fetching project thicknesses:', error);
                });
        }

        // Функция для заполнения выбранных толщин проекта
        function fillProjectThicknesses(projectThicknesses) {
            const input = document.getElementById("thicknessInput");
            let selectedThicknesses = [];
            let selectedThicknessIds = [];

            // Очищаем предыдущие значения
            input.value = "";
            input.removeAttribute("data-thickness-ids");

            // Заполняем выбранные толщины
            projectThicknesses.forEach(thickness => {
                selectedThicknesses.push(thickness.value);
                selectedThicknessIds.push(thickness.id);
            });

            // Обновляем значение input
            input.value = selectedThicknesses.join(', ');
            input.dataset.thicknessIds = selectedThicknessIds.join(',');

            // Применяем стили к выбранным элементам в выпадающем списке
            const dropdownContent = document.getElementById("thicknessDropdown");
            const dropdownOptions = dropdownContent.getElementsByTagName("div");
            for (let option of dropdownOptions) {
                if (selectedThicknessIds.includes(option.dataset.id)) {
                    option.classList.add("selected");
                } else {
                    option.classList.remove("selected");
                }
            }
        }

        // Создаем экземпляр MutationObserver
        observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.attributeName === "data-thickness-ids") {
                    const projectId = <?php echo $projectId; ?>;
                    const thicknessIds = mutation.target.dataset.thicknessIds.split(',');

                    // Удаляем все записи для данного проекта
                    fetch('function/delete_project_thicknesses.php?projectId=' + projectId)
                        .then(response => response.text())
                        .then(() => {
                            // Добавляем новые записи
                            thicknessIds.forEach(thicknessId => {
                                fetch('function/add_project_thickness.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        projectId: projectId,
                                        thicknessId: thicknessId
                                    })
                                })
                                .catch(error => {
                                    console.error('Error adding project thickness:', error);
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Error deleting project thicknesses:', error);
                        });
                }
            });
        });

        // Настройка параметров для наблюдения за изменениями
        const config = { attributes: true };

        // Начать наблюдение за целевым элементом
        observer.observe(document.getElementById("thicknessInput"), config);

        // Функция для выбора толщины
        function selectThickness(thicknessName, thicknessId) {
            const input = document.getElementById("thicknessInput");
            let selectedThicknesses = input.value.trim().split(',').filter(thickness => thickness !== ''); // Удаляем пустые значения
            let selectedThicknessIds = input.dataset.thicknessIds ? input.dataset.thicknessIds.split(',') : []; // Получаем массив выбранных id толщин

            // Проверяем, была ли выбрана толщина ранее
            const index = selectedThicknesses.findIndex(selectedThickness => selectedThickness.trim() === thicknessName.trim());
            if (index !== -1) {
                // Если толщина уже выбрана, удаляем ее из списка выбранных толщин и соответствующий id из массива id
                selectedThicknesses.splice(index, 1);
                const idIndex = selectedThicknessIds.indexOf(thicknessId);
                if (idIndex !== -1) {
                    selectedThicknessIds.splice(idIndex, 1);
                }
            } else {
                // Если толщина не выбрана, добавляем ее в список и соответствующий id в массив id
                selectedThicknesses.push(thicknessName);
                selectedThicknessIds.push(thicknessId);
            }
        
            // Обновляем значение input
            input.value = selectedThicknesses.join(', '); // Обновляем значение input
            input.dataset.thicknessIds = selectedThicknessIds.join(','); // Обновляем атрибут data-thickness-ids
        
            // Применяем стили к выбранным элементам в выпадающем списке
            const dropdownContent = document.getElementById("thicknessDropdown");
            const dropdownOptions = dropdownContent.getElementsByTagName("div");
            for (let option of dropdownOptions) {
                if (selectedThicknessIds.includes(option.dataset.id)) { // Проверяем id толщины
                    option.classList.add("selected");
                } else {
                    option.classList.remove("selected");
                }
            }
        }

        // Функция для отображения/скрытия выпадающего списка
        function toggleThicknessDropdown() {
            const dropdown = document.getElementById("thicknessDropdown");
            dropdown.classList.toggle("show");
        }

        // Функция для скрытия выпадающего списка при клике вне его области
        document.addEventListener("click", function(event) {
            const thicknessDropdown = document.getElementById("thicknessDropdown");
            const thicknessInput = document.getElementById("thicknessInput");
            if (event.target !== thicknessDropdown && event.target !== thicknessInput) {
                thicknessDropdown.classList.remove("show");
            }
        });


        // Функция для обновления ответственных лиц проекта
function updateResponsibles(projectId, responsibleIds) {
    fetch('function/update_responsibles.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            projectId: projectId,
            responsibleIds: responsibleIds
        })
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
    })
    .catch(error => {
        console.error('Error updating responsibles:', error);
    });
}

// Функция для получения списка ответственных из БД
function getResponsible() {
    fetch('function/get_responsible.php')
        .then(response => response.json())
        .then(responsible => {
            // Добавляем ответственных в выпадающий список
            const dropdownContent = document.getElementById("responsibleDropdown");
            responsible.forEach(person => {
                const personOption = document.createElement("div");
                const fullName = person.name + ' ' + person.surname; // Конкатенируем имя и фамилию
                personOption.textContent = fullName;
                personOption.dataset.userId = person.userId; // Добавляем атрибут с userId
                personOption.addEventListener("click", () => {
                    selectResponsible(personOption); // Передаем элемент, а не объект person
                });
                dropdownContent.appendChild(personOption);
            });
        })
        .catch(error => {
            console.error('Error fetching responsible:', error);
        });
}

// Функция для получения уже выбранных ответственных лиц для проекта
function getSelectedResponsibles(projectId) {
    fetch('function/get_selected_responsible.php?projectId=' + projectId)
        .then(response => response.json())
        .then(selectedResponsibles => {
            const input = document.getElementById("responsibleInput");
            const dropdownContent = document.getElementById("responsibleDropdown");

            // Очищаем выпадающий список от старых значений
            dropdownContent.innerHTML = '';

            selectedResponsibles.forEach(person => {
                const personName = person.name + ' ' + person.surname;
                const userId = person.userId;
                const personOption = document.createElement("div");
                personOption.textContent = personName;
                personOption.dataset.userId = userId;
                personOption.classList.add("selected");
                dropdownContent.appendChild(personOption);
            });

            // Обновляем значение input
            const selectedNames = selectedResponsibles.map(person => person.name + ' ' + person.surname);
            input.value = selectedNames.join(', ');

            // Обновляем атрибут data-responsible-ids
            input.dataset.responsibleIds = selectedResponsibles.map(person => person.userId).join(',');
        })
        .catch(error => {
            console.error('Error fetching selected responsibles:', error);
        });
}

// Функция для отображения/скрытия выпадающего списка ответственных
function toggleResponsibleDropdown() {
    const dropdown = document.getElementById("responsibleDropdown");
    dropdown.classList.toggle("show");
}

// Функция для выбора ответственного
function selectResponsible(personOption) {
    const input = document.getElementById("responsibleInput");
    let selectedResponsible = input.value.trim().split(',').map(item => item.trim()).filter(item => item !== ''); // Удаляем пустые значения и убираем пробелы вокруг

    const personName = personOption.textContent; // Получаем имя и фамилию из выбранного элемента
    const userId = personOption.dataset.userId; // Получаем userId из dataset

    // Получаем dropdownContent
    const dropdownContent = document.getElementById("responsibleDropdown");

    // Проверяем, был ли выбран ответственный ранее
    const index = selectedResponsible.indexOf(personName);
    if (index !== -1) {
        // Если ответственный уже выбран, удаляем его из списка выбранных ответственных
        selectedResponsible.splice(index, 1);
    } else {
        // Если ответственный не выбран, добавляем его в список
        selectedResponsible.push(personName);
    }
    
    // Обновляем значение input
    input.value = selectedResponsible.join(', '); // Обновляем значение input

    // Добавляем userId в data-responsible-ids
    const responsibleIds = selectedResponsible.map(name => {
        const option = Array.from(dropdownContent.children).find(child => child.textContent === name);
        return option.dataset.userId;
    });
    input.dataset.responsibleIds = responsibleIds.join(',');

    // Применяем стили к выбранным элементам в выпадающем списке
    const dropdownOptions = dropdownContent.getElementsByTagName("div");
    for (let option of dropdownOptions) {
        if (selectedResponsible.includes(option.textContent.trim())) {
            option.classList.add("selected");
        } else {
            option.classList.remove("selected");
        }
    }
}

// Функция для скрытия выпадающего списка при клике вне его области
document.addEventListener("click", function(event) {
    const dropdown = document.getElementById("responsibleDropdown");
    const input = document.getElementById("responsibleInput");
    if (event.target !== dropdown && event.target !== input) {
        dropdown.classList.remove("show");
    }
});

// Вызываем функцию получения списка ответственных при загрузке страницы
document.addEventListener("DOMContentLoaded", () => {
    getResponsible();

    // Получаем ID текущего проекта (замените на ваш метод получения ID проекта)
    const projectId = <?php echo $projectId; ?>;
    getSelectedResponsibles(projectId);
});

// Обработка изменений в атрибуте data-responsible-ids
document.getElementById("responsibleInput").addEventListener("change", () => {
    // Получаем ID текущего проекта (замените на ваш метод получения ID проекта)
    const projectId = <?php echo $projectId; ?>;
    const responsibleIds = document.getElementById("responsibleInput").dataset.responsibleIds;
    updateResponsibles(projectId, responsibleIds);
});



document.querySelector('.delete').addEventListener('click', function() {
        var projectId = "<?php echo $projectId; ?>";
        var user_id = "<?php echo $user_id; ?>"; // Получение ProjectObject из PHP
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Обработка успешного ответа
                console.log("Новая строка TicketMetalCad добавлена успешно");
                
                window.location.href = 'metal-cad.php';
                // Перезагрузка страницы или обновление интерфейса по вашему желанию
            } else if (this.readyState == 4 && this.status != 200) {
                // Обработка ошибки
                console.error("Произошла ошибка при добавлении строки TicketMetalCad");
            }
        };
        xhttp.open("GET", "function/delete_project.php?projectId=" + projectId + "&user_id=" + user_id, true);
        xhttp.send();

    });

        


    </script>
</body>
</html>