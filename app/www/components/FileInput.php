<?php
function FileInput(string $id, FileType $type, ?string $name = null) {
    $name ??= $id;
    $acceptString = implode(
        ', ', 
        array_map(fn($mime) => $mime->value, $type->allowedMimes())
    );
?>
<input 
    id="<?= $id ?>" 
    name="<?= $name ?>" 
    type="file" 
    accept="<?= $acceptString ?>"
/>
<?php } ?>
