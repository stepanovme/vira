<?php 
    if($roleId == 1){
        echo '
            <p class="title">ГЛАВНОЕ МЕНЮ</p>
            <a href="" class="active"><img src="/assets/images/dashboard.svg" alt="">Дашборд</a>
            <a href="" class="mobile_link active-mobile"><img src="/assets/images/mobile_dashboard.svg" alt=""></a>
        ';
    } else if($roleId == 3 || $roleId == 4){
        echo '
            <p class="title">ГЛАВНОЕ МЕНЮ</p>
            <a href="" class="active"><img src="/assets/images/dashboard.svg" alt="">Дашборд</a>
            <a href=""><img src="/assets/images/pencil.svg" alt="">Гибка металла</a>
            <a href="" class="mobile_link active-mobile"><img src="/assets/images/mobile_dashboard.svg" alt=""></a>
            <a href="" class="mobile_link"><img src="/assets/images/mobile_pencil.svg" alt=""></a>
        ';
    } else if($roleId == 2 || $roleId == 5){
        echo '
        <p class="title">ГЛАВНОЕ МЕНЮ</p>
        <a href="" class="active"><img src="/assets/images/dashboard.svg" alt="">Дашборд</a>
        <a href=""><img src="/assets/images/pencil.svg" alt="">Гибка металла</a>
        <p class="title">ИНФОРМАЦИЯ</p>
        <a href=""><img src="/assets/images/people.svg" alt="">Сотрудники</a>
        <a href="" class="mobile_link active-mobile"><img src="/assets/images/mobile_dashboard.svg" alt=""></a>
        <a href="" class="mobile_link"><img src="/assets/images/mobile_pencil.svg" alt=""></a>
        <a href="" class="mobile_link"><img src="/assets/images/mobile_people.svg" alt=""></a>
        ';
    }
?>