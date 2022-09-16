<?php
$title = "Дерево данных - авторизация";
require_once 'public/layouts/header.php';
?>

<section class="container main">
    <h1>Авторизация</h1>
    <p>Авторизация пользователя</p>
    <form action="/user/auth" method="post" class="form-control">
        <input type="email" name="email" placeholder="введите email" value="<?=$_POST['email']?>">
        <br>
        <input type="password" name="pass" placeholder="введите пароль" value="<?=$_POST['pass']?>">
        <br>
        <div class="error"><?=$data['message']?></div>
        <button class="btn" id="send">Авторизоваться</button>
    </form>
</section>


<?php
require_once 'public/layouts/footer.php';
?>

</body>
</html>