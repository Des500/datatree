<?php
$title = $data['title'];
require_once 'public/layouts/header.php';
?>

<section class="container main">
    <h2><?=$data['title']?></h2>
    <form action="/tree/store/" name="SendForm" method="post" class="form-control" onsubmit="return checkTreeForm();">
        <label for="parent_id">Введите родителя</label>
        <select name="parent_id" placeholder="введите родителя">
        <option disabled value="">Выбрать родителя...</option>
        <?php
        foreach ($data['tree'] as $item):
            ?>
            <option
                    <?php
                        if($item['item']['id'] == $data['itemdata']['parent_id']) echo 'selected';
                    ?>
                    value="<?=$item['item']['id']?>"
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
        <input type="text" name="title" placeholder="Введите название" value="<?=$data['itemdata']['title']?>">
        <br>
        <label for="description">Введите описание</label>
        <textarea name="description" id="" placeholder="Введите описание"><?=$data['itemdata']['description']?></textarea>
        <br>
        <input type="hidden" name="id" value="<?=$data['itemdata']['id']?>">
        <input type="hidden" name="isnew" value="<?=$data['isnew']?>">
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