<?php 
require_once 'bootstrap.php';
require_once rootPath('components', 'Title.php');
require_once rootPath('components', 'Card.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>UniCook</title>
</head>

<body>
    <!-- <?= Title('Hello, World!') ?> -->
    <?= Card() ?>
</body>

</html>
