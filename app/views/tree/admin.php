<?php
$title = "Дерево данных";
require_once 'public/layouts/header.php';
?>

<section class="container">
    <h2>Административная панель</h2>
    <p>&nbsp;</p>
    <div class="info">
        <div class="left-block">
            <input type="checkbox" id="menu-checkbox">
            <nav class="menu" role="navigation">

                <label for="menu-checkbox" class="btn toggle-button" data-open="Дерево развернуть" data-close="Дерево свернуть" onclick>
                </label>
                <div class="links">
                    <?php
                    $level=0;
                    foreach ($data['tree'] as $key => $item):
                        ?>

                        <?php
                        if($level<$item['level']):
                            ?>
                            <input type="checkbox" id="input<?=$item['item']['id']?>"

                                <?php
                                if($level==0):
                                    ?>
                                    checked
                                <?php
                                endif;
                                    ?>
                            >
                            <label for="input<?=$item['item']['id']?>" class="toggle-ul" data-open="Дерево развернуть" data-close="Дерево свернуть" onclick>
                            </label>
                            <ul id="ul<?=$item['item']['id']?>">

                        <?php
                        elseif ($level == $item['level']):
                            ?>
                            </li>
                        <?php
                        elseif ($level>$item['level']):
                                ?>

                            <?php
                            for($i = $item['level']; $i<$level; $i++):
                                ?>
                                </ul>
                                </li>
                            <?php
                            endfor;
                                ?>

                        <?php
                        endif;
                        $level = $item['level']
                        ?>

                        <li>
                            <a href="/tree/adminpanel/<?=$item['item']['id']?>" class="menu-item">
                            <?php
                            for ($i = 0; $i < $item['level']; $i++) echo '-';
                            ?>
                            <?=$item['item']['id']?> <?=$item['item']['parent_id']?> <?=$item['item']['title']?>
                            </a>

                    <?php
                    endforeach;
                    ?>
                </div>
            </nav>


        </div>
        <div class="content-block">
            <?php
            if(!empty($data['itemdata'])):
            ?>
                <h2><?=$data['itemdata']['id']?>|<?=$data['itemdata']['title']?></h2>
                <p><?=$data['itemdata']['description'] ?></p>
                <div class="btn-group">
                    <a href="/tree/edit/<?=$data['itemdata']['id']?>">
                        <button class="btn">Редактировать</button>
                    </a>
                    <a href="/tree/add/<?=$data['itemdata']['id']?>">
                        <button class="btn">Добавить</button>
                    </a>
                    <a href="/tree/delete/<?=$data['itemdata']['id']?>">
                        <button class="btn btn-warning">Удалить</button>
                    </a>
                </div>
            <?php
            else:
            ?>
                <h2>Корневой каталог</h2>
                <div class="btn-group">
                    <a href="/tree/add/0">
                        <button class="btn">Добавить</button>
                    </a>
                </div>
            <?php
            endif;
            ?>
        </div>
    </div>
</section>


<?php
require_once 'public/layouts/footer.php';
?>

</body>
</html>