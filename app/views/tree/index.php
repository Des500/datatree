<?php
$title = "главная";
require_once 'public/layouts/header.php';
?>

<section class="container info">

    <?php
        $inputChecked = '';
        include 'blocks/leftBlock.php'
    ?>

    <div class="content-block">
        <h2 id="element-title">Выберите элемент</h2>
        <p>&nbsp;</p>
        <p id="element-desc"></p>
    </div>
</section>


<?php
require_once 'public/layouts/footer.php';
?>

</body>
</html>