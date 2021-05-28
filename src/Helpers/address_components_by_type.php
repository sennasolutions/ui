<?php

function address_components_by_type($array) {
    $output = [];

    foreach($array as $component) {
        foreach($component['types'] as $type) {
            if (!isset($output[$type])) {
                $output[$type] = [];
            }
            $output[$type][] = $component;
        }
    }

    return $output;
}