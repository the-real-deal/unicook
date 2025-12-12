<?php

function defineIfMissing(string $name, mixed $value): void {
    if (!defined($name)) {
        define($name, $value);
    }
}

function searchEnum(mixed $cases, mixed $value): string|int|false {
    $enumValues = array_map(fn($m) => $m->value, $cases);
    return array_search($value, $enumValues, true);
}

function envValue(string $key): ?string {
    $value = getenv($key);
    return $value === false ? null : $value;
}

?>