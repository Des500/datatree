<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?=$title?>">
    <link rel="stylesheet" href="/public/css/styles.min.css?<?=time()?>">
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />-->
    <title><?=$title?></title>
</head>
<body>
<header>
    <div class="container top-menu">
        <div class="nav">
            <a href="/">Главная</a>
        </div>
    </div>
    <div class="container middle">
        <div class="logo">
            <a href="/">
                <img src="/public/images/logo.svg" alt="">
                <span> Некое дерево</span>
            </a>
        </div>
        <div class="auth-checkout">
            <?php
//                $user = $this->Model('UserModel');
//                $user->getUser();
                if(empty($_SESSION['login'])):
            ?>
                <div>
                    <a href="/user/auth">
                    <button class="btn auth">Войти</button>
                    </a>
                    <a href="/user/reg">
                        <button class="btn reg">Регистация</button>
                    </a><br>
                </div>
            <?php
                else:
            ?>
                    <a href="/user/dashboard">
                        <button class="btn dashboard">Кабинет пользователя</button>
                    </a><br>
            <?php
                endif;
            ?>
        </div>
    </div>

</header>
<section class="container">
    <div id="notif-message">
        <?php
            $msgError = NotifMessage::getStatus('error');
            if (!empty($msgError)):
        ?>
            <div class="error"><?=$msgError?></div>
        <?php
            endif;
        ?>
        <?php
        $msgSuccess = NotifMessage::getStatus('success');
        if (!empty($msgSuccess)):
            ?>
            <div class="success"><?=$msgSuccess?></div>
        <?php
        endif;
        ?>
    </div>
</section>
