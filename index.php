<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Дашборд</title>
</head>
<body>
    
    <div class="wrapper">
        <div class="navbar">
            <div class="logo">VIRA</div>
            <img src="/assets/images/mobile_logo.png" alt="" class="logo_mobile">
            <nav>
                <p class="title">ГЛАВНОЕ МЕНЮ</p>
                <a href="" class="active"><img src="/assets/images/dashboard.svg" alt="">Дашборд</a>
                <a href=""><img src="/assets/images/pencil.svg" alt="">Гибка металла</a>
                <p class="title">ИНФОРМАЦИЯ</p>
                <a href=""><img src="/assets/images/people.svg" alt="">Сотрудники</a>
                <a href="" class="mobile_link active-mobile"><img src="/assets/images/mobile_dashboard.svg" alt=""></a>
                <a href="" class="mobile_link"><img src="/assets/images/mobile_pencil.svg" alt=""></a>
                <a href="" class="mobile_link"><img src="/assets/images/mobile_people.svg" alt=""></a>
            </nav>
        </div>
        <div class="layout">
            <header>
                <div class="profile">
                    <div class="avatar">
                        <img src="/assets/images/avatar.png" alt="">
                    </div>
                    <div class="info">
                        <p class="name">Денис Кузнецов</p>
                        <p class="role">Руководитель проекта</p>
                    </div>
                </div>
                <img class="mobile-avatar" src="/assets/images/small_logo.svg" alt="">
                <input type="checkbox" id="toggle">
                <label for="toggle" class="toggle-btn">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </label>
            </header>
            <div class="content">
                <div class="menu" id="menu">
                    <div class="link">
                        <div class="head">
                            <img src="/assets/images/dashboard_mobile.svg" alt="">
                        </div>
                        <div class="body">
                            <p>Дашборд</p>
                            <img src="/assets/images/arrow.svg" alt="">
                        </div>
                    </div>
                    <div class="link">
                        <div class="head">
                            <img src="/assets/images/pencil_mobile.svg" alt="">
                        </div>
                        <div class="body">
                            <p>Гибка металла</p>
                            <img src="/assets/images/arrow.svg" alt="">
                        </div>
                    </div>
                    <div class="link">
                        <div class="head">
                            <img src="/assets/images/people_mobile.svg" alt="">
                        </div>
                        <div class="body">
                            <p>Сотрудники</p>
                            <img src="/assets/images/arrow.svg" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
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

    </script>
</body>
</html>