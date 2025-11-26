<?php
function MainLayout(string $pageTitle, array $children) {
    return new Component(__DIR__, get_defined_vars());
}
?>