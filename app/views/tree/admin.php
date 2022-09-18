<?php
$title = "админ панель";
require_once 'public/layouts/header.php';
?>

<section class="container">
    <h2>Административная панель</h2>
    <p>&nbsp;</p>
    <div class="info">
        <?php
            $inputChecked = 'checked';
            include 'blocks/leftBlock.php'
        ?>
        <div class="content-block">

            <div class="content-block">
                <h2 id="element-title"></h2>
                <p>&nbsp;</p>
                <p id="element-desc"></p>
            </div>

            <div class="btn-group" id="element-btn" style="display: none">
                <a href="" id="element-edit">
                    <button class="btn">Редактировать</button>
                </a>
                <a href="" id="element-add">
                    <button class="btn">Добавить</button>
                </a>
                <a href="" id="element-delete">
                    <button class="btn btn-warning">Удалить</button>
                </a>
            </div>
        </div>
    </div>
</section>


<?php
require_once 'public/layouts/footer.php';
?>

</body>
</html>