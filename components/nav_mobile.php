<?php 
                if($roleId == 1){
                    echo '
                    <div class="link">
                        <div class="head">
                            <img src="/assets/images/dashboard_mobile.svg" alt="">
                        </div>
                        <div class="body">
                            <p>Дашборд</p>
                            <img src="/assets/images/arrow.svg" alt="">
                        </div>
                    </div>
                    ';
                } else if($roleId == 3 || $roleId == 4){
                    echo '
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
                    ';
                } else if($roleId == 2 || $roleId == 5){
                    echo '
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
                    ';
                }
                ?>