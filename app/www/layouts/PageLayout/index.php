<?php
function PageLayout(string $pageTitle, Component $page) {
    return new Component(__DIR__, get_defined_vars());
}
?>