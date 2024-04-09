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




