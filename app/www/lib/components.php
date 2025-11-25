<?php

class Component {
    private string $templatePath;

    public function __construct(
        private string $__FILE__,
        private array $props,
        private string $templateExtension = 'template',
    ) {
        $extension = pathinfo($__FILE__, PATHINFO_EXTENSION);
        $filename = pathinfo($__FILE__, PATHINFO_FILENAME);
        $templateFile = $filename . '.' . $templateExtension . '.' . $extension;
        $this->templatePath = joinPath(dirname($__FILE__), $templateFile);
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