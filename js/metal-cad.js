var employee = document.getElementById("metal-cad");
employee.classList.add("active");

var employeeMobile = document.getElementById("metal-cad-mobile");
employeeMobile.classList.add("active-mobile");

var slide = document.getElementById("slide");
slide.classList.add("active");

document.addEventListener("DOMContentLoaded", function(event) {
    var animationState = localStorage.getItem('animationState');
    if (animationState === 'fillAnimation') {
        var fillScreen = document.createElement("div");
        fillScreen.classList.add("fill-screen");
        document.body.appendChild(fillScreen);

        fillScreen.addEventListener("animationend", function() {
            fillScreen.style.animation = "fillAnimationReverse 0.2s forwards";
            document.querySelector('.wrapper>.layout>.content>.content-header').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.search-header').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.slide-list').style.display = 'grid';
        }, { once: true });

        localStorage.removeItem('animationState');
    }
});

$(document).ready(function() {
    $(".table-btn").on("click", function() {
        var isActive = $(this).hasClass("active");
        $(".slide").removeClass("active");
        if (!isActive) {
            $(this).addClass("active");
        }
        if ($(".slide-list").is(":visible")) {
            $(".slide-list").slideUp("middle", function() {
                $(".table").slideDown();
            });
        } else if (!$(".table").is(":visible")) {
            $(".table").slideToggle();
        }
    });

    $(".slide").on("click", function() {
        var isActive = $(this).hasClass("active");
        $(".table-btn").removeClass("active");
        if (!isActive) {
            $(this).addClass("active");
        }
        if ($(".table").is(":visible")) {
            $(".table").slideUp("middle", function() {
                $(".slide-list").slideDown();
            });
        } else if (!$(".slide-list").is(":visible")) {
            $(".slide-list").slideToggle();
        }
    });
});

// Получаем кнопку "Добавить"
var addButton = document.getElementById('add');
var addButtonMobile = document.getElementById('mobile-add');

// Получаем модальное окно
var modal = document.getElementById('modal');

// Получаем элемент закрытия модального окна
var closeBtn = document.getElementsByClassName('close')[0];

// Добавляем обработчик события click на кнопку "Добавить"
addButton.addEventListener('click', function() {
    modal.style.display = 'flex'; // Отображаем модальное окно при нажатии на кнопку
});

// Добавляем обработчик события click на кнопку "Добавить"
addButtonMobile.addEventListener('click', function() {
    modal.style.display = 'flex'; // Отображаем модальное окно при нажатии на кнопку
});

// Добавляем обработчик события click на элемент закрытия модального окна
closeBtn.addEventListener('click', function(event) { // Добавляем параметр event
    modal.style.animation = 'fadeOut 0.3s ease-in-out';
    setTimeout(function() {
        modal.style.animation = '';
        modal.style.display = 'none'; // После окончания анимации скрываем модальное окно
    }, 200);
});

// Закрываем модальное окно при клике вне его области
window.addEventListener('click', function(event) { // Добавляем параметр event
    if (event.target == modal) {
        modal.style.animation = 'fadeOut 0.3s ease-in-out';
        setTimeout(function() {
            modal.style.animation = '';
            modal.style.display = 'none'; // После окончания анимации скрываем модальное окно
        }, 200);
    }
});



// Цвета
// Получение цветов из сервера
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

document.addEventListener("DOMContentLoaded", getColors);

// Создаем массив с цветами
const colors = [];
  
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

  
  // Генерируем элементы для списка цветов
  const dropdownContent = document.getElementById("colorDropdown");
  colors.forEach(color => {
    const colorOption = document.createElement("div");
    colorOption.textContent = color;
    colorOption.addEventListener("click", () => {
      selectColor(color);
    });
    dropdownContent.appendChild(colorOption);
  });


  function toggleColorDropdown() {
    const dropdown = document.getElementById("colorDropdown");
    dropdown.classList.toggle("show");
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
                personOption.textContent = person;
                personOption.addEventListener("click", () => {
                    selectResponsible(person);
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
function selectResponsible(person) {
    const input = document.getElementById("responsibleInput");
    let selectedResponsible = input.value.trim().split(',').map(item => item.trim()).filter(item => item !== ''); // Удаляем пустые значения и убираем пробелы вокруг

    // Проверяем, был ли выбран ответственный ранее
    const index = selectedResponsible.indexOf(person);
    if (index !== -1) {
        // Если ответственный уже выбран, удаляем его из списка выбранных ответственных
        selectedResponsible.splice(index, 1);
    } else {
        // Если ответственный не выбран, добавляем его в список
        selectedResponsible.push(person);
    }
  
    // Обновляем значение input
    input.value = selectedResponsible.join(', '); // Обновляем значение input
  
    // Применяем стили к выбранным элементам в выпадающем списке
    const dropdownContent = document.getElementById("responsibleDropdown");
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


// Отправка модального окна
document.getElementById("modal-add").addEventListener("click", function(event) {
    event.preventDefault(); // Предотвращаем стандартное поведение формы
    
    // Получаем значения из полей формы
    const projectName = document.querySelector('input[name="projectName"]').value.trim();
    const projectObject = document.querySelector('input[name="projectObject"]').value.trim();
    
    // Проверяем, чтобы оба поля были заполнены
    if (projectName === '' || projectObject === '') {
        alert("Пожалуйста, заполните все поля формы.");
        return;
    }
    
    // Далее следует ваш код для сравнения цвета и толщины с данными из базы данных и создания новых записей в таблицах
});
