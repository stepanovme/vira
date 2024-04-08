const toggle = document.getElementById('toggle');
const menu = document.getElementById('menu');

toggle.addEventListener('click', function() {
    // Проверяем, если у меню уже есть стиль right: 0, значит меню отображается,
    // и мы хотим его скрыть, установив right в -100vw
    if (menu.style.right === '0px') {
        menu.style.right = '-100vw';
    } else {
        // Если меню скрыто, то отображаем его, установив right в 0
        menu.style.right = '0';
    }
});