var employee = document.getElementById("metal-cad");
employee.classList.add("active");

var employeeMobile = document.getElementById("metal-cad-mobile");
employeeMobile.classList.add("active-mobile");

document.addEventListener("DOMContentLoaded", function(event) {
    var animationState = localStorage.getItem('animationState');
    if (animationState === 'fillAnimation') {
        var fillScreen = document.createElement("div");
        fillScreen.classList.add("fill-screen");
        document.body.appendChild(fillScreen);

        fillScreen.addEventListener("animationend", function() {
            fillScreen.style.animation = "fillAnimationReverse 0.2s forwards";
            document.querySelector('.wrapper>.layout>.content>.content-header').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.subtitle').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.buttons').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.settings').style.display = 'grid';
        }, { once: true });

        localStorage.removeItem('animationState');
    }
});

//   Толщины
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
        })
        .catch(error => {
            console.error('Error fetching thicknesses:', error);
        });
}

document.addEventListener("DOMContentLoaded", getThickness);



// Функция для выбора толщины
function selectThickness(thickness, thicknessId) {
    const input = document.getElementById("thicknessInput");
    let selectedThicknesses = input.value.trim().split(',').filter(thickness => thickness !== ''); // Удаляем пустые значения
    let selectedThicknessIds = input.dataset.thicknessIds ? input.dataset.thicknessIds.split(',') : []; // Получаем массив выбранных id толщин

    // Проверяем, была ли выбрана толщина ранее
    const index = selectedThicknesses.findIndex(selectedThickness => selectedThickness.trim() === thickness.trim());
    if (index !== -1) {
        // Если толщина уже выбрана, удаляем ее из списка выбранных толщин и соответствующий id из массива id
        selectedThicknesses.splice(index, 1);
        const idIndex = selectedThicknessIds.indexOf(thicknessId);
        if (idIndex !== -1) {
            selectedThicknessIds.splice(idIndex, 1);
        }
    } else {
        // Если толщина не выбрана, добавляем ее в список и соответствующий id в массив id
        selectedThicknesses.push(thickness);
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



// Ответственные
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

// Вызываем функцию получения списка ответственных при загрузке страницы
document.addEventListener("DOMContentLoaded", getResponsible);

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