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