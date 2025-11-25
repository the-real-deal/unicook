<?php
/**
 * A component for just a simple html header is created for reusing
 * the header level injection logic.
 */
$element = $props->level->toHTML();
?>
<<?= $element ?>>
    <?= $props->message ?>
</<?= $element ?>>
