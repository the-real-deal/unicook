<?php
function FileInput(string $id, FileType $type, ?string $name = null) {
    $name ??= $id;
    renderComponent(__DIR__, get_defined_vars());
}
?>