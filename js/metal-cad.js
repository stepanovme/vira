var employee = document.getElementById("metal-cad");
employee.classList.add("active");

var employeeMobile = document.getElementById("metal-cad-mobile");
employeeMobile.classList.add("active-mobile");

var slide = document.getElementById("slide");
slide.classList.add("active");

document.addEventListener("DOMContentLoaded", function(event) {
    // Проверьте, есть ли состояние анимации в локальном хранилище
    var animationState = localStorage.getItem('animationState');
    if (animationState === 'fillAnimation') {
        // Если анимация была активна, восстановите её
        var fillScreen = document.createElement("div");
        fillScreen.classList.add("fill-screen");
        document.body.appendChild(fillScreen);

        fillScreen.addEventListener("animationend", function() {
            fillScreen.style.animation = "fillAnimationReverse 0.2s forwards";
            // document.querySelector('.wrapper>.layout>.content>.search-header').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.content-header').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.search-header').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.slide-list').style.display = 'grid';
        }, { once: true });

        // Удалите состояние анимации из локального хранилища, чтобы оно не воспроизводилось вновь
        localStorage.removeItem('animationState');
    }
});

// document.addEventListener("DOMContentLoaded", function(event) {
//     var animationState = localStorage.getItem('animationState');
//     if (animationState === 'fillAnimation') {
//         var fillScreen = document.createElement("div");
//         fillScreen.classList.add("fill-screen");
//         document.body.appendChild(fillScreen);

//         setTimeout(function() {
//             fillScreen.style.animation = "fillAnimationReverse 0.3s forwards";
//             document.querySelector('.wrapper>.layout>.content>.content-header').style.display = 'flex';
//             document.querySelector('.wrapper>.layout>.content>.search-header').style.display = 'flex';
//             document.querySelector('.wrapper>.layout>.content>.slide-list').style.display = 'grid';
//         }, 200); 
        
//         localStorage.removeItem('animationState');
//     }
// });

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
closeBtn.addEventListener('click', function() {
    modal.style.display = 'none'; // Скрываем модальное окно при нажатии на элемент закрытия
});

// Закрываем модальное окно при клике вне его области
window.addEventListener('click', function(event) {
    if (event.target == modal) {
        modal.style.display = 'none'; // Скрываем модальное окно при клике вне его области
    }
});


