<?php
function FileInput(string $id, FileType $type, ?string $name = null): Component {
    $name ??= $id;
    return new Component(__DIR__, get_defined_vars());
}
?>