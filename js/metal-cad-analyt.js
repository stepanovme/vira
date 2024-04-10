var employee = document.getElementById("metal-cad");
employee.classList.add("active");

var employeeMobile = document.getElementById("metal-cad-mobile");
employeeMobile.classList.add("active-mobile");

var width = window.innerWidth;

document.addEventListener("DOMContentLoaded", function(event) {
    var animationState = localStorage.getItem('animationState');
    var width = window.innerWidth;

    if (animationState === 'fillAnimation') {
        var fillScreen = document.createElement("div");
        fillScreen.classList.add("fill-screen");
        document.body.appendChild(fillScreen);

        fillScreen.addEventListener("animationend", function() {
            fillScreen.style.animation = "fillAnimationReverse 0.2s forwards";
            document.querySelector('.wrapper>.layout>.content>.content-header').style.display = 'flex';
            if(width <= 560){
                document.querySelector('.wrapper>.layout>.content>.subtitle').style.display = 'block';
            } else{
                document.querySelector('.wrapper>.layout>.content>.subtitle').style.display = 'flex';
            }
            document.querySelector('.wrapper>.layout>.content>.analyt').style.display = 'grid';
            document.querySelector('.wrapper>.layout>.content>.table').style.display = 'block';
            document.querySelector('.wrapper>.layout>.content>.table-process').style.display = 'block';
        }, { once: true });

        localStorage.removeItem('animationState');
    }
});