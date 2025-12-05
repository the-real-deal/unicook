<?php

function requireComponent(string ...$path): void {
    require_once joinPath($path, 'index.php');
}

readonly class Component {
    private string $templatePath;

    public function __construct(
        private string $templateDir,
        private array $props,
        string $templatePath = 'template.php',
    ) {
        $this->templatePath = joinPath($templateDir, $templatePath);
    }

    function render(): string {
        // store page rendering in buffer
        // https://www.php.net/manual/en/function.ob-start.php
        ob_start();
        // include template
        $props = (object)$this->props; // make props accessible with arrow notation
        require $this->templatePath;
        // get buffer and clean it
        // https://www.php.net/manual/en/function.ob-get-clean.php
        $template = ob_get_clean();
        assert($template !== false); // assert there are no errors
        return $template;
    }

    function __toString(): string {
        return $this->render();
    }
}

?>