<?php
enum HeaderLevel: string {
    case H1 = 'h1';
    case H2 = 'h2';
    case H3 = 'h3';
    case H4 = 'h4';
    case H5 = 'h5';
    case H6 = 'h6';
}

function HTMLHeader(string $message, HeaderLevel $level = HeaderLevel::H1) {
    return new Component(__FILE__, get_defined_vars());
}
?>
