<?php
$acceptString = implode(
    ', ', 
    array_map(fn($mime) => $mime->value, $props->type->allowedMimes())
);
?>
<input 
    id="<?= $props->id ?>" 
    name="<?= $props->name ?>" 
    type="file" 
    accept="<?= $acceptString ?>"
/>