<?php 
require_once 'bootstrap.php';
$Title = includeComponent('components/Title');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniCook</title>
</head>

<body>
    <?= $Title(message: 'Hello, World!') ?>
</body>

</html>
