var employee = document.getElementById("metal-cad");
employee.classList.add("active");

var employeeMobile = document.getElementById("metal-cad-mobile");
employeeMobile.classList.add("active-mobile");
var screenWidth = window.innerWidth;

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
            document.querySelector('.wrapper>.layout>.content>.slide-list').style.display = 'grid';
            document.querySelector('.wrapper>.layout>.content>.information-bars').style.display = 'grid';
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

document.addEventListener('DOMContentLoaded', function() {
    // Находим все элементы с классом "slide" и "tr" и добавляем обработчик события клика
    const slides = document.querySelectorAll('.slide');
    const tableRows = document.querySelectorAll('tr');

    slides.forEach(slide => {
        slide.addEventListener('click', function() {
            // Получаем значение атрибута "data-project-id"
            const ticketId = this.dataset.ticketId;
            // Переходим на страницу metal-cad-project.php, передавая значение data-project-id в качестве параметра
            window.location.href = 'metal-cad-ticket.php?ticketId=' + ticketId;
        });
    });

    tableRows.forEach(row => {
        row.addEventListener('click', function() {
            // Получаем значение атрибута "data-project-id"
            const ticketId = this.dataset.ticketId;
            // Переходим на страницу metal-cad-project.php, передавая значение data-project-id в качестве параметра
            window.location.href = 'metal-cad-ticket.php?ticketId=' + ticketId;
        });
    });
});