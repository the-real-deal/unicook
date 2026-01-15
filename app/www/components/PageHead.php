<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

// Note: script tags cannot be closed directly with />
// https://stackoverflow.com/a/69984

function PageHead(string $title, array $extraCss = []) {
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?></title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <!-- css -->
    <?php
    $cssPaths = array_merge(["/css/main.css"], $extraCss);
    foreach ($cssPaths as $css) { 
    ?>
        <link rel="stylesheet" type="text/css" href="<?= $css ?>" />
    <?php } ?>
</head>
<?php
} ?>
