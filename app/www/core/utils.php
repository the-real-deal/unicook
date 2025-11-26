<?php

function defineIfMissing(string $name, mixed $value): void {
    if (!defined($name)) {
        define($name, $value);
    }
}

?>