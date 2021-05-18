<?php

function class_concat() {
    $args = func_get_args();

    return join(' ', $args);
}
