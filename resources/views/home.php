<?php include 'layout/start.php'?>

<?php if ($names) { ?>

    <?php foreach ($names as $name) { ?>
        <p> <?php echo $name ?> </p>
    <?php } ?>

    <?php echo $appDebug ?>

<?php } ?>

<?php include 'layout/end.php'?>
