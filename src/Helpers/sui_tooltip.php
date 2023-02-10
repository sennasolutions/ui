<?php

use Illuminate\View\ComponentAttributeBag;

/**
 * Allows for the use of a tooltip attribute, with modifiers for position and length.
 */
function sui_tooltip(ComponentAttributeBag $attributes) {
    foreach($attributes as $key => $attribute) {
        if (str($key)->startsWith('tooltip')) {
            $attributes->offsetUnset($key);

            $attributes->offsetSet('aria-label', $attribute);
            $attributes->offsetSet('data-balloon-pos', 'up');

            $positions = ['up', 'top', 'down', 'bottom', 'left', 'right', 'up-left', 'top-left', 'up-right', 'top-right', 'down-left', 'bottom-left', 'down-right', 'bottom-right'];

            foreach($positions as $position) {
                if (str($key)->contains($position)) {
                    $position = str_replace('top', 'up', $position);
                    $position = str_replace('bottom', 'down', $position);
                    $attributes->offsetSet('data-balloon-pos', $position);
                }
            }

            $lenghts = ['small', 'medium', 'large', 'xlarge', 'fit'];

            foreach($lenghts as $lenght) {
                if (str($key)->contains($lenght)) {
                    $attributes->offsetSet('data-balloon-length', $lenght);
                }
            }
        }
    }
}