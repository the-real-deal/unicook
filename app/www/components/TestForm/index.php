<?php
function TestForm(): Component {
    $name ??= $id;
    return new Component(__DIR__, get_defined_vars());
}
?>