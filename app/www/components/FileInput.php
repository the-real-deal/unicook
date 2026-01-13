<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/files.php";

function FileInput(string $id, FileType $type, ?string $name = null) {
    $name ??= $id;
    $acceptString = implode(
        ", ",
        array_map(fn($mime) => $mime->value, $type->allowedMimes()),
    );
?>
<input
    id="<?= $id ?>"
    name="<?= $name ?>"
    type="file"
    accept="<?= $acceptString ?>"
/>
<?php } ?>
