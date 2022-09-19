<div class="left-block">
    <input type="checkbox" id="menu-checkbox">
    <nav class="menu" role="navigation">

        <label for="menu-checkbox" class="btn toggle-btn" data-open="Дерево развернуть" data-close="Дерево свернуть">
        </label>
        <div class="links">
            <ul>
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
                        else:
                            echo $inputChecked;
                        endif;
                        ?>
                    >
                    <label for="input<?=$item['item']['id']?>" class="toggle-ul">
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
                    <a class="menu-item" id="item-<?=$item['item']['id']?>">
                        <?=$item['item']['id']?>|<?=$item['item']['parent_id']?>|<?=$item['item']['title']?>
                    </a>

                    <?php
                    endforeach;
                    ?>
            </ul>
        </div>
    </nav>


</div>
