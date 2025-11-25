<?php
function PageLayout(string $pageTitle, Component $page) {
    return new Component(__FILE__, get_defined_vars());
}
?>