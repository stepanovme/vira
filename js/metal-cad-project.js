var employee = document.getElementById("metal-cad");
employee.classList.add("active");

var employeeMobile = document.getElementById("metal-cad-mobile");
employeeMobile.classList.add("active-mobile");

// var slide = document.getElementById("slide");
// slide.classList.add("active");

document.querySelector('.wrapper>.layout>.content>.content-header').style.display = 'flex';


document.addEventListener("DOMContentLoaded", function(event) {
    var animationState = localStorage.getItem('animationState');
    if (animationState === 'fillAnimation') {
        var fillScreen = document.createElement("div");
        fillScreen.classList.add("fill-screen");
        document.body.appendChild(fillScreen);

        fillScreen.addEventListener("animationend", function() {
            fillScreen.style.animation = "fillAnimationReverse 0.2s forwards";
            document.querySelector('.wrapper>.layout>.content>.content-header').style.display = 'flex';
            document.querySelector('.wrapper>.layout>.content>.slide-list').style.display = 'grid';
        }, { once: true });

        localStorage.removeItem('animationState');
    }
});