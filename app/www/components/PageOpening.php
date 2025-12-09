<?php
// Note: script tags cannot be closed directly with />
<<<<<<< Updated upstream
// https://stackoverflow.com/a/69984 
function PageOpening(string $title) {
?>
=======
// https://stackoverflow.com/a/69984
function PageOpening(string $title, array $extraCss = [])
{
    ?>
>>>>>>> Stashed changes
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?></title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <!-- css -->
    <?php
    $cssPaths = ["/css/main.css"] + $extraCss;
    foreach ($cssPaths as $css) { ?>
        <link rel="stylesheet" type="text/css" href="<?= $css ?>" />
    <?php }
    ?>
    <!-- js -->
    <?php
    $scripts = array_map(fn($path) => substr($path, strlen(PROJECT_ROOT)), listFilesRec('js', 'js'));
    foreach ($scripts as $script) {
    ?>
        <script src="<?= $script ?>"></script>
    <?php } ?>
</head>
<?php } ?>
