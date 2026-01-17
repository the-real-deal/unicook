<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/files.php";
require_once "lib/utils.php";

function FileInput(
    string $id, 
    FileType $type, 
    ?string $name = null, 
    bool $hidden = false,
    bool $required = false,
) {
    $name ??= $id;
    $acceptString = implode(", ", enumValues($type->allowedMimes()));
?>
<input
    id="<?= $id ?>"
    name="<?= $name ?>"
    type="file"
    accept="<?= $acceptString ?>"
    <?= $hidden ? "hidden" : "" ?>
    <?= $required ? "required" : "" ?>
/>
<?php } ?>
