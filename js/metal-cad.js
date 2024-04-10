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
function getColors() {
    fetch('function/get_colors.php')
      .then(response => response.json())
      .then(colors => {
        // Добавляем цвета в выпадающий список
        const dropdownContent = document.getElementById("colorDropdown");
        colors.forEach(color => {
          const colorOption = document.createElement("div");
          colorOption.textContent = color;
          colorOption.addEventListener("click", () => {
            selectColor(color);
          });
          dropdownContent.appendChild(colorOption);
        });
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
function selectColor(color) {
    const input = document.getElementById("colorInput");
    const selectedColors = input.value.split(',').map(color => color.trim());
    
    // Проверяем, был ли выбран цвет ранее
    const index = selectedColors.indexOf(color);
    if (index !== -1) {
      // Если цвет уже выбран, удаляем его из списка выбранных цветов
      selectedColors.splice(index, 1);
    } else {
      // Если цвет не выбран, добавляем его в список
      selectedColors.push(color);
    }
  
    // Обновляем значение input
    input.value = selectedColors.join(', ').replace(/^, /, ''); // Убираем запятую перед первым элементом
  
    // Применяем стили к выбранным элементам в выпадающем списке
    const dropdownContent = document.getElementById("colorDropdown");
    const dropdownOptions = dropdownContent.getElementsByTagName("div");
    for (let option of dropdownOptions) {
      if (selectedColors.includes(option.textContent)) {
        option.classList.add("selected");
      } else {
        option.classList.remove("selected");
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
                thicknessOption.textContent = thickness;
                thicknessOption.addEventListener("click", () => {
                    selectThickness(thickness);
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
function selectThickness(thickness) {
    const input = document.getElementById("thicknessInput");
    let selectedThicknesses = input.value.trim().split(',').filter(thickness => thickness !== ''); // Удаляем пустые значения

    // Проверяем, была ли выбрана толщина ранее
    const index = selectedThicknesses.findIndex(selectedThickness => selectedThickness.trim() === thickness.trim());
    if (index !== -1) {
        // Если толщина уже выбрана, удаляем ее из списка выбранных толщин
        selectedThicknesses.splice(index, 1);
    } else {
        // Если толщина не выбрана, добавляем ее в список
        selectedThicknesses.push(thickness);
    }
  
    // Обновляем значение input
    input.value = selectedThicknesses.join(', '); // Обновляем значение input
  
    // Применяем стили к выбранным элементам в выпадающем списке
    const dropdownContent = document.getElementById("thicknessDropdown");
    const dropdownOptions = dropdownContent.getElementsByTagName("div");
    for (let option of dropdownOptions) {
        if (selectedThicknesses.includes(option.textContent.trim())) {
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
    const colorDropdown = document.getElementById("colorDropdown");
    const thicknessDropdown = document.getElementById("thicknessDropdown");
    const colorInput = document.getElementById("colorInput");
    const thicknessInput = document.getElementById("thicknessInput");
    if (event.target !== colorDropdown && event.target !== colorInput) {
        colorDropdown.classList.remove("show");
    }
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
