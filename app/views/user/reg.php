<?php
$title = "регистрация";
require_once 'public/layouts/header.php';
?>

<section class="container main">
    <h1>Регистрация</h1>
    <p>Регистрация нового пользователя</p>
    <form action="/user/reg" method="post" class="form-control">
        <input type="text" name="name" placeholder="введите name" value="<?=$_POST['name']?>">
        <br>
        <input type="email" name="email" placeholder="введите email" value="<?=$_POST['email']?>">
        <br>
        <input type="password" name="pass" placeholder="введите пароль" value="<?=$_POST['pass']?>">
        <br>
        <input type="password" name="re_pass" placeholder="повторите пароль" value="<?=$_POST['pass']?>">
        <br>
        <button class="btn" id="send">Зарегистрировать</button>
    </form>
</section>


<?php
require_once 'public/layouts/footer.php';
?>

</body>
</html>