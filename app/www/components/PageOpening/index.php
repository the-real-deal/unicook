<?php
function PageOpening(string $title): Component {
    return new Component(__DIR__, get_defined_vars());
}
?>