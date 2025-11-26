<?php
function MainLayout(string $title, array $children): Component {
    return new Component(__DIR__, get_defined_vars());
}
?>