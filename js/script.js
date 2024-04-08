document.getElementById("showRegister").addEventListener("click", function(event) {
    event.preventDefault();
    
    var fillScreen = document.createElement("div");
    fillScreen.classList.add("fill-screen");
    document.body.appendChild(fillScreen);

    fillScreen.addEventListener("animationend", function() {
        fillScreen.style.animation = "fillAnimationReverse 0.2s forwards";
        document.querySelector('.form-auth').style.display = 'none';
        document.querySelector('.form-register').style.display = 'flex';
    }, { once: true });
});

document.getElementById("showAuth").addEventListener("click", function(event) {
    event.preventDefault();
    
    var fillScreen = document.createElement("div");
    fillScreen.classList.add("fill-screen");
    document.body.appendChild(fillScreen);

    fillScreen.addEventListener("animationend", function() {
        fillScreen.style.animation = "fillAnimationReverse 0.2s forwards";
        document.querySelector('.form-register').style.display = 'none';
        document.querySelector('.form-auth').style.display = 'flex';
    }, { once: true });
});
