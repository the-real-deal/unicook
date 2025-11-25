<?php
function MainLayout(string $pageTitle, array $children) {
    return new Component(__FILE__, get_defined_vars());
}
?>