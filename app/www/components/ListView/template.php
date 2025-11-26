<?php
requireComponent('.');

foreach ($props->elements as $e) {
    if (is_array($e)) {
        echo ListView($e);
    } else {
        echo $e;
    }
}
?>