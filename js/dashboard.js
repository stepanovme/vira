var dashboard = document.getElementById("dashboard");
dashboard.classList.add("active");

var dashboardMobile = document.getElementById("dashboard-mobile");
dashboardMobile.classList.add("active-mobile");

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
        }, { once: true });

        // Удалите состояние анимации из локального хранилища, чтобы оно не воспроизводилось вновь
        localStorage.removeItem('animationState');
    }
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