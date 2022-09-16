<?php
$title = "Дерево данных - Кабинет пользователя";
require_once 'public/layouts/header.php';
?>

<section class="container main">
    <h1>Кабинет пользователя</h1>
    <div class="user-info">
        <p>Привет <b><?=$data['name']?></b></p>
        <p><b>e-mail: </b><?=$data['email']?></p>
        <?php
        if($data['role'] === 'admin'):
            ?>
            <a href="/tree/adminpanel">
                <button class="btn">Администрирование</button>
            </a>
        <?php
        endif;
        ?>
        <form action="/user/dashboard" method="post">
            <input type="hidden" name="exit_button">
            <button type="submit" class="btn">Выйти</button>
        </form>
    </div>
</section>


<?php
require_once 'public/layouts/footer.php';
?>

</body>
</html>