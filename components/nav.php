<?php 
    if($roleId == 1){
        echo '
            <p class="title">ГЛАВНОЕ МЕНЮ</p>
            <a href="index.php" id="dashboard"><img src="/assets/images/dashboard.svg" alt="">Дашборд</a>
            <a href="index.php" id="dashboard-mobile" class="mobile_link"><img src="/assets/images/mobile_dashboard.svg" alt=""></a>
        ';
    } else if($roleId == 3 || $roleId == 4){
        echo '
            <p class="title">ГЛАВНОЕ МЕНЮ</p>
            <a href="index.php" id="dashboard"><img src="/assets/images/dashboard.svg" alt="">Дашборд</a>
            <a href="" id="metal-cad"><img src="/assets/images/pencil.svg" alt="">Гибка металла</a>
            <a href="index.php" id="dashboard-mobile" class="mobile_link"><img src="/assets/images/mobile_dashboard.svg" alt=""></a>
            <a href="" id="metal-cad-mobile" class="mobile_link"><img src="/assets/images/mobile_pencil.svg" alt=""></a>
        ';
    } else if($roleId == 2 || $roleId == 5){
        echo '
        <p class="title">ГЛАВНОЕ МЕНЮ</p>
        <a href="index.php" id="dashboard"><img src="/assets/images/dashboard.svg" alt="">Дашборд</a>
        <a href="" id="metal-cad"><img src="/assets/images/pencil.svg" alt="">Гибка металла</a>
        <p class="title">ИНФОРМАЦИЯ</p>
        <a href="employee.php" id="employee"><img src="/assets/images/people.svg" alt="">Сотрудники</a>
        <a href="index.php" id="dashboard-mobile" class="mobile_link"><img src="/assets/images/mobile_dashboard.svg" alt=""></a>
        <a href="" id="metal-cad-mobile" class="mobile_link"><img src="/assets/images/mobile_pencil.svg" alt=""></a>
        <a href="employee.php" id="employee-mobile" class="mobile_link"><img src="/assets/images/mobile_people.svg" alt=""></a>
        ';
    }
?>