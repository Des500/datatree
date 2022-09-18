<?php
$title = "добавление элемента";
require_once 'public/layouts/header.php';
?>

<section class="container main">
    <h2>Добавление элемента</h2>
    <form action="/tree/store" name="SendForm" method="post" class="form-control" onsubmit="return checkTreeForm();">
        <label for="parent_id">Введите родителя</label>
        <select name="parent_id" placeholder="введите родителя" value="<?=$data['itemdata']['parent_id']?>">
        <option selected disabled value="">Выбрать родителя...</option>
        <?php
        foreach ($data['tree'] as $item):
            ?>
            <option
                    <?php
                        if($item['item']['id'] == $data['parent_id']) echo 'selected';
                    ?>
                >
                <?php
                    for ($i = 0; $i < $item['level']; $i++) echo '-';
                ?>
                |<?=$item['item']['id']?>|<?=$item['item']['title']?>
            </option>
        <?php
        endforeach;
        ?>
        </select>
        <br>
        <label for="title">Введите название</label>
        <input type="text" name="title" placeholder="Введите название" value="">
        <br>
        <label for="description">Введите описание</label>
        <textarea name="description" id="" placeholder="Введите описание"></textarea>
        <br>
        <div class="error"><?=$data['message']?></div>
        <div class="btn-group">
            <button type="submit" class="btn btn-success">Сохранить</button>
            <a onclick="history.back();return false;">
                <button class="btn">Отмена</button>
            </a>
        </div>
    </form>
</section>

<?php
require_once 'public/layouts/footer.php';
?>

</body>
</html>