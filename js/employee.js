var employee = document.getElementById("employee");
employee.classList.add("active");

var employeeMobile = document.getElementById("employee-mobile");
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
            document.querySelector('.wrapper>.layout>.content>table').style.display = 'table';
            document.querySelector('.wrapper>.layout>.content>.content-header').style.display = 'flex';
        }, { once: true });

        // Удалите состояние анимации из локального хранилища, чтобы оно не воспроизводилось вновь
        localStorage.removeItem('animationState');
    }
});

document.querySelectorAll('.role-select').forEach(select => {
    select.addEventListener('change', function() {
        const userId = this.dataset.userid;
        const roleId = this.value;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'function/updateUserRole.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Роль пользователя обновлена');
            } else {
                console.error('Произошла ошибка при обновлении роли пользователя');
            }
        };
        xhr.send('userId=' + userId + '&roleId=' + roleId);
    });
});