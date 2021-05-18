<?php

function class_merge() {
    $args = array_map(fn($x) => explode(' ', $x), func_get_args());

    return implode(' ', array_unique(array_merge(...$args)));
}
