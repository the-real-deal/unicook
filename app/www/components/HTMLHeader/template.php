<?php
/**
 * A component for just a simple html header is created for reusing
 * the header level injection logic.
 */
$element = $props->level->value;
?>
<<?= $element ?>>
    <?= $props->message ?>
</<?= $element ?>>
