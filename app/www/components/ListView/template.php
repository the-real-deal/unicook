<?php
requireComponent('.');

foreach ($props->elements as $e) {
    if (is_array($e)) {
        ListView($e);
    } else {
        echo $e;
    }
}
?>