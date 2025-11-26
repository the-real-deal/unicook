<?php
function FileUploadInput(string $id, ?string $name = null): Component {
    $name ??= $id;
    return new Component(__DIR__, get_defined_vars());
}
?>