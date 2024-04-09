var employee = document.getElementById("metal-cad");
employee.classList.add("active");

var employeeMobile = document.getElementById("metal-cad-mobile");
employeeMobile.classList.add("active-mobile");

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