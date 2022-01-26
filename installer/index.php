<?php
@session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Digraph installation wizard</title>
    <style>
        <?php echo file_get_contents(__DIR__ . '/style.css'); ?>
    </style>
</head>

<body>
    <?php include __DIR__ . '/run.php'; ?>
</body>

</html>