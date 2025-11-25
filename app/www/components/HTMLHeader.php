<?php
enum HeaderLevel {
    case H1;
    case H2;
    case H3;
    case H4;
    case H5;
    case H6;

    public function toHTML(): string {
        return match($this) 
        {
            HeaderLevel::H1 => 'h1',
            HeaderLevel::H2 => 'h2',
            HeaderLevel::H3 => 'h3',
            HeaderLevel::H4 => 'h4',
            HeaderLevel::H5 => 'h5',
            HeaderLevel::H6 => 'h6',
            default => throw new Exception("Invalid Header Level"), 
        };
    }
}

function HTMLHeader(string $message, HeaderLevel $level = HeaderLevel::H1) {
    return new Component(__FILE__, get_defined_vars());
}
?>
