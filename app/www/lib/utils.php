<?php
function createMissingDir(string $path) {
    $path = is_dir($path) ? $path : dirname($path);
    if (!file_exists($path)) {
        mkdir($path, recursive: true);
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

interface Closeable {
    public function close();
}

trait AutoCloseable {
    public function __destruct() {
        $this->close();
    }

    abstract public function close();
}

function using(Closeable $value, callable $callback) {
    try {
        return $callback($value);
    } finally {
        $value->close();
    }
}

?>